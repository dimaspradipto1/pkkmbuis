<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor',
        'nama',
        'route_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
