<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranLombas extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_lombas';

    protected $fillable = [
        'user_id',
        'lomba_id',
        'tanggal_daftar',
        'status',
    ];

    /**
     * Get the user that owns the PendaftaranLombas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the lomba that owns the PendaftaranLombas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lomba(): BelongsTo
    {
        return $this->belongsTo(Lomba::class, 'lomba_id', 'id');
    }
}
