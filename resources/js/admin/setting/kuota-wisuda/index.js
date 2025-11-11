// Main Kuota Wisuda Initialization
import { initDataTable } from './table.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

function initKuotaWisuda(config) {
    const table = initDataTable(config);

    if (!table) {
        console.error('Gagal menginisialisasi DataTable. Inisialisasi dihentikan.');
        return;
    }

    window.kuotaWisudaTable = table;

    initExports(config);

    initActions(config);
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.kuotaWisudaConfig !== 'undefined') {
        initKuotaWisuda(window.kuotaWisudaConfig);
    } else {
        console.error('Konfigurasi kuotaWisudaConfig tidak ditemukan.');
    }
});

window.initKuotaWisuda = initKuotaWisuda;
