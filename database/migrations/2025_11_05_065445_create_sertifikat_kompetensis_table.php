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
        Schema::create('sertifikat_kompetensis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('biodata_id')->constrained('biodatas')->cascadeOnDelete();
            $table->string('nama_sertifikat');
            $table->string('penerbit');
            $table->date('tanggal_terbit');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_kompetensis');
    }
};
