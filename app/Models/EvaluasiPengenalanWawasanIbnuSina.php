<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiPengenalanWawasanIbnuSina extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'q1',
        'q2',
        'q3',
        'q4',
        'q5',
        'q6',
        'q7',
        'q8',
        'q9',
        'q10',
        'q11',
        'q12',
        'q13',
        'saran_dan_masukan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function questions(): array
    {
        return [
            'q1'  => 'Pemateri Memahami materi',
            'q2'  => 'Pamateri Berinteraksi dengan peserta',
            'q3'  => 'Pemaparan jelas dan mudah dipahami',
            'q4'  => 'Pamateri Mampu mengalokasikan waktu',
            'q5'  => 'Pamateri Memberikan motivasi dan feedback',
            'q6'  => 'Metode dan alat dalam penyajian menarik',
            'q7'  => 'Pamateri Memberikan kesempatan berpartisipasi',
            'q8'  => 'Pamateri Menjawab pertanyaan dengan benar dan jelas',
            'q9'  => 'Materi yang di sajikan Informatif',
            'q10' => 'Materi yang di sajikan Mudah dipahami',
            'q11' => 'Materi yang di sajikan Bermanfaat dan sesuai kebutuhan',
            'q12' => 'Materi yang di sajikan Relevan dengan Kegiatan PKKMB',
            'q13' => 'Materi yang di sajikan Mendukung peningkatan SDM MABA UIS',
        ];
    }
}
