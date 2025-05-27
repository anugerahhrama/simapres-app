<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPeriode extends Model
{
    use HasFactory;

    protected $table = 'riwayat_periodes';

    protected $fillable = [
        'user_id',
        'periode_id',
        'status'
    ];

    /**
     * Get the user that owns the RiwayatPeriode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the user that owns the RiwayatPeriode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id', 'id');
    }
}
