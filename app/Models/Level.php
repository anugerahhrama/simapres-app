<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Level extends Model
{
    use HasFactory;

    protected $table = 'levels';

    protected $fillable = [
        'level_code',
        'nama_level'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'level_id');
    }
}
