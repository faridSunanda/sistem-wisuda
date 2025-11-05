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
        Schema::create('pelaksanaan_wisudas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pendaftaran_wisuda_id');
            $table->string('nama_kegiatan', 255); 
            $table->dateTime('waktu_pelaksanaan');
            $table->string('tempat_pelaksanaan', 255);
            $table->text('keterangan'); 
            $table->timestamps(); 
            $table->softDeletes();
            $table->foreign('pendaftaran_wisuda_id')->references('id')->on('pendaftaran_wisudas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaksanaan_wisudas');
    }
};
