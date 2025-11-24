import { initDataTable } from './table.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

window.initAlurPendaftaran = function(config) {
    const table = initDataTable(config);
    window.alurPendaftaranTable = table;

    initExports(config);

    initActions(config);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.alurPendaftaranConfig !== 'undefined' && typeof window.initAlurPendaftaran === 'function') {
            window.initAlurPendaftaran(window.alurPendaftaranConfig);
        }
    });
} else {
    if (typeof window.alurPendaftaranConfig !== 'undefined' && typeof window.initAlurPendaftaran === 'function') {
        window.initAlurPendaftaran(window.alurPendaftaranConfig);
    }
}
