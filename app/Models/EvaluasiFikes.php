<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiFikes extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_fikes';

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
        'saran_dekan',
        'saran_wakil_dekan_1',
        'saran_wakil_dekan_2',
        'saran_prodi_s1_kesling',
        'saran_prodi_s1_k3',
        'saran_prodi_s2_kesmas',
        'saran_hiv',
        'saran_alumni_fikes',
        'q14',
        'q15',
        'q16',
        'q17',
        'q18',
        'q19',
        'q20',
        'q21',
        'q22',
        'saran_panitia',
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
            'q14' => 'Efektifitas waktu dan Jadwal pelaksanaan (tanggal dan durasi)',
            'q15' => 'Pelayanan panitia dengan peserta',
            'q16' => 'Kejelasan informasi yang diberikan panitia',
            'q17' => 'Kedisiplinan Kegiatan PKKMB',
            'q18' => 'Keramahan Panitia dalam memberikan dan merespon pertanyaan',
            'q19' => 'Ketersediaan dan kesiapan sarana prasarana kegiatan pendukung kegiatan kuliah umum',
            'q20' => 'Kondisi Lokasi tempat PKKM (Kondusif dan Nyaman)',
            'q21' => 'Kualitas Sarana prasarana pendukung (Toilet, Taman, Tempat duduk, dan Mushola dll)',
            'q22' => 'Kualitas Sound/suara dan tampilan layar infocus',
        ];
    }

    public static function saranFields(): array
    {
        return [
            'saran_dekan'           => 'Pemateri Dekan',
            'saran_wakil_dekan_1'   => 'Pemateri Wakil Dekan I',
            'saran_wakil_dekan_2'   => 'Pemateri Wakil Dekan II',
            'saran_prodi_s1_kesling'=> 'Pemateri Program Studi S1 Kesehatan Lingkungan',
            'saran_prodi_s1_k3'     => 'Pemateri Program Studi S1 Kesehatan dan Keselamatan Kerja',
            'saran_prodi_s2_kesmas' => 'Pemateri Program Studi S2 Kesehatan Masyarakat',
            'saran_hiv'             => 'Pemateri HIV',
            'saran_alumni_fikes'    => 'Pemateri ALUMNI FIKes',
        ];
    }
}
