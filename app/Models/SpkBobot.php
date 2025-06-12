<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkBobot extends Model
{
    use HasFactory;

    protected $table = 'spk_bobots';

    protected $fillable = [
        'c1',
        'c2',
        'c3',
        'c4',
        'c5'
    ];
}
