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
        Schema::create('sertifikat_organisasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('biodata_id')->constrained('biodatas')->cascadeOnDelete();
            $table->string('nama_sertifikat');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_organisasis');
    }
};
