import { initDataTable } from './table.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

window.initWisuda = function(config) {
    try {
        const table = initDataTable(config);
        window.wisudaTable = table;

        initExports(config);

        initActions(config);

    } catch (error) {
        console.error('Error initializing Wisuda:', error);
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded');
        if (typeof window.wisudaConfig !== 'undefined' && typeof window.initWisuda === 'function') {
            window.initWisuda(window.wisudaConfig);
        } else {
            console.error('Wisuda config or function not found');
        }
    });
} else {
    if (typeof window.wisudaConfig !== 'undefined' && typeof window.initWisuda === 'function') {
        window.initWisuda(window.wisudaConfig);
    } else {
        console.error('Wisuda config or function not found');
    }
}

