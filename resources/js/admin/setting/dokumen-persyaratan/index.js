// Main Dokumen Persyaratan Initialization
// Import modules here when needed
// import { initTable } from './table.js';
// import { initForm } from './form.js';

window.initDokumenPersyaratan = function(config) {
    // Initialize components here
    // const table = initTable(config);
    // initForm(config);
    
    console.log('Dokumen Persyaratan initialized with config:', config);
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.dokumenPersyaratanConfig !== 'undefined' && typeof window.initDokumenPersyaratan === 'function') {
            window.initDokumenPersyaratan(window.dokumenPersyaratanConfig);
        }
    });
} else {
    // DOM already loaded
    if (typeof window.dokumenPersyaratanConfig !== 'undefined' && typeof window.initDokumenPersyaratan === 'function') {
        window.initDokumenPersyaratan(window.dokumenPersyaratanConfig);
    }
}

