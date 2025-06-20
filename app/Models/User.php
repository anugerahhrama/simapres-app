<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'level_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function periode(): HasMany
    {
        return $this->hasMany(RiwayatPeriode::class, 'user_id');
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function minat(): HasMany
    {
        return $this->hasMany(UserMinat::class, 'user_id');
    }

    /**
     * Get all of the keahlian for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function keahlian(): HasMany
    {
        return $this->hasMany(UserKeahlian::class, 'user_id');
    }

    public function keahlian2()
    {
        return $this->belongsToMany(Keahlian::class, 'user_keahlians', 'user_id', 'keahlian_id');
    }

    /**
     * Get all of the pendaftaranLomba for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendaftaranLomba(): HasMany
    {
        return $this->hasMany(PendaftaranLombas::class, 'user_id');
    }

    /**
     * Get all of the rekomendasiLomba for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rekomendasiLomba(): HasMany
    {
        return $this->hasMany(RekomendasiLomba::class, 'user_id');
    }

    /**
     * Get all of the dosen for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dosen(): HasMany
    {
        return $this->hasMany(Bimbingan::class, 'dosen_id');
    }

    /**
     * Get all of the mahasiswa for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Bimbingan::class, 'mahasiswa_id');
    }

    /**
     * Get all of the prestasi for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prestasi(): HasMany
    {
        return $this->hasMany(Prestasi::class, 'mahasiswa_id');
    }

    /**
     * Get all of the pendamping for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendamping(): HasMany
    {
        return $this->hasMany(Pendamping::class, 'dosen_id');
    }

    /**
     * Get the detail user associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detailUser(): HasOne
    {
        return $this->hasOne(DetailUser::class, 'user_id', 'id');
    }

    /**
     * Get all of the tingkatanLomba for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function preferensiTingkatLomba(): HasOne
    {
        return $this->hasOne(UserTingkatLomba::class, 'user_id');
    }

    /**
     * Get all of the spk for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spk(): HasMany
    {
        return $this->hasMany(SpkBobot::class, 'user_id');
    }

    public function jenis()
    {
        return $this->hasOne(Jenis::class);
    }

    public function biaya()
    {
        return $this->hasOne(Biaya::class);
    }

    public function hadiah()
    {
        return $this->hasOne(Hadiah::class);
    }
}
