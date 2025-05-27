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
        Schema::create('user_tingkat_lombas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pilihan_utama_id')->nullable()->constrained('tingkatan_lombas');
            $table->foreignId('pilihan_kedua_id')->nullable()->constrained('tingkatan_lombas');
            $table->foreignId('pilihan_ketiga_id')->nullable()->constrained('tingkatan_lombas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tingkat_lombas');
    }
};
