<?php

namespace App\DataTables;

use App\Models\HasilTest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class HasilTestDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<HasilTest> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('checkbox', function($row) {
                return '<input type="checkbox" name="ids[]" value="'.$row->id.'" class="form-check-input record-checkbox">';
            })
            // Absensi
            ->addColumn('a1_p', fn($row) => $this->formatAbsen($row->absenPertama?->hadir_pagi))
            ->addColumn('a1_s', fn($row) => $this->formatAbsen($row->absenPertama?->hadir_sore))
            ->addColumn('a2_p', fn($row) => $this->formatAbsen($row->absenKedua?->hadir_pagi))
            ->addColumn('a2_s', fn($row) => $this->formatAbsen($row->absenKedua?->hadir_sore))
            ->addColumn('a3_p', fn($row) => $this->formatAbsen($row->absenKetiga?->hadir_pagi))
            ->addColumn('a3_s', fn($row) => $this->formatAbsen($row->absenKetiga?->hadir_sore))
            
            // Kedisiplinan
            ->addColumn('d1_a', fn($row) => $this->formatDisiplin($row->kedisiplinanPertama?->kelengkapan_atribut))
            ->addColumn('d1_w', fn($row) => $this->formatDisiplin($row->kedisiplinanPertama?->ketepatan_waktu))
            ->addColumn('d1_p', fn($row) => $this->formatDisiplin($row->kedisiplinanPertama?->perilaku))
            
            ->addColumn('d2_a', fn($row) => $this->formatDisiplin($row->kedisiplinanKedua?->kelengkapan_atribut))
            ->addColumn('d2_w', fn($row) => $this->formatDisiplin($row->kedisiplinanKedua?->ketepatan_waktu))
            ->addColumn('d2_p', fn($row) => $this->formatDisiplin($row->kedisiplinanKedua?->perilaku))
            
            ->addColumn('d3_a', fn($row) => $this->formatDisiplin($row->kedisiplinanKetiga?->kelengkapan_atribut))
            ->addColumn('d3_w', fn($row) => $this->formatDisiplin($row->kedisiplinanKetiga?->ketepatan_waktu))
            ->addColumn('d3_p', fn($row) => $this->formatDisiplin($row->kedisiplinanKetiga?->perilaku))

            // Modules
            ->addColumn('m1_pre', fn($row) => $this->getScore($row, 1, 'pretest'))
            ->addColumn('m1_post', fn($row) => $this->getScore($row, 1, 'posttest'))
            ->addColumn('m2_pre', fn($row) => $this->getScore($row, 2, 'pretest'))
            ->addColumn('m2_post', fn($row) => $this->getScore($row, 2, 'posttest'))
            ->addColumn('m3_pre', fn($row) => $this->getScore($row, 3, 'pretest'))
            ->addColumn('m3_post', fn($row) => $this->getScore($row, 3, 'posttest'))
            ->addColumn('m4_pre', fn($row) => $this->getScore($row, 4, 'pretest'))
            ->addColumn('m4_post', fn($row) => $this->getScore($row, 4, 'posttest'))
            ->addColumn('m5_tugas', fn($row) => ($row->tugasKelompok && !empty($row->tugasKelompok->link_tugas)) ? '<span class="fw-bold text-success">100</span>' : '<span class="text-muted opacity-50">-</span>')
            
            ->addColumn('action', function($row) {
                $btn = '<form action="' . route('hasiltest.resetByUser', $row->id) . '" method="POST" class="d-inline">';
                $btn .= csrf_field();
                $btn .= '<button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Reset</button>';
                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns([
                'checkbox', 'a1_p', 'a1_s', 'a2_p', 'a2_s', 'a3_p', 'a3_s', 
                'd1_a', 'd1_w', 'd1_p', 'd2_a', 'd2_w', 'd2_p', 'd3_a', 'd3_w', 'd3_p',
                'm1_pre', 'm1_post', 'm2_pre', 'm2_post', 'm3_pre', 'm3_post', 'm4_pre', 'm4_post', 'm5_tugas', 'action'
            ]);
    }

    private function formatAbsen($status) {
        if (!$status || $status == 'Belum Absen') return '<small class="text-muted opacity-50">-</small>';
        return $status == 'Hadir' ? '<span class="text-success fw-bold">H</span>' : '<span class="text-danger fw-bold">A</span>';
    }

    private function formatDisiplin($val) {
        if (!$val || $val == '-') return '<small class="text-muted opacity-50">-</small>';
        
        $map = [
            'Lengkap' => 'L',
            'Tidak Lengkap' => 'TL',
            'Tepat Waktu' => 'TW',
            'Terlambat' => 'TR',
            'Sangat Terlambat' => 'ST',
            'Baik' => 'B',
            'Cukup' => 'C',
            'Buruk' => 'BR'
        ];

        $initial = $map[$val] ?? $val;
        return '<span class="fw-bold" style="color: #012970;">'.$initial.'</span>';
    }

    private function getScore($user, $modul, $type) {
        $test = $user->hasilTests->where('modul', $modul)->where('type', $type)->first();
        if (!$test) return '<small class="text-muted opacity-50">-</small>';
        $color = $test->skor >= 65 ? 'text-success' : 'text-danger';
        return '<span class="fw-bold '.$color.'">'.$test->skor.'</span>';
    }

    public function query(\App\Models\User $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('role', 'mahasiswa')
            ->with([
                'absenPertama', 'absenKedua', 'absenKetiga',
                'kedisiplinanPertama', 'kedisiplinanKedua', 'kedisiplinanKetiga',
                'hasilTests', 'tugasKelompok'
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('hasiltest-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
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
            Column::make('checkbox')
                  ->title('<input type="checkbox" id="select-all" class="form-check-input">')
                  ->orderable(false)
                  ->searchable(false)
                  ->exportable(false)
                  ->printable(false)
                  ->width(40)
                  ->addClass('text-center align-middle'),
            Column::make('DT_RowIndex')->title('NO')->orderable(false)->searchable(false)->addClass('text-center align-middle'),
            Column::make('name')->title('NAMA MAHASISWA')->addClass('fw-bold align-middle')->width(150),
            
            // Absen Day 1
            Column::computed('a1_p')->title('H1 P')->addClass('text-center align-middle bg-light'),
            Column::computed('a1_s')->title('H1 S')->addClass('text-center align-middle bg-light'),
            
            // Absen Day 2
            Column::computed('a2_p')->title('H2 P')->addClass('text-center align-middle bg-light'),
            Column::computed('a2_s')->title('H2 S')->addClass('text-center align-middle bg-light'),
            
            // Absen Day 3
            Column::computed('a3_p')->title('H3 P')->addClass('text-center align-middle bg-light'),
            Column::computed('a3_s')->title('H3 S')->addClass('text-center align-middle bg-light'),

            // Kedisiplinan 1
            Column::computed('d1_a')->title('D1 A')->addClass('text-center align-middle'),
            Column::computed('d1_w')->title('D1 W')->addClass('text-center align-middle'),
            Column::computed('d1_p')->title('D1 P')->addClass('text-center align-middle'),

            // Kedisiplinan 2
            Column::computed('d2_a')->title('D2 A')->addClass('text-center align-middle'),
            Column::computed('d2_w')->title('D2 W')->addClass('text-center align-middle'),
            Column::computed('d2_p')->title('D2 P')->addClass('text-center align-middle'),

            // Kedisiplinan 3
            Column::computed('d3_a')->title('D3 A')->addClass('text-center align-middle'),
            Column::computed('d3_w')->title('D3 W')->addClass('text-center align-middle'),
            Column::computed('d3_p')->title('D3 P')->addClass('text-center align-middle'),
            
            // Modules
            Column::computed('m1_pre')->title('M1 PRE')->addClass('text-center align-middle bg-light'),
            Column::computed('m1_post')->title('M1 POST')->addClass('text-center align-middle bg-light'),
            Column::computed('m2_pre')->title('M2 PRE')->addClass('text-center align-middle bg-light'),
            Column::computed('m2_post')->title('M2 POST')->addClass('text-center align-middle bg-light'),
            Column::computed('m3_pre')->title('M3 PRE')->addClass('text-center align-middle bg-light'),
            Column::computed('m3_post')->title('M3 POST')->addClass('text-center align-middle bg-light'),
            Column::computed('m4_pre')->title('M4 PRE')->addClass('text-center align-middle bg-light'),
            Column::computed('m4_post')->title('M4 POST')->addClass('text-center align-middle bg-light'),
            
            Column::computed('m5_tugas')->title('M5 TGS')->addClass('text-center align-middle'),
            Column::computed('action')->title('ACT')->addClass('text-center align-middle')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'HasilTest_' . date('YmdHis');
    }
}
