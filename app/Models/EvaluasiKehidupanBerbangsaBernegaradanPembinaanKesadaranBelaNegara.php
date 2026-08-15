<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_kehidupan_berbangsa_bela_negaras';

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
        $defaults = [
            'q1' => 'Pemateri menguasai materi dan menyampaikannya dengan jelas serta mudah dipahami.',
            'q2' => 'Pemateri mampu berinteraksi, memberikan kesempatan berpartisipasi, serta menjawab pertanyaan peserta dengan baik.',
            'q3' => 'Pemateri menggunakan metode penyampaian yang menarik dan mampu mengelola waktu kegiatan dengan baik.',
            'q4' => 'Materi yang disampaikan informatif, bermanfaat, dan sesuai dengan kebutuhan peserta.',
            'q5' => 'Materi yang disampaikan relevan dengan kegiatan PKKMB serta mendukung peningkatan wawasan dan kualitas SDM mahasiswa baru UIS.',
        ];

        return EvaluasiQuestion::getQuestionsForMenu(5, $defaults);
    }
}
