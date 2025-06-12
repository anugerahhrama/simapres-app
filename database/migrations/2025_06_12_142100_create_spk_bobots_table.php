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
        Schema::create('spk_bobots', function (Blueprint $table) {
            $table->id();
            $table->float('c1')->nullable()->default(0);
            $table->float('c2')->nullable()->default(0);
            $table->float('c3')->nullable()->default(0);
            $table->float('c4')->nullable()->default(0);
            $table->float('c5')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_bobots');
    }
};
