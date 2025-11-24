import { initDataTable } from './table.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

window.initSesi = function(config) {
    const table = initDataTable(config);
    window.sesiTable = table;

    initExports(config);

    initActions(config);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.sesiConfig !== 'undefined' && typeof window.initSesi === 'function') {
            window.initSesi(window.sesiConfig);
        }
    });
} else {
    if (typeof window.sesiConfig !== 'undefined' && typeof window.initSesi === 'function') {
        window.initSesi(window.sesiConfig);
    }
}
