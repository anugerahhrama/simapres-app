<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserKeahlian extends Model
{
    use HasFactory;

    protected $table = 'user_keahlians';

    protected $fillable = [
        'user_id',
        'keahlian_id',
        'tingkat'
    ];

    /**
     * Get the user that owns the UserKeahlian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the user that owns the UserKeahlian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function keahlian(): BelongsTo
    {
        return $this->belongsTo(Keahlian::class, 'keahlian_id', 'id');
    }
}
