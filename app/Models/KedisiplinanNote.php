<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KedisiplinanNote extends Model
{
    protected $fillable = [
        'category',
        'content',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
