<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiPrestasi extends Model
{
    use HasFactory;

    protected $table = 'bukti_prestasis';

    protected $fillable = [
        'prestasi_id',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'tanggal_upload',
    ];

    /**
     * Get the prestasi that owns the BuktiPrestasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(Prestasi::class, 'prestasi_id', 'id');
    }
}
