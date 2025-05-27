<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TingkatanLomba extends Model
{
    use HasFactory;

    protected $table = 'tingkatan_lombas';

    protected $fillable = [
        'nama',
        'skor_default',
    ];

    /**
     * Get all of the comments for the TingkatanLomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tingkatLombaSatu(): HasMany
    {
        return $this->hasMany(UserTingkatLomba::class, 'pilihan_utama_id');
    }

    public function tingkatLombaDua(): HasMany
    {
        return $this->hasMany(UserTingkatLomba::class, 'pilihan_kedua_id');
    }

    public function tingkatLombaTiga(): HasMany
    {
        return $this->hasMany(UserTingkatLomba::class, 'pilihan_ketiga_id');
    }

    /**
     * Get all of the lomba for the TingkatanLomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lomba(): HasMany
    {
        return $this->hasMany(Lomba::class, 'tingkatan_lomba_id');
    }
}
