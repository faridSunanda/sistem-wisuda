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
        Schema::create('dokumen_persyaratans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tipe_dokumen', 100);
            $table->string('nama_dokumen', 255);
            $table->text('keterangan');
            $table->string('berkas', 255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_persyaratans');
    }
};
