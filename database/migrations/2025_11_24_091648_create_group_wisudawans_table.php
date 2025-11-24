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
        Schema::create('group_wisudawans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('wisuda_id')->constrained('wisudas')->cascadeOnDelete();
            $table->foreignUuid('biodata_id')->constrained('biodatas')->cascadeOnDelete();
            $table->foreignUuid('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignUuid('sesi_id')->constrained('sesi')->cascadeOnDelete();
            $table->unsignedInteger('nomor_urut');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_wisudawans');
    }
};
