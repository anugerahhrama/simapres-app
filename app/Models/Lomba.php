<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lomba extends Model
{
    use HasFactory;

    protected $table = 'lombas';

    protected $fillable = [
        'judul',
        'kategori',
        'deskripsi',
        'penyelenggara',
        'link_registrasi',
        'awal_registrasi',
        'akhir_registrasi',
        'tingkatan_lomba_id',
        'minat_id',
        'created_by',
        'status_verifikasi',
        'jenis_pendaftaran',
        'harga_pendaftaran',
        'perkiraan_hadiah',
        'mendapatkan_uang',
        'mendapatkan_sertifikat',
        'nilai_benefit',
    ];

    /**
     * Get the user that owns the Lomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tingkatanLomba(): BelongsTo
    {
        return $this->belongsTo(TingkatanLomba::class, 'tingkatan_lomba_id', 'id');
    }

    /**
     * Get all of the keahlian for the Lomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function keahlian(): BelongsToMany
    {
        return $this->belongsToMany(Keahlian::class, 'lomba_keahlians', 'lomba_id', 'keahlian_id');
    }

    /**
     * Get the user that owns the Lomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function minat(): BelongsTo
    {
        return $this->belongsTo(Minat::class, 'minat_id', 'id');
    }

    /**
     * Get all of the pendaftaranLomba for the Lomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendaftaranLomba(): HasMany
    {
        return $this->hasMany(PendaftaranLombas::class, 'lomba_id');
    }

    /**
     * Get all of the rekomendasiLomba for the Lomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rekomendasiLomba(): HasMany
    {
        return $this->hasMany(RekomendasiLomba::class, 'lomba_id');
    }

    /**
     * Get all of the pendamping for the Lomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendamping(): HasMany
    {
        return $this->hasMany(Pendamping::class, 'lomba_id');
    }

    /**
     * Get the user who created the Lomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
