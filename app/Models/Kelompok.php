<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelompok',
        'slug',
        'pendamping_id',
        'keterangan',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($kelompok) {
            if (empty($kelompok->slug)) {
                $kelompok->slug = static::generateUniqueSlug($kelompok->nama_kelompok);
            }
        });

        static::updating(function ($kelompok) {
            if ($kelompok->isDirty('nama_kelompok')) {
                $kelompok->slug = static::generateUniqueSlug($kelompok->nama_kelompok, $kelompok->id);
            }
        });
    }

    public static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function pendamping()
    {
        return $this->belongsTo(User::class, 'pendamping_id');
    }

    public function anggota()
    {
        return $this->hasMany(User::class, 'kelompok_id');
    }
}
