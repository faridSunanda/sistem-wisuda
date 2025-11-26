import { initDataTable } from './table.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

window.initJadwalPelaksanaan = function(config) {
    try {
        const table = initDataTable(config);
        window.jadwalPelaksanaanTable = table;

        initExports(config);

        initActions(config);

    } catch (error) {
        console.error('Error initializing Jadwal Pelaksanaan:', error);
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded');
        if (typeof window.jadwalPelaksanaanConfig !== 'undefined' && typeof window.initJadwalPelaksanaan === 'function') {
            window.initJadwalPelaksanaan(window.jadwalPelaksanaanConfig);
        } else {
            console.error('Jadwal Pelaksanaan config or function not found');
        }
    });
} else {
    if (typeof window.jadwalPelaksanaanConfig !== 'undefined' && typeof window.initJadwalPelaksanaan === 'function') {
        window.initJadwalPelaksanaan(window.jadwalPelaksanaanConfig);
    } else {
        console.error('Jadwal Pelaksanaan config or function not found');
    }
}

