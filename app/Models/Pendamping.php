<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendamping extends Model
{
    use HasFactory;

    protected $fillable = [
        'lomba_id',
        'dosen_id'
    ];

    /**
     * Get the user that owns the Pendamping
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }

    /**
     * Get the lomba that owns the Pendamping
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lomba(): BelongsTo
    {
        return $this->belongsTo(Lomba::class, 'lomba_id', 'id');
    }
}
