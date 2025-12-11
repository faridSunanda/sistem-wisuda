import { initDataTable } from './table.js';
import { initFilters } from './filter.js';

window.initDataWisudawan = function(config) {
    try {
        const table = initDataTable(config);
        window.dataWisudawanTable = table;
        initFilters(config, table);
    } catch (error) {
        console.error('Error initializing Data Wisudawan:', error);
    }
};

const CONFIRM_CONFIG = {
    konfirmasi: {
        title: 'Konfirmasi Pembayaran?',
        text: 'Apakah Anda yakin ingin mengkonfirmasi pembayaran ini? Status akan berubah menjadi "Sudah Bayar".',
        confirmButtonColor: '#f59e0b',
        confirmButtonText: 'Ya, Konfirmasi',
        successMessage: 'Pembayaran berhasil dikonfirmasi',
        errorMessage: 'Terjadi kesalahan saat mengkonfirmasi pembayaran'
    },
    proses: {
        title: 'Proses Wisudawan?',
        text: 'Apakah Anda yakin ingin memproses wisudawan ini? Mahasiswa akan di-approve untuk mengikuti wisuda.',
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Ya, Proses',
        successMessage: 'Wisudawan berhasil diproses',
        errorMessage: 'Terjadi kesalahan saat memproses wisudawan'
    }
};

function showConfirmation(config, onConfirm) {
    if (typeof Swal === 'undefined') {
        if (confirm(config.text)) {
            onConfirm();
        }
        return;
    }

    Swal.fire({
        title: config.title,
        text: config.text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: '#6b7280',
        confirmButtonText: config.confirmButtonText,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            onConfirm();
        }
    });
}

function reloadTable() {
    if (window.dataWisudawanTable) {
        window.dataWisudawanTable.ajax.reload(null, false);
    } else {
        location.reload();
    }
}

function showSuccess(message, changeFilter = null) {
    reloadTable();
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: message,
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            if (changeFilter && $('#filterStatus').length) {
                $('#filterStatus').val(changeFilter).trigger('change');
            }
        });
    } else {
        alert(message);
        if (changeFilter && $('#filterStatus').length) {
            $('#filterStatus').val(changeFilter).trigger('change');
        }
    }
}

function showError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: message
        });
    } else {
        alert(message);
    }
}

function submitRequest(url, config, changeFilter = null) {
    $.ajax({
        url: url,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        success: function(response) {
            const message = (response.success && response.message) 
                ? response.message 
                : (response.message || config.successMessage);
            showSuccess(message, changeFilter);
        },
        error: function(xhr) {
            console.error('Error submitting request:', xhr);
            let message = config.errorMessage;
            
            if (xhr.responseJSON) {
                message = xhr.responseJSON.message || xhr.responseJSON.error || message;
            } else if (xhr.responseText) {
                try {
                    const parsed = JSON.parse(xhr.responseText);
                    message = parsed.message || parsed.error || message;
                } catch (e) {
                    message = 'Terjadi kesalahan saat memproses request';
                }
            }
            
            showError(message);
        }
    });
}

window.konfirmasiPembayaran = function(id, url) {
    showConfirmation(CONFIRM_CONFIG.konfirmasi, () => {
        submitRequest(url, CONFIRM_CONFIG.konfirmasi, 'sudah_bayar');
    });
};

window.prosesWisudawan = function(id, url) {
    showConfirmation(CONFIRM_CONFIG.proses, () => {
        submitRequest(url, CONFIRM_CONFIG.proses, 'terverifikasi');
    });
};

window.konfirmasiSemuaPembayaran = function(url) {
    const confirmText = 'Apakah Anda yakin ingin mengkonfirmasi semua pembayaran yang menunggu konfirmasi?';
    
    if (typeof Swal === 'undefined') {
        if (confirm(confirmText)) {
            submitKonfirmasiSemua(url);
        }
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Semua Pembayaran?',
        text: 'Apakah Anda yakin ingin mengkonfirmasi semua pembayaran yang statusnya "Menunggu Konfirmasi"?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Konfirmasi Semua',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            submitKonfirmasiSemua(url);
        }
    });
};

function submitKonfirmasiSemua(url) {
    $.ajax({
        url: url,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        success: function(response) {
            const message = response.message || 'Semua pembayaran berhasil dikonfirmasi';
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => reloadTable());
            } else {
                alert(message);
                reloadTable();
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat mengkonfirmasi semua pembayaran';
            showError(message);
        }
    });
}

window.prosesSemuaWisudawan = function(url) {
    const confirmText = 'Apakah Anda yakin ingin memproses semua wisudawan yang sudah bayar?';
    
    if (typeof Swal === 'undefined') {
        if (confirm(confirmText)) {
            submitProsesSemua(url);
        }
        return;
    }

    Swal.fire({
        title: 'Proses Semua Wisudawan?',
        text: 'Apakah Anda yakin ingin memproses semua wisudawan yang statusnya "Sudah Bayar"?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Proses Semua',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            submitProsesSemua(url);
        }
    });
};

function submitProsesSemua(url) {
    $.ajax({
        url: url,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        success: function(response) {
            const message = response.message || 'Semua wisudawan berhasil diproses';
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    if ($('#filterStatus').length) {
                        $('#filterStatus').val('terverifikasi').trigger('change');
                    } else {
                        reloadTable();
                    }
                });
            } else {
                alert(message);
                if ($('#filterStatus').length) {
                    $('#filterStatus').val('terverifikasi').trigger('change');
                } else {
                    reloadTable();
                }
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses semua wisudawan';
            showError(message);
        }
    });
}

function initialize() {
    if (typeof window.dataWisudawanConfig !== 'undefined' && typeof window.initDataWisudawan === 'function') {
        window.initDataWisudawan(window.dataWisudawanConfig);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialize);
} else {
    initialize();
}
