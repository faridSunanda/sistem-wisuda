export function initActions(config) {
    const { detailUrl, editUrl, deleteUrl } = config;

    window.lihatData = function(id) {
        if (detailUrl) {
            window.location.href = detailUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/setting/kuota-wisuda/${id}`;
        }
    };

    window.editData = function(id) {
        if (editUrl) {
            window.location.href = editUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/setting/kuota-wisuda/${id}/edit`;
        }
    };

    window.hapusData = function(event, id) {
        if (!confirm('Apakah Anda yakin ingin menghapus kuota wisuda ini?')) {
            return;
        }

        const url = deleteUrl ? deleteUrl.replace(':id', id) : `/admin/setting/kuota-wisuda/${id}`;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const button = event.currentTarget;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Kuota wisuda berhasil dihapus.', 'success');

                if (typeof window.kuotaWisudaTable !== 'undefined') {
                    window.kuotaWisudaTable.draw();
                } else {
                    location.reload();
                }
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat menghapus data.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Terjadi kesalahan saat menghapus data.', 'error');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    };

    function showToast(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            alert(message);
        }
    }
}
