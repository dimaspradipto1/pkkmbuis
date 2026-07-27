<?php

namespace Database\Seeders;

use App\Models\ObservasiAcaraFst;
use Illuminate\Database\Seeder;

class ObservasiAcaraFstSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'waktu_runddown' => '08.30 - 08.40',
                'waktu_realisasi' => null,
                'kegiatan' => 'Pembukaan',
                'aspek' => [
                    'Ketepatan waktu & kesiapan MC.',
                    'Kemampuan membangun suasana.',
                ],
            ],
            [
                'waktu_runddown' => '08.40 - 08.50',
                'waktu_realisasi' => null,
                'kegiatan' => 'Menyanyikan Lagu Indonesia Raya & Mars UIS',
                'aspek' => [
                    'Kekhidmatan & partisipasi peserta.',
                    'Kualitas audio.',
                ],
            ],
            [
                'waktu_runddown' => '08.50 - 09.00',
                'waktu_realisasi' => null,
                'kegiatan' => 'Pembacaan Doa',
                'aspek' => [
                    'Kekhusyukan suasana.',
                ],
            ],
            [
                'waktu_runddown' => '09.00 - 09.15',
                'waktu_realisasi' => null,
                'kegiatan' => 'Sambutan Dekan FST (Ir. Sanusi, S.T., M.Eng., Ph.D., IPM)',
                'aspek' => [
                    'Kejelasan & antusiasme sambutan.',
                    'Ketepatan durasi.',
                ],
            ],
            [
                'waktu_runddown' => '09.15 - 10.15',
                'waktu_realisasi' => null,
                'kegiatan' => 'Pengenalan Pimpinan, Dosen, Visi & Misi FST (Ir. Sanusi, S.T., M.Eng., Ph.D., IPM)',
                'aspek' => [
                    'Kejelasan penyampaian informasi.',
                    'Efektivitas metode perkenalan.',
                    'Perhatian peserta.',
                ],
            ],
            [
                'waktu_runddown' => '10.15 - 11.00',
                'waktu_realisasi' => null,
                'kegiatan' => 'Sosialisasi Kurikulum Prodi Teknik Informatika (Dr. M. Ropianto, M.Kom)',
                'aspek' => [
                    'Kejelasan materi (SKS, mata kuliah, aturan).',
                    'Kemampuan menjawab pertanyaan maba.',
                ],
            ],
            [
                'waktu_runddown' => '11.00 - 11.45',
                'waktu_realisasi' => null,
                'kegiatan' => 'Sosialisasi Kurikulum Prodi Teknik Industri (Ir. Herman, S.T., M.T)',
                'aspek' => [
                    'Kejelasan materi (SKS, mata kuliah, aturan).',
                    'Kemampuan menjawab pertanyaan maba.',
                ],
            ],
            [
                'waktu_runddown' => '11.45 - 12.00',
                'waktu_realisasi' => null,
                'kegiatan' => 'Ice Breaking',
                'aspek' => [
                    'Partisipasi dan antusiasme peserta.',
                    'Kreativitas & efektivitas panitia.',
                ],
            ],
            [
                'waktu_runddown' => '12.00 - 13.00',
                'waktu_realisasi' => null,
                'kegiatan' => 'ISHOMA',
                'aspek' => [
                    'Ketertiban peserta saat istirahat.',
                    'Kebersihan area.',
                ],
            ],
            [
                'waktu_runddown' => '13.00 - 14.00',
                'waktu_realisasi' => null,
                'kegiatan' => 'Pengenalan HMPS Teknik Informatika & Teknik Industri',
                'aspek' => [
                    'Kualitas presentasi HMPS.',
                    'Kemampuan menarik minat maba.',
                ],
            ],
        ];

        foreach ($rows as $row) {
            $aspekText = implode("\n", array_map(
                fn ($index, $item) => ($index + 1) . '. ' . $item,
                array_keys($row['aspek']),
                $row['aspek']
            ));

            ObservasiAcaraFst::create([
                'waktu_runddown' => $row['waktu_runddown'],
                'waktu_realisasi' => $row['waktu_realisasi'],
                'kegiatan' => $row['kegiatan'],
                'aspek_observasi' => $aspekText,
            ]);
        }
    }
}
