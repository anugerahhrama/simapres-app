<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTingkatLomba extends Model
{
    use HasFactory;

    protected $table = 'user_tingkat_lombas';

    protected $fillable = [
        'user_id',
        'pilihan_utama_id',
        'pilihan_kedua_id',
        'pilihan_ketiga_id',
    ];

    /**
     * Get the user that owns the UserTingkatLomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tingkatSatu(): BelongsTo
    {
        return $this->belongsTo(TingkatanLomba::class, 'pilihan_utama_id', 'id');
    }

    public function tingkatDua(): BelongsTo
    {
        return $this->belongsTo(TingkatanLomba::class, 'pilihan_kedua_id', 'id');
    }

    public function tingkatTiga(): BelongsTo
    {
        return $this->belongsTo(TingkatanLomba::class, 'pilihan_ketiga_id', 'id');
    }
}
