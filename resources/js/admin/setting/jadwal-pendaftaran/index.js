import { initDataTable } from './table.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

window.initJadwalPendaftaran = function(config) {

    try {
        const table = initDataTable(config);
        window.jadwalPendaftaranTable = table;

        initExports(config);

        initActions(config);

    } catch (error) {
        console.error('Error initializing Jadwal Pendaftaran:', error);
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded');
        if (typeof window.jadwalPendaftaranConfig !== 'undefined' && typeof window.initJadwalPendaftaran === 'function') {
            window.initJadwalPendaftaran(window.jadwalPendaftaranConfig);
        } else {
            console.error('Jadwal Pendaftaran config or function not found');
        }
    });
} else {
    if (typeof window.jadwalPendaftaranConfig !== 'undefined' && typeof window.initJadwalPendaftaran === 'function') {
        window.initJadwalPendaftaran(window.jadwalPendaftaranConfig);
    } else {
        console.error('Jadwal Pendaftaran config or function not found');
    }
}
