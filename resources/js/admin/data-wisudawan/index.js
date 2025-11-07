// Main Data Wisudawan Initialization
import { initDataTable } from './table.js';
import { initFilters } from './filter.js';
import { initExports } from './export.js';
import { initActions } from './actions.js';

window.initDataWisudawan = function(config) {
    // Initialize DataTable
    const table = initDataTable(config);
    window.dataWisudawanTable = table;
    
    // Initialize Filters
    initFilters(config, table);
    
    // Initialize Exports
    initExports(config);
    
    // Initialize Actions
    initActions(config);
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.dataWisudawanConfig !== 'undefined' && typeof window.initDataWisudawan === 'function') {
            window.initDataWisudawan(window.dataWisudawanConfig);
        }
    });
} else {
    // DOM already loaded
    if (typeof window.dataWisudawanConfig !== 'undefined' && typeof window.initDataWisudawan === 'function') {
        window.initDataWisudawan(window.dataWisudawanConfig);
    }
}

