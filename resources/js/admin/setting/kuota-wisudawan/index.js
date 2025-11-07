// Main Kuota Wisudawan Initialization
// Import modules here when needed
// import { initTable } from './table.js';
// import { initForm } from './form.js';

window.initKuotaWisudawan = function(config) {
    // Initialize components here
    // const table = initTable(config);
    // initForm(config);
    
    console.log('Kuota Wisudawan initialized with config:', config);
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.kuotaWisudawanConfig !== 'undefined' && typeof window.initKuotaWisudawan === 'function') {
            window.initKuotaWisudawan(window.kuotaWisudawanConfig);
        }
    });
} else {
    // DOM already loaded
    if (typeof window.kuotaWisudawanConfig !== 'undefined' && typeof window.initKuotaWisudawan === 'function') {
        window.initKuotaWisudawan(window.kuotaWisudawanConfig);
    }
}

