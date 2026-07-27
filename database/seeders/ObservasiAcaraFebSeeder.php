<?php

namespace Database\Seeders;

use App\Models\ObservasiAcaraFeb;
use Illuminate\Database\Seeder;

class ObservasiAcaraFebSeeder extends Seeder
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
                'kegiatan' => 'Sambutan Dekan FEB (Dr. Sabri, SE., M.M)',
                'aspek' => [
                    'Kejelasan & antusiasme sambutan.',
                    'Ketepatan durasi.',
                ],
            ],
            [
                'waktu_runddown' => '09.15 - 10.15',
                'waktu_realisasi' => null,
                'kegiatan' => 'Pengenalan Pimpinan, Dosen, Visi & Misi FEB (Dr. Sabri, SE., M.M)',
                'aspek' => [
                    'Kejelasan penyampaian informasi.',
                    'Efektivitas metode perkenalan.',
                    'Perhatian peserta.',
                ],
            ],
            [
                'waktu_runddown' => '10.15 - 11.00',
                'waktu_realisasi' => null,
                'kegiatan' => 'Sosialisasi Kurikulum Prodi Manajemen (Dr. Hendri Herman, SE., M.Si)',
                'aspek' => [
                    'Kejelasan materi (SKS, mata kuliah, aturan).',
                    'Kemampuan menjawab pertanyaan maba.',
                ],
            ],
            [
                'waktu_runddown' => '11.00 - 11.45',
                'waktu_realisasi' => null,
                'kegiatan' => 'Sosialisasi Kurikulum Prodi Akuntansi (Maya Richmawati, SE., M.Ak)',
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
                'kegiatan' => 'Pengenalan HMPS Manajemen & Akuntansi',
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

            ObservasiAcaraFeb::create([
                'waktu_runddown' => $row['waktu_runddown'],
                'waktu_realisasi' => $row['waktu_realisasi'],
                'kegiatan' => $row['kegiatan'],
                'aspek_observasi' => $aspekText,
            ]);
        }
    }
}
