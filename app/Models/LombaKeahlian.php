<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LombaKeahlian extends Model
{
    use HasFactory;

    protected $table = 'lomba_keahlians';

    protected $fillable = [
        'lomba_id',
        'keahlian_id',
    ];

    /**
     * Get the lomba that owns the LombaKeahlian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lomba(): BelongsTo
    {
        return $this->belongsTo(Lomba::class, 'lomba_id', 'id');
    }

    /**
     * Get the user that owns the LombaKeahlian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function keahlian(): BelongsTo
    {
        return $this->belongsTo(Keahlian::class, 'keahlian_id', 'id');
    }
}
