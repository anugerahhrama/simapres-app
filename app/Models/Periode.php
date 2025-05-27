<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periodes';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    /**
     * Get all of the comments for the Periode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user(): HasMany
    {
        return $this->hasMany(RiwayatPeriode::class, 'periode_id');
    }
}
