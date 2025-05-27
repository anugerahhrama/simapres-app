<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Minat extends Model
{
    use HasFactory;

    protected $table = 'minats';

    protected $fillable = [
        'nama_minat',
    ];

    /**
     * Get all of the comments for the Minat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user(): HasMany
    {
        return $this->hasMany(UserMinat::class, 'minat_id');
    }

    /**
     * Get all of the lomba for the Minat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lomba(): HasMany
    {
        return $this->hasMany(Lomba::class, 'minat_id');
    }
}
