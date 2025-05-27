<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studis';

    protected $fillable = [
        'name',
        'jurusan',
    ];

    /**
     * Get the user associated with the ProgramStudi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detailUser(): HasMany
    {
        return $this->hasMany(DetailUser::class, 'prodi_id');
    }
}
