<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekomendasiLomba extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi_lombas';

    protected $fillable = [
        'user_id',
        'lomba_id',
        'catatan',
        'tanggal_rekomendasi',
    ];

    /**
     * Get the user that owns the RekomendasiLomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the lomba that owns the RekomendasiLomba
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lomba(): BelongsTo
    {
        return $this->belongsTo(Lomba::class, 'lomba_id', 'id');
    }
}
