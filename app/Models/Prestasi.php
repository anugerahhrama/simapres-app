<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasis';

    protected $fillable = [
        'mahasiswa_id',
        'nama_kegiatan',
        'deskripsi',
        'tanggal',
        'kategori',
        'pencapaian',
        'evaluasi_diri',
        'status_verifikasi',
    ];

    /**
     * Get the user that owns the Prestasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }

    /**
     * Get all of the bukti for the Prestasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bukti(): HasMany
    {
        return $this->hasMany(BuktiPrestasi::class, 'prestasi_id');
    }
}
