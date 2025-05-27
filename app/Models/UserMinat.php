<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMinat extends Model
{
    use HasFactory;

    protected $table = 'user_minats';

    protected $fillable = [
        'user_id',
        'minat_id',
    ];

    /**
     * Get the user that owns the UserMinat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the user that owns the UserMinat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function minat(): BelongsTo
    {
        return $this->belongsTo(Minat::class, 'minat_id', 'id');
    }
}
