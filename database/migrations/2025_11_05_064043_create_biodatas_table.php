<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('biodatas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nik')-nullable();
            $table->string('nim')->nullable();
            $table->string('nirm')->nullable();
            $table->string('nirl')->nullable();
            $table->string('foto_profile')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('status_mahasiswa')->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('alamat_rumah')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('judul_skripsi')->nullable();
            $table->text('kesan_pesan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodatas');
    }
};
