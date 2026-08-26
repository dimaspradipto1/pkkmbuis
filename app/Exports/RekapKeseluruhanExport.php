<?php

namespace App\Exports;

use App\Models\ModulSetting;
use App\Models\PostTestSetting;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapKeseluruhanExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected string $title;

    public function __construct(string $title = 'MASTER REPORT - REKAPITULASI KESELURUHAN MAHASISWA')
    {
        $this->title = $title;
    }

    public function collection()
    {
        $students = User::where('role', 'mahasiswa')
            ->with([
                'absenPertama', 'absenKedua', 'absenKetiga',
                'kedisiplinanPertama', 'kedisiplinanKedua', 'kedisiplinanKetiga',
                'hasilTests', 'tugasKelompok'
            ])
            ->orderBy('name', 'asc')
            ->get();

        $activePosttestModules = PostTestSetting::getActiveModules();
        $activePosttestCount = count($activePosttestModules);
        if ($activePosttestCount === 0) {
            $activePosttestModules = ModulSetting::getActivePosttestModules();
            $activePosttestCount = count($activePosttestModules);
        }
        if ($activePosttestCount === 0) {
            $activePosttestModules = [1, 2, 3, 4];
            $activePosttestCount = 4;
        }

        return $students->map(function ($row, $index) use ($activePosttestModules, $activePosttestCount) {
            // 1. Post-Test (Dinamis)
            $postTestScores = $row->hasilTests
                ->where('type', 'posttest')
                ->whereIn('modul', $activePosttestModules)
                ->pluck('skor')
                ->toArray();

            $scoreTes = ($activePosttestCount > 0 && count($postTestScores) > 0) ? (array_sum($postTestScores) / $activePosttestCount) : 0;

            // 2. Tugas Kelompok
            $scoreTugas = ($row->tugasKelompok && (!empty($row->tugasKelompok->link_tugas) || !empty($row->tugasKelompok->nilai))) ? ($row->tugasKelompok->nilai ?: 100) : 0;

            // 3. Absensi (6 metrics)
            $absPoints = 0;
            $absenRecords = [$row->absenPertama, $row->absenKedua, $row->absenKetiga];
            foreach ($absenRecords as $rec) {
                if ($rec) {
                    $pagi = strtolower($rec->hadir_pagi ?? '');
                    if ($pagi !== '' && str_contains($pagi, 'hadir') && !str_contains($pagi, 'tidak')) $absPoints++;

                    $sore = strtolower($rec->hadir_sore ?? '');
                    if ($sore !== '' && str_contains($sore, 'hadir') && !str_contains($sore, 'tidak')) $absPoints++;
                }
            }
            $scoreAbs = ($absPoints / 6) * 100;

            // 4. Kedisiplinan (9 metrics)
            $disPoints = 0;
            $disRecords = [$row->kedisiplinanPertama, $row->kedisiplinanKedua, $row->kedisiplinanKetiga];
            foreach ($disRecords as $rec) {
                if ($rec) {
                    if (strtolower($rec->kelengkapan_atribut ?? '') === 'lengkap') $disPoints++;
                    if (strtolower($rec->ketepatan_waktu ?? '') === 'tepat waktu') $disPoints++;
                    if (in_array(strtolower($rec->perilaku ?? ''), ['baik', 'sangat baik'])) $disPoints++;
                }
            }
            $scoreDis = ($disPoints / 9) * 100;

            // Total Nilai Akhir: Test 10%, Tugas 10%, Absen 50%, Disiplin 30%
            $totalAkhir = ($scoreTes * 0.1) + ($scoreTugas * 0.1) + ($scoreAbs * 0.5) + ($scoreDis * 0.3);

            return [
                'no'           => $index + 1,
                'nama'         => $row->name,
                'a1_pagi'      => $row->absenPertama?->hadir_pagi ?? '-',
                'a1_sore'      => $row->absenPertama?->hadir_sore ?? '-',
                'a2_pagi'      => $row->absenKedua?->hadir_pagi ?? '-',
                'a2_sore'      => $row->absenKedua?->hadir_sore ?? '-',
                'a3_pagi'      => $row->absenKetiga?->hadir_pagi ?? '-',
                'a3_sore'      => $row->absenKetiga?->hadir_sore ?? '-',
                'd1_atribut'   => $row->kedisiplinanPertama?->kelengkapan_atribut ?? '-',
                'd1_waktu'     => $row->kedisiplinanPertama?->ketepatan_waktu ?? '-',
                'd1_perilaku'  => $row->kedisiplinanPertama?->perilaku ?? '-',
                'd2_atribut'   => $row->kedisiplinanKedua?->kelengkapan_atribut ?? '-',
                'd2_waktu'     => $row->kedisiplinanKedua?->ketepatan_waktu ?? '-',
                'd2_perilaku'  => $row->kedisiplinanKedua?->perilaku ?? '-',
                'd3_atribut'   => $row->kedisiplinanKetiga?->kelengkapan_atribut ?? '-',
                'd3_waktu'     => $row->kedisiplinanKetiga?->ketepatan_waktu ?? '-',
                'd3_perilaku'  => $row->kedisiplinanKetiga?->perilaku ?? '-',
                'm1_pre'       => $row->hasilTests->where('modul', 1)->where('type', 'pretest')->first()?->skor ?? '-',
                'm1_post'      => $row->hasilTests->where('modul', 1)->where('type', 'posttest')->first()?->skor ?? '-',
                'm2_pre'       => $row->hasilTests->where('modul', 2)->where('type', 'pretest')->first()?->skor ?? '-',
                'm2_post'      => $row->hasilTests->where('modul', 2)->where('type', 'posttest')->first()?->skor ?? '-',
                'm3_pre'       => $row->hasilTests->where('modul', 3)->where('type', 'pretest')->first()?->skor ?? '-',
                'm3_post'      => $row->hasilTests->where('modul', 3)->where('type', 'posttest')->first()?->skor ?? '-',
                'm4_pre'       => $row->hasilTests->where('modul', 4)->where('type', 'pretest')->first()?->skor ?? '-',
                'm4_post'      => $row->hasilTests->where('modul', 4)->where('type', 'posttest')->first()?->skor ?? '-',
                'm5_tugas'     => ($row->tugasKelompok && (!empty($row->tugasKelompok->link_tugas) || !empty($row->tugasKelompok->nilai))) ? ($row->tugasKelompok->nilai ?: 100) : '-',
                'score_tes'    => round($scoreTes, 2),
                'score_tugas'  => round($scoreTugas, 2),
                'score_absen'  => round($scoreAbs, 2),
                'score_disiplin' => round($scoreDis, 2),
                'total_akhir'  => round($totalAkhir, 2),
            ];
        });
    }

    public function headings(): array
    {
        return [
            [
                'NO',
                'NAMA MAHASISWA',
                'ABSEN HARI PERTAMA',
                '',
                'ABSEN HARI KEDUA',
                '',
                'ABSEN HARI KETIGA',
                '',
                'KEDISIPLINAN HARI PERTAMA',
                '',
                '',
                'KEDISIPLINAN HARI KEDUA',
                '',
                '',
                'KEDISIPLINAN HARI KETIGA',
                '',
                '',
                'MODUL 1',
                '',
                'MODUL 2',
                '',
                'MODUL 3',
                '',
                'MODUL 4',
                '',
                'MODUL 5',
                'REKAPITULASI NILAI AKHIR (BOBOT)',
                '',
                '',
                '',
                '',
            ],
            [
                '',
                '',
                'PAGI',
                'SORE',
                'PAGI',
                'SORE',
                'PAGI',
                'SORE',
                'ATRIBUT',
                'WAKTU',
                'PERILAKU',
                'ATRIBUT',
                'WAKTU',
                'PERILAKU',
                'ATRIBUT',
                'WAKTU',
                'PERILAKU',
                'PRE',
                'POST',
                'PRE',
                'POST',
                'PRE',
                'POST',
                'PRE',
                'POST',
                'TUGAS',
                'TEST (10%)',
                'TUGAS (10%)',
                'KEHADIRAN (50%)',
                'KEDISIPLINAN (30%)',
                'TOTAL NILAI AKHIR',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'AE';

                // Insert 3 title rows at the top
                $sheet->insertNewRowBefore(1, 3);

                // Set Title in A1
                $sheet->setCellValue('A1', strtoupper($this->title));
                $sheet->mergeCells("A1:{$lastColumn}1");

                // Set Subtitle in A2
                $sheet->setCellValue('A2', 'Dicetak Pada: ' . date('d-m-Y H:i') . ' WIB | PKKMB Universitas Ibnu Sina');
                $sheet->mergeCells("A2:{$lastColumn}2");

                // Title Style A1
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'name' => 'Segoe UI',
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '046B26'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Subtitle Style A2
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'name' => 'Segoe UI',
                        'italic' => true,
                        'size' => 9,
                        'color' => ['rgb' => '6C757D'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Merge Table Headers on Rows 4 & 5
                $sheet->mergeCells('A4:A5'); // NO
                $sheet->mergeCells('B4:B5'); // NAMA MAHASISWA
                $sheet->mergeCells('C4:D4'); // ABSEN H1
                $sheet->mergeCells('E4:F4'); // ABSEN H2
                $sheet->mergeCells('G4:H4'); // ABSEN H3
                $sheet->mergeCells('I4:K4'); // KEDISIPLINAN H1
                $sheet->mergeCells('L4:N4'); // KEDISIPLINAN H2
                $sheet->mergeCells('O4:Q4'); // KEDISIPLINAN H3
                $sheet->mergeCells('R4:S4'); // MODUL 1
                $sheet->mergeCells('T4:U4'); // MODUL 2
                $sheet->mergeCells('V4:W4'); // MODUL 3
                $sheet->mergeCells('X4:Y4'); // MODUL 4
                $sheet->mergeCells('Z4:Z5'); // MODUL 5 (TUGAS)
                $sheet->mergeCells('AA4:AE4'); // REKAPITULASI NILAI AKHIR (BOBOT)

                // Header Style on Row 4 & 5
                $sheet->getStyle("A4:{$lastColumn}5")->applyFromArray([
                    'font' => [
                        'name' => 'Segoe UI',
                        'bold' => true,
                        'size' => 10,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '046B26'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Row Heights
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(3)->setRowHeight(10);
                $sheet->getRowDimension(4)->setRowHeight(24);
                $sheet->getRowDimension(5)->setRowHeight(22);

                // Data Rows Formatting
                $highestRow = $sheet->getHighestRow();
                if ($highestRow >= 6) {
                    $sheet->getStyle("A4:{$lastColumn}{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'D0D0D0'],
                            ],
                        ],
                        'font' => [
                            'name' => 'Segoe UI',
                            'size' => 10,
                        ],
                    ]);

                    // Alignments
                    $sheet->getStyle("A6:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B6:B{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle("C6:{$lastColumn}{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Emphasize Total Nilai Akhir (Column AE)
                    $sheet->getStyle("AE6:AE{$highestRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => '087C39'],
                        ],
                    ]);
                }
            },
        ];
    }
}

