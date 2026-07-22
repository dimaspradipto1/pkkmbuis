<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $fillable = [
        'link_buku_saku',
        'link_daftar_kelompok',
        'link_rundown',
    ];
}
