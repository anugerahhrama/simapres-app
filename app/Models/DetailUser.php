<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailUser extends Model
{
    use HasFactory;

    protected $table = 'detail_users';

    protected $fillable = [
        'user_id',
        'name',
        'no_induk',
        'phone',
        'jenis_kelamin',
        'prodi_id',
        'photo_file',
    ];

    public function detailUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id', 'id');
    }
}
