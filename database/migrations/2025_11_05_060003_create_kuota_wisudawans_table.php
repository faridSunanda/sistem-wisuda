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
        Schema::create('kuota_wisudawans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pendaftaran_wisuda_id');
            $table->unsignedInteger('jumlah_kuota'); 
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
        Schema::dropIfExists('kuota_wisudawans');
    }
};
