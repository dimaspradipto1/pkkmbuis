<?php

namespace App\DataTables;

use App\Models\HasilTest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HasilTestAbsensiDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('a1_p', fn($row) => $this->formatAbsen($row->absenPertama?->hadir_pagi))
            ->addColumn('a1_s', fn($row) => $this->formatAbsen($row->absenPertama?->hadir_sore))
            ->addColumn('a2_p', fn($row) => $this->formatAbsen($row->absenKedua?->hadir_pagi))
            ->addColumn('a2_s', fn($row) => $this->formatAbsen($row->absenKedua?->hadir_sore))
            ->addColumn('a3_p', fn($row) => $this->formatAbsen($row->absenKetiga?->hadir_pagi))
            ->addColumn('a3_s', fn($row) => $this->formatAbsen($row->absenKetiga?->hadir_sore))
            ->addColumn('nilai_absen', function ($row) {
                $points = 0;
                $absenRecords = [$row->absenPertama, $row->absenKedua, $row->absenKetiga];
                
                foreach ($absenRecords as $rec) {
                    if ($rec) {
                        $pagi = strtolower($rec->hadir_pagi ?? '');
                        if ($pagi !== '' && str_contains($pagi, 'hadir') && !str_contains($pagi, 'tidak')) $points++;
                        
                        $sore = strtolower($rec->hadir_sore ?? '');
                        if ($sore !== '' && str_contains($sore, 'hadir') && !str_contains($sore, 'tidak')) $points++;
                    }
                }
                $score = round(($points / 6) * 100, 2);
                return '<span class="fw-bold text-primary">' . $score . '</span>';
            })
            ->rawColumns(['a1_p', 'a1_s', 'a2_p', 'a2_s', 'a3_p', 'a3_s', 'nilai_absen']);
    }

    private function formatAbsen($status) {
        if (!$status || $status == 'Belum Absen') return '<small class="text-muted opacity-50">-</small>';
        
        if ($status == 'Hadir') {
            return '<span class="text-success fw-bold">H</span>';
        } elseif ($status == 'Izin') {
            return '<span class="text-info fw-bold">I</span>';
        } elseif ($status == 'Sakit') {
            return '<span class="text-warning fw-bold">S</span>';
        }
        
        return '<span class="text-danger fw-bold">A</span>';
    }

    public function query(\App\Models\User $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('role', 'mahasiswa')
            ->with(['absenPertama', 'absenKedua', 'absenKetiga']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('absensi-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('hasiltest.index', ['table' => 'absensi']))
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->parameters([
                        'scrollX' => true,
                        'autoWidth' => false,
                        'dom' => 'Bfrtip',
                        'pageLength' => 25,
                        'language' => ['search' => 'Cari:'],
                    ])
                    ->buttons([
                        Button::make('excel'),
                        Button::make('print'),
                        Button::make('reload'),
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('NO')->orderable(false)->searchable(false)->addClass('text-center align-middle')->width(50),
            Column::make('name')->title('NAMA MAHASISWA')->addClass('fw-bold align-middle')->width(200),
            Column::computed('a1_p')->title('H1 P')->addClass('text-center align-middle bg-light'),
            Column::computed('a1_s')->title('H1 S')->addClass('text-center align-middle bg-light'),
            Column::computed('a2_p')->title('H2 P')->addClass('text-center align-middle bg-light'),
            Column::computed('a2_s')->title('H2 S')->addClass('text-center align-middle bg-light'),
            Column::computed('a3_p')->title('H3 P')->addClass('text-center align-middle bg-light'),
            Column::computed('a3_s')->title('H3 S')->addClass('text-center align-middle bg-light'),
            Column::computed('nilai_absen')->title('NILAI ABSEN')->addClass('text-center align-middle bg-light fw-bold'),
        ];
    }

    protected function filename(): string
    {
        return 'HasilTestAbsensi_' . date('YmdHis');
    }
}
