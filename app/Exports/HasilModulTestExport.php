<?php

namespace App\Exports;

use App\Models\HasilTest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HasilModulTestExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected string $type;
    protected int $modul;

    public function __construct(string $type, int $modul)
    {
        $this->type = $type;
        $this->modul = $modul;
    }

    public function collection()
    {
        return HasilTest::where('type', $this->type)
            ->where('modul', $this->modul)
            ->with(['user.kelompok'])
            ->latest('updated_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Pendaftar / NPM',
            'Nama Mahasiswa',
            'Kelompok',
            'Tipe Test',
            'Modul',
            'Skor / Nilai',
            'Jumlah Benar',
            'Total Soal',
            'Status',
            'Waktu Mengerjakan',
        ];
    }

    public function map($row): array
    {
        return [
            $row->user?->id_pendaftar ?? $row->user?->nim ?? '-',
            $row->user?->name ?? '-',
            $row->user?->kelompok?->nama_kelompok ?? '-',
            strtoupper($row->type),
            'Modul ' . $row->modul,
            $row->skor,
            $row->jumlah_benar,
            $row->total_soal,
            $row->skor >= 65 ? 'Tuntas' : 'Tidak Tuntas',
            $row->created_at ? $row->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') : '-',
        ];
    }
}
