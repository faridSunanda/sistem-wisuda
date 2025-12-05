import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/admin/data-wisudawan.css",
                "resources/css/admin/alur-pendaftaran.css",
                "resources/css/admin/dokumen-persyaratan.css",
                "resources/css/admin/jadwal-pendaftaran.css",
                "resources/css/admin/jadwal-wisuda.css",
                "resources/css/admin/kuota-wisudawan.css",
                "resources/js/app.js",
                "resources/js/admin/data-wisudawan/index.js",
                "resources/js/admin/setting/alur-pendaftaran/index.js",
                "resources/js/admin/setting/dokumen-persyaratan/index.js",
                "resources/js/admin/setting/jadwal-pendaftaran/index.js",
                "resources/js/admin/setting/jadwal-wisuda/index.js",
                "resources/js/admin/setting/kuota-wisudawan/index.js",
                "resources/js/mahasiswa/data-diri/index.js",
                "resources/js/mahasiswa/sertifikat-bahasa-internasional/index.js",
                "resources/js/mahasiswa/sertifikat-kompetensi/index.js",
                "resources/js/mahasiswa/sertifikat-magang/index.js",
                "resources/js/mahasiswa/sertifikat-organisasi/index.js",
                "resources/js/mahasiswa/sertifikat-pendidikan-karakter/index.js",
                "resources/js/mahasiswa/sertifikat-penghargaan/index.js",
                "resources/js/portal/index.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
