// Main Jadwal Wisuda Initialization
// Import modules here when needed
// import { initTable } from './table.js';
// import { initForm } from './form.js';

window.initJadwalWisuda = function(config) {
    // Initialize components here
    // const table = initTable(config);
    // initForm(config);
    
    console.log('Jadwal Wisuda initialized with config:', config);
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.jadwalWisudaConfig !== 'undefined' && typeof window.initJadwalWisuda === 'function') {
            window.initJadwalWisuda(window.jadwalWisudaConfig);
        }
    });
} else {
    // DOM already loaded
    if (typeof window.jadwalWisudaConfig !== 'undefined' && typeof window.initJadwalWisuda === 'function') {
        window.initJadwalWisuda(window.jadwalWisudaConfig);
    }
}

