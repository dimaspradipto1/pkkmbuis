<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class KedisiplinanExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected string $modelClass;
    protected string $title;

    public function __construct(string $modelClass, string $title = 'DATA KEDISIPLINAN MAHASISWA - PKKMB UIS 2026')
    {
        $this->modelClass = $modelClass;
        $this->title = $title;
    }

    public function collection()
    {
        $items = $this->modelClass::with(['user.kelompok'])
            ->whereHas('user', function ($q) {
                $q->where('role', 'mahasiswa');
            })
            ->get();

        return $items->map(function ($item, $index) {
            return [
                'no'                  => $index + 1,
                'nama'                => $item->user->name ?? '-',
                'npm'                 => $item->user->id_pendaftar ?? '-',
                'kelompok'            => $item->user->kelompok->nama_kelompok ?? '-',
                'kelengkapan_atribut' => $item->kelengkapan_atribut ?? '-',
                'ketepatan_waktu'     => $item->ketepatan_waktu ?? '-',
                'perilaku'            => $item->perilaku ?? '-',
                'catatan'             => $item->catatan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA MAHASISWA',
            'NPM / ID PENDAFTAR',
            'KELOMPOK',
            'KELENGKAPAN ATRIBUT',
            'KETEPATAN WAKTU',
            'PERILAKU',
            'CATATAN',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'H';

                // Insert 3 title rows at top
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

                // Column Headers Style on Row 4
                $sheet->getStyle("A4:{$lastColumn}4")->applyFromArray([
                    'font' => [
                        'name' => 'Segoe UI',
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '046B26'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(4)->setRowHeight(26);

                // Border for data rows
                $highestRow = $sheet->getHighestRow();
                if ($highestRow >= 5) {
                    $sheet->getStyle("A4:{$lastColumn}{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => 'D0D0D0'],
                            ],
                        ],
                    ]);
                    
                    // Align center NO column
                    $sheet->getStyle("A5:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Align center NPM column
                    $sheet->getStyle("C5:C{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Align center status columns
                    $sheet->getStyle("E5:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
