<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bimbingan extends Model
{
    use HasFactory;

    protected $table = 'bimbingans';

    protected $fillable = [
        'dosen_id', 'mahasiswa_id', 'tanggal_mulai', 'tanggal_selesai', 'catatan_bimbingan', 'status'
    ];

    /**
     * Get the dosen that owns the Bimbingan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }

    /**
     * Get the mahasiswa that owns the Bimbingan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }
}
