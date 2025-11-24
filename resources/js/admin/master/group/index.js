import { initDataTable } from './table.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

window.initGroup = function(config) {
    const table = initDataTable(config);
    window.groupTable = table;

    initExports(config);

    initActions(config);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.groupConfig !== 'undefined' && typeof window.initGroup === 'function') {
            window.initGroup(window.groupConfig);
        }
    });
} else {
    if (typeof window.groupConfig !== 'undefined' && typeof window.initGroup === 'function') {
        window.initGroup(window.groupConfig);
    }
}
