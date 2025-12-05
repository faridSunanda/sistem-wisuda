const SWAL_CONFIG = {
    confirmButtonColor: '#435ebe',
    cancelButtonColor: '#6c757d'
};

export function initActions(config, table) {
    const { downloadPptUrl, pindahkanKeUrl, detailUrl, editUrl, deleteUrl } = config;

    initGlobalFunctions(detailUrl, editUrl, deleteUrl);
    initCheckboxHandlers();
    initButtonHandlers(config, table);
    initModalHandlers(downloadPptUrl);
}

// Global functions
function initGlobalFunctions(detailUrl, editUrl, deleteUrl) {
    window.lihatData = function(id) {
        const url = detailUrl ? detailUrl.replace(':id', id) : `/admin/group-wisudawan/${id}`;
        window.location.href = url;
    };

    window.editData = function(id) {
        const url = editUrl ? editUrl.replace(':id', id) : `/admin/group-wisudawan/${id}/edit`;
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
    const url = deleteUrl ? deleteUrl.replace(':id', id) : `/admin/group-wisudawan/${id}`;
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

// Checkbox handlers
function initCheckboxHandlers() {
    $('#selectAllCheckbox, #selectAllHeader').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.wisudawan-checkbox:not(:disabled)').prop('checked', isChecked);
        syncSelectAllState();
    });

    $(document).on('change', '.wisudawan-checkbox', function() {
        syncSelectAllState();
    });
}

function syncSelectAllState() {
    const enabledCheckboxes = $('.wisudawan-checkbox:not(:disabled)');
    const checkedCheckboxes = $('.wisudawan-checkbox:not(:disabled):checked');
    
    const allChecked = enabledCheckboxes.length > 0 && 
                      enabledCheckboxes.length === checkedCheckboxes.length;
    
    $('#selectAllCheckbox, #selectAllHeader').prop('checked', allChecked);
}

// Button handlers
function initButtonHandlers(config, table) {
    const { pindahkanKeUrl } = config;

    $('#pindahkanBtn').on('click', function() {
        handlePindahkanKe(this, pindahkanKeUrl, table);
    });
}

// Pindahkan ke
function handlePindahkanKe(buttonEl, pindahkanKeUrl, table) {
    const selectedIds = getSelectedIds();
    const targetGroupId = $('#targetGroupId').val();
    const targetSesiId = $('#targetSesiId').val();

    if (!validateSelection(selectedIds, 'Pilih minimal satu wisudawan untuk dipindahkan.')) {
        return;
    }

    if (!targetGroupId || !targetSesiId) {
        showWarningAlert('Pilih group dan sesi tujuan.');
        return;
    }

    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: `Memindahkan ${selectedIds.length} wisudawan ke group dan sesi yang dipilih?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: SWAL_CONFIG.confirmButtonColor,
        cancelButtonColor: SWAL_CONFIG.cancelButtonColor,
        confirmButtonText: 'Ya, pindahkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            executePindahkanKe(buttonEl, pindahkanKeUrl, selectedIds, targetGroupId, targetSesiId, table);
        }
    });
}

function executePindahkanKe(buttonEl, pindahkanKeUrl, selectedIds, targetGroupId, targetSesiId, table) {
    const $btn = $(buttonEl);
    setButtonLoading($btn, true);

    $.ajax({
        url: pindahkanKeUrl,
        method: 'POST',
        data: {
            ids: selectedIds,
            target_group_id: targetGroupId,
            target_sesi_id: targetSesiId,
            _token: getCsrfToken()
        },
        success: function(response) {
            if (response.success) {
                showSuccessAlert(response.message || 'Data wisudawan berhasil dipindahkan.', () => {
                    refreshTable(table);
                    resetSelection();
                    resetPindahkanForm();
                });
            } else {
                throw new Error(response.message || 'Terjadi kesalahan saat memindahkan data.');
            }
        },
        error: function(xhr) {
            handleAjaxError(xhr, 'Terjadi kesalahan saat memindahkan data.');
        },
        complete: function() {
            setButtonLoading($btn, false);
        }
    });
}

// Modal handlers
function initModalHandlers(downloadPptUrl) {
    $('#downloadPptBtn').on('click', function() {
        handleDownloadPptClick();
    });

    $('#closeDownloadModalBtn').on('click', closeDownloadModal);

    $('#downloadPptModal').on('click', function(e) {
        if ($(e.target).is('#downloadPptModal')) {
            closeDownloadModal();
        }
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && !$('#downloadPptModal').hasClass('hidden')) {
            closeDownloadModal();
        }
    });

    $('#confirmDownloadBtn').on('click', function() {
        handleConfirmDownload(this, downloadPptUrl);
    });
}

// Download PPT
function handleDownloadPptClick() {
    const selectedIds = getSelectedIds();
    
    if (!validateSelection(selectedIds, 'Pilih minimal satu wisudawan untuk download PPT.')) {
        return;
    }

    const count = selectedIds.length;
    $('#downloadInfoText').text(`Download ${count} data wisudawan`);
    $('#downloadPosisiKanan').val('kanan');
    $('#downloadUrutan').val('pertama');
    showDownloadModal();
}

function handleConfirmDownload(buttonEl, downloadPptUrl) {
    const selectedIds = getSelectedIds();
    const posisi = $('#downloadPosisiKanan').val();
    const urutan = $('#downloadUrutan').val();
    
    if (!validateSelection(selectedIds, 'Pilih minimal satu wisudawan untuk download PPT.')) {
        return;
    }

    const $btn = $(buttonEl);
    setButtonLoading($btn, true, '<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

    $.ajax({
        url: downloadPptUrl,
        method: 'POST',
        data: {
            ids: selectedIds,
            posisi: posisi,
            urutan: urutan,
            _token: getCsrfToken()
        },
        success: function(response) {
            closeDownloadModal();
            
            if (response.success) {
                showSuccessAlert(response.message || 'Download PPT berhasil dimulai.');
            } else {
                showErrorAlert(response.message || 'Terjadi kesalahan saat download PPT.');
            }
        },
        error: function(xhr) {
            handleAjaxError(xhr, 'Terjadi kesalahan saat download PPT.');
        },
        complete: function() {
            setButtonLoading($btn, false);
        }
    });
}

function showDownloadModal() {
    $('#downloadPptModal').removeClass('hidden').addClass('flex');
}

function closeDownloadModal() {
    $('#downloadPptModal').removeClass('flex').addClass('hidden');
}

// Helper functions
function getSelectedIds() {
    const selectedIds = [];
    $('.wisudawan-checkbox:checked').each(function() {
        selectedIds.push($(this).val());
    });
    return selectedIds;
}

function validateSelection(selectedIds, message) {
    if (selectedIds.length === 0) {
        showWarningAlert(message);
        return false;
    }
    return true;
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function setButtonLoading(buttonEl, isLoading, loadingHtml = '<i class="fas fa-spinner fa-spin"></i>') {
    const $btn = $(buttonEl);
    if (isLoading) {
        $btn.data('original-html', $btn.html());
        $btn.prop('disabled', true).html(loadingHtml);
    } else {
        const originalHtml = $btn.data('original-html') || '';
        $btn.prop('disabled', false).html(originalHtml);
    }
}

function refreshTable(table) {
    if (typeof table !== 'undefined' && table) {
        table.draw();
    } else if (typeof window.downloadPptTable !== 'undefined') {
        window.downloadPptTable.draw();
    } else {
        location.reload();
    }
}

function resetSelection() {
    $('.wisudawan-checkbox').prop('checked', false);
    $('#selectAllCheckbox, #selectAllHeader').prop('checked', false);
}

function resetPindahkanForm() {
    $('#targetGroupId, #targetSesiId').val('');
}

function showSuccessAlert(message, callback = null) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: message,
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
        title: 'Error',
        text: message,
        confirmButtonColor: SWAL_CONFIG.confirmButtonColor
    });
}

function showWarningAlert(message) {
    Swal.fire({
        icon: 'warning',
        title: 'Peringatan',
        text: message,
        confirmButtonColor: SWAL_CONFIG.confirmButtonColor
    });
}

function showInfoAlert(message) {
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: message,
        confirmButtonColor: SWAL_CONFIG.confirmButtonColor
    });
}

function handleAjaxError(xhr, defaultMessage) {
    let message = defaultMessage;
    if (xhr.responseJSON && xhr.responseJSON.message) {
        message = xhr.responseJSON.message;
    }
    showErrorAlert(message);
}
