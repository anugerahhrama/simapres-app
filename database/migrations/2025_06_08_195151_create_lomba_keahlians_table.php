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
        Schema::create('lomba_keahlians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lomba_id')->constrained('lombas')->onDelete('cascade');
            $table->foreignId('keahlian_id')->constrained('keahlians')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lomba_keahlians');
    }
};
