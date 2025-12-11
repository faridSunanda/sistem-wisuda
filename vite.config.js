import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/portal/index.css",
                "resources/css/admin/data-wisudawan.css",
                "resources/css/admin/alur-pendaftaran.css",
                "resources/css/admin/dokumen-persyaratan.css",
                "resources/css/admin/jadwal-pelaksanaan.css",
                "resources/css/admin/wisuda.css",
                "resources/css/admin/group-wisudawan.css",
                "resources/css/akademik/data-wisudawan.css",
                "resources/css/admin/group.css",
                "resources/css/admin/sesi.css",
                "resources/js/app.js",
                "resources/js/admin/data-wisudawan/index.js",
                "resources/js/admin/master/alur-pendaftaran/index.js",
                "resources/js/admin/master/dokumen-persyaratan/index.js",
                "resources/js/admin/master/group/index.js",
                "resources/js/admin/master/sesi/index.js",
                "resources/js/admin/wisuda/jadwal-pelaksanaan/index.js",
                "resources/js/admin/wisuda/wisuda/index.js",
                "resources/js/mahasiswa/data-diri/index.js",
                "resources/js/mahasiswa/components/sidebar.js",
                "resources/js/mahasiswa/components/header.js",
                "resources/js/mahasiswa/components/footer.js",
                "resources/js/mahasiswa/sertifikat/sertifikat-bahasa-internasional/index.js",
                "resources/js/mahasiswa/sertifikat/sertifikat-kompetensi/index.js",
                "resources/js/mahasiswa/sertifikat/sertifikat-magang/index.js",
                "resources/js/mahasiswa/sertifikat/sertifikat-organisasi/index.js",
                "resources/js/mahasiswa/sertifikat/sertifikat-pendidikan-karakter/index.js",
                "resources/js/mahasiswa/sertifikat/sertifikat-penghargaan/index.js",
                "resources/js/portal/index.js",
                "resources/js/akademik/data-wisudawan/index.js",
                "resources/js/keuangan/data-wisudawan/index.js",
                "resources/js/keuangan/data-wisudawan/detail.js",
                "resources/css/keuangan/data-wisudawan.css",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
