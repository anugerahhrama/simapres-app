<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keahlian extends Model
{
    use HasFactory;

    protected $table = 'keahlians';

    protected $fillable = [
        'nama_keahlian',
    ];

    /**
     * Get all of the keahlian for the Keahlian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function keahlian(): HasMany
    {
        return $this->hasMany(UserKeahlian::class, 'keahlian_id');
    }

    /**
     * Get all of the lomba for the Keahlian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lomba(): HasMany
    {
        return $this->hasMany(Lomba::class, 'bidang_keahlian_id');
    }
    
}
