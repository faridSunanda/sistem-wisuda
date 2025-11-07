// Main Jadwal Pendaftaran Initialization
// Import modules here when needed
// import { initTable } from './table.js';
// import { initForm } from './form.js';

window.initJadwalPendaftaran = function(config) {
    // Initialize components here
    // const table = initTable(config);
    // initForm(config);
    
    console.log('Jadwal Pendaftaran initialized with config:', config);
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.jadwalPendaftaranConfig !== 'undefined' && typeof window.initJadwalPendaftaran === 'function') {
            window.initJadwalPendaftaran(window.jadwalPendaftaranConfig);
        }
    });
} else {
    // DOM already loaded
    if (typeof window.jadwalPendaftaranConfig !== 'undefined' && typeof window.initJadwalPendaftaran === 'function') {
        window.initJadwalPendaftaran(window.jadwalPendaftaranConfig);
    }
}

