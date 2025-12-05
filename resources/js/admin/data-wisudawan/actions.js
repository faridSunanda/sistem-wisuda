const SWAL_CONFIG = {
    confirmButtonColor: '#435ebe',
    cancelButtonColor: '#6c757d'
};

export function initActions(config) {
    const { detailUrl, editUrl, deleteUrl } = config;

    initGlobalFunctions(detailUrl, editUrl, deleteUrl);
}

// Global functions
function initGlobalFunctions(detailUrl, editUrl, deleteUrl) {
    window.lihatData = function(id) {
        const url = detailUrl ? detailUrl.replace(':id', id) : `/admin/data-wisudawan/${id}`;
        window.location.href = url;
    };

    window.editData = function(id) {
        const url = editUrl ? editUrl.replace(':id', id) : `/admin/data-wisudawan/${id}/edit`;
        window.location.href = url;
    };

    window.hapusData = function(id, buttonEl) {
        if (!buttonEl) {
            console.warn('⚠️ Button element tidak ditemukan.');
            return;
        }

        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Anda tidak akan dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: SWAL_CONFIG.cancelButtonColor,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteWisudawan(id, buttonEl, deleteUrl);
            }
        });
    };
}

// Delete wisudawan
function deleteWisudawan(id, buttonEl, deleteUrl) {
    const url = deleteUrl ? deleteUrl.replace(':id', id) : `/admin/data-wisudawan/${id}`;
    const token = getCsrfToken();
    
    setButtonLoading(buttonEl, true);

    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessAlert(data.message || 'Data berhasil dihapus.', () => {
                    refreshTable();
                });
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat menghapus data.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlert(error.message || 'Terjadi kesalahan.');
        })
        .finally(() => {
            setButtonLoading(buttonEl, false);
        });
}

// Helper functions
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function setButtonLoading(buttonEl, isLoading) {
    const button = buttonEl;
    if (isLoading) {
        button.dataset.originalHtml = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
    } else {
        const originalHtml = button.dataset.originalHtml || '';
        button.innerHTML = originalHtml;
        button.disabled = false;
    }
}

function refreshTable() {
    if (typeof window.dataWisudawanTable !== 'undefined') {
        window.dataWisudawanTable.draw();
    } else {
        location.reload();
    }
}

function showSuccessAlert(message, callback = null) {
    return Swal.fire({
        icon: 'success',
        title: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        confirmButtonColor: SWAL_CONFIG.confirmButtonColor
    }).then(() => {
        if (callback && typeof callback === 'function') {
            callback();
        }
    });
}

function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        confirmButtonColor: SWAL_CONFIG.confirmButtonColor
    });
}
