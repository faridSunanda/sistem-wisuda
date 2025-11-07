import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin/data-wisudawan.css',
                'resources/css/admin/alur-pendaftaran.css',
                'resources/css/admin/dokumen-persyaratan.css',
                'resources/css/admin/jadwal-pendaftaran.css',
                'resources/css/admin/jadwal-wisuda.css',
                'resources/css/admin/kuota-wisudawan.css',
                'resources/js/app.js',
                'resources/js/admin/data-wisudawan/index.js',
                'resources/js/admin/setting/alur-pendaftaran/index.js',
                'resources/js/admin/setting/dokumen-persyaratan/index.js',
                'resources/js/admin/setting/jadwal-pendaftaran/index.js',
                'resources/js/admin/setting/jadwal-wisuda/index.js',
                'resources/js/admin/setting/kuota-wisudawan/index.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
