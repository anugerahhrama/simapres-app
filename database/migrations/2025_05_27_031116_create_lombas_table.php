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
        Schema::create('lombas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori');
            $table->text('deskripsi')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->string('link_registrasi')->nullable();
            $table->date('awal_registrasi')->nullable();
            $table->date('akhir_registrasi')->nullable();
            $table->foreignId('tingkatan_lomba_id')->nullable()->constrained('tingkatan_lombas')->onDelete('set null');
            $table->foreignId('bidang_keahlian_id')->nullable()->constrained('keahlians')->onDelete('set null');
            $table->foreignId('minat_id')->nullable()->constrained('minats')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users');
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->enum('jenis_pendaftaran', ['gratis', 'berbayar'])->default('gratis');
            $table->unsignedInteger('harga_pendaftaran')->nullable();
            $table->unsignedBigInteger('perkiraan_hadiah')->nullable();
            $table->boolean('mendapatkan_uang')->default(false);
            $table->boolean('mendapatkan_sertifikat')->default(false);
            $table->unsignedTinyInteger('nilai_benefit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lombas');
    }
};
