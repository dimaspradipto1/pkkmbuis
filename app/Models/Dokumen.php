<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $fillable = [
        'link_buku_saku',
        'link_daftar_kelompok',
        'link_rundown',
        'link_tata_tertib_kehidupan_mahasiswa',
        'link_video_tutorial_penggunaan_sistem_PKKMB',
        'link_wa_group',
        'no_wa_admin',
    ];
}
