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
        Schema::create('pendaftaran_wisudas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->year('tahun_wisuda'); 
            $table->string('status', 50)->default('Draft'); 
            $table->dateTime('waktu_buka_pendaftaran');
            $table->dateTime('waktu_tutup_pendaftaran');
            $table->timestamps(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_wisudas');
    }
};
