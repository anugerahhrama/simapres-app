<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpkBobot extends Model
{
    use HasFactory;

    protected $table = 'spk_bobots';

    protected $fillable = [
        'user_id',
        'c1',
        'c2',
        'c3',
        'c4',
        'c5'
    ];


    /**
     * Get the user that owns the SpkBobot
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
