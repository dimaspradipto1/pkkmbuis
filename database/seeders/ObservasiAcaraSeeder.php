<?php

namespace Database\Seeders;

use App\Models\ObservasiAcara;
use Illuminate\Database\Seeder;

class ObservasiAcaraSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'waktu_runddown' => '17.00 - 18.00',
                'waktu_realisasi' => '16.00 - 17.00',
                'kegiatan' => 'Registrasi Peserta',
                'aspek' => [
                    'Kelancaran alur registrasi & absensi.',
                    'Kesigapan panitia (Sekretariat & Pendamping).',
                ],
            ],
            [
                'waktu_runddown' => '17.30 - 18.00',
                'waktu_realisasi' => '17.23 - 17.45',
                'kegiatan' => 'Maba Masuk Gate (Gunting Pita)',
                'aspek' => [
                    'Ketepatan waktu dan kemeriahan (pita, balon, dll).',
                    'Koordinasi Rektor, Ketua Panitia, dan panitia penyambut.',
                ],
            ],
            [
                'waktu_runddown' => '18.00 - 18.30',
                'waktu_realisasi' => '18.00 - 18.30',
                'kegiatan' => 'Istirahat Sholat',
                'aspek' => [
                    'Ketersediaan & kebersihan tempat sholat.',
                    'Ketepatan waktu kembali ke acara.',
                ],
            ],
            [
                'waktu_runddown' => '18.30 - 19.00',
                'waktu_realisasi' => '18.30 - 19.00',
                'kegiatan' => 'Persiapan & Pemutaran Video Profil',
                'aspek' => [
                    'Kualitas audio-visual (Sound & Komputer).',
                    'Kesiapan teknis tim ICT.',
                ],
            ],
            [
                'waktu_runddown' => '19.00 - 19.15',
                'waktu_realisasi' => '19.00 - 19.10',
                'kegiatan' => 'Opening PKKMB (MC & Welcoming Da...)',
                'aspek' => [
                    'Kemampuan MC membuka acara.',
                    'Antusiasme peserta.',
                ],
            ],
            [
                'waktu_runddown' => '19.15 - 19.25',
                'waktu_realisasi' => '19.25 - 19.33',
                'kegiatan' => "Pembacaan Ayat Suci Al-Qur'an",
                'aspek' => [
                    'Kekhidmatan suasana.',
                    'Kualitas audio (mic).',
                ],
            ],
            [
                'waktu_runddown' => '19.30 - 19.40',
                'waktu_realisasi' => '19.33 - 19.47',
                'kegiatan' => 'Menyanyikan Lagu Indonesia Raya & M...',
                'aspek' => [
                    'Partisipasi dan semangat peserta.',
                    'Kesiapan dirigen & audio.',
                ],
            ],
            [
                'waktu_runddown' => '20.00 - 20.10',
                'waktu_realisasi' => '19.47 - 19.52',
                'kegiatan' => 'Penyematan Tanda Peserta',
                'aspek' => [
                    'Kelancaran prosesi.',
                    'Koordinasi panitia (PIC: Pak Raga).',
                ],
            ],
            [
                'waktu_runddown' => '20.10 - 20.25',
                'waktu_realisasi' => '19.52 - 20.18',
                'kegiatan' => 'Pembukaan oleh Rektor',
                'aspek' => [
                    'Kejelasan penyampaian Visi & Misi.',
                    'Ketepatan durasi.',
                ],
            ],
            [
                'waktu_runddown' => '20.25 - 20.35',
                'waktu_realisasi' => '20.19 - 20.40',
                'kegiatan' => 'Wawasan Sejarah Ibnu Sina (Dr. Juni...)',
                'aspek' => [
                    'Kejelasan materi.',
                    'Perhatian dan antusiasme peserta.',
                ],
            ],
            [
                'waktu_runddown' => '20.35 - 21.05',
                'waktu_realisasi' => '20.42 - 21.12',
                'kegiatan' => 'Pengenalan Pimpinan & Staf',
                'aspek' => [
                    'Kelancaran dan efisiensi alur perkenalan.',
                    'Kejelasan informasi dari MC & Kabid SDM.',
                ],
            ],
            [
                'waktu_runddown' => '21.05 - 21.10',
                'waktu_realisasi' => 'Tidak ada',
                'kegiatan' => 'Ice Breaking (UKM Perkusi)',
                'aspek' => [
                    'Kemampuan penampilan dalam membangkitkan semangat.',
                ],
            ],
            [
                'waktu_runddown' => '21.10 - 21.55',
                'waktu_realisasi' => '21.13',
                'kegiatan' => 'Pelayanan Kemahasiswaan & Pusat P...',
                'aspek' => [
                    'Relevansi dan kejelasan materi.',
                    'Efektivitas moderator (Agus).',
                ],
            ],
            [
                'waktu_runddown' => '21.55 - 22.00',
                'waktu_realisasi' => 'tepat waktu',
                'kegiatan' => 'Pengumuman & Penutupan',
                'aspek' => [
                    'Kejelasan informasi untuk kegiatan hari kedua.',
                ],
            ],
        ];

        foreach ($rows as $row) {
            $aspekText = implode("\n", array_map(
                fn ($index, $item) => ($index + 1) . '. ' . $item,
                array_keys($row['aspek']),
                $row['aspek']
            ));

            ObservasiAcara::create([
                'waktu_runddown' => $row['waktu_runddown'],
                'waktu_realisasi' => $row['waktu_realisasi'],
                'kegiatan' => $row['kegiatan'],
                'aspek_observasi' => $aspekText,
            ]);
        }
    }
}
