import { initDataTable } from './table.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

window.initDokumenPersyaratan = function(config) {
    const table = initDataTable(config);
    window.dokumenPersyaratanTable = table;

    initExports(config);

    initActions(config);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.dokumenPersyaratanConfig !== 'undefined' && typeof window.initDokumenPersyaratan === 'function') {
            window.initDokumenPersyaratan(window.dokumenPersyaratanConfig);
        }
    });
} else {
    if (typeof window.dokumenPersyaratanConfig !== 'undefined' && typeof window.initDokumenPersyaratan === 'function') {
        window.initDokumenPersyaratan(window.dokumenPersyaratanConfig);
    }
}
