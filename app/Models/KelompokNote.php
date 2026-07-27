<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokNote extends Model
{
    protected $fillable = [
        'kelompok_id',
        'content',
        'user_id',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
