import { initDataTable } from './table.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

function initJadwalWisuda(config) {
    try {

        const table = initDataTable(config);
        window.jadwalWisudaTable = table;

        initExports(config);

        initActions(config);

        initAdditionalFeatures();


    } catch (error) {
        console.error('Error initializing Jadwal Wisuda:', error);
        alert('Terjadi kesalahan saat menginisialisasi halaman Jadwal Wisuda.');
    }
}

function initAdditionalFeatures() {
    $(document).on('click', '#refreshTable', function() {
        if (typeof window.jadwalWisudaTable !== 'undefined') {
            window.jadwalWisudaTable.draw();
            showTempMessage('Data diperbarui', 'success');
        }
    });

    $(document).on('keyup', '.dataTables_filter input', function() {
        const searchTerm = $(this).val();
        if (searchTerm.length > 2) {
            $('.dataTables_empty').parent().hide();
        }
    });

    $(document).on('click', '#jadwalWisudaTable tbody tr', function(e) {
        if (!$(e.target).closest('.btn-action').length) {
            const data = window.jadwalWisudaTable.row(this).data();
            if (data && data.id) {
                window.lihatData(data.id);
            }
        }
    });
}

function showTempMessage(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' : 'alert-info';

    const $alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('body').append($alert);

    setTimeout(() => {
        $alert.alert('close');
    }, 3000);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.jadwalWisudaConfig !== 'undefined') {
            initJadwalWisuda(window.jadwalWisudaConfig);
        }
    });
} else {
    // DOM already loaded
    if (typeof window.jadwalWisudaConfig !== 'undefined') {
        initJadwalWisuda(window.jadwalWisudaConfig);
    }
}
