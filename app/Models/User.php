<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nomor_registrasi',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function absenPertama()
    {
        return $this->hasOne(AbsenPertama::class);
    }

    public function absenKedua()
    {
        return $this->hasOne(AbsenKedua::class);
    }

    public function absenKetiga()
    {
        return $this->hasOne(AbsenKetiga::class);
    }

    public function kedisiplinanPertama()
    {
        return $this->hasOne(KedisiplinanPertama::class);
    }

    public function kedisiplinanKedua()
    {
        return $this->hasOne(KedisiplinanKedua::class);
    }

    public function kedisiplinanKetiga()
    {
        return $this->hasOne(KedisiplinanKetiga::class);
    }

    public function hasilTests()
    {
        return $this->hasMany(HasilTest::class);
    }

    public function tugasKelompok()
    {
        return $this->hasOne(SoalTugasKelompok::class);
    }
}
