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
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('biodata_id')->constrained('biodatas')->cascadeOnDelete();
            $table->string('jenis');
            $table->string('nama_sertifikat');
            $table->string('penerbit');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->string('berkas');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
