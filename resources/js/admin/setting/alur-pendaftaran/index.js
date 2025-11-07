// Main Alur Pendaftaran Initialization
// Import modules here when needed
// import { initTable } from './table.js';
// import { initForm } from './form.js';

window.initAlurPendaftaran = function(config) {
    // Initialize components here
    // const table = initTable(config);
    // initForm(config);
    
    console.log('Alur Pendaftaran initialized with config:', config);
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.alurPendaftaranConfig !== 'undefined' && typeof window.initAlurPendaftaran === 'function') {
            window.initAlurPendaftaran(window.alurPendaftaranConfig);
        }
    });
} else {
    // DOM already loaded
    if (typeof window.alurPendaftaranConfig !== 'undefined' && typeof window.initAlurPendaftaran === 'function') {
        window.initAlurPendaftaran(window.alurPendaftaranConfig);
    }
}

