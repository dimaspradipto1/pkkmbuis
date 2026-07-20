<?php

namespace App\DataTables;

use App\Models\HasilTest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HasilTestPenilaianDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('checkbox', function($row) {
                return '<input type="checkbox" name="ids[]" value="'.$row->id.'" class="form-check-input record-checkbox">';
            })
            ->addColumn('m1_pre', fn($row) => $this->getScore($row, 1, 'pretest'))
            ->addColumn('m1_post', fn($row) => $this->getScore($row, 1, 'posttest'))
            ->addColumn('m2_pre', fn($row) => $this->getScore($row, 2, 'pretest'))
            ->addColumn('m2_post', fn($row) => $this->getScore($row, 2, 'posttest'))
            ->addColumn('m3_pre', fn($row) => $this->getScore($row, 3, 'pretest'))
            ->addColumn('m3_post', fn($row) => $this->getScore($row, 3, 'posttest'))
            ->addColumn('m4_pre', fn($row) => $this->getScore($row, 4, 'pretest'))
            ->addColumn('m4_post', fn($row) => $this->getScore($row, 4, 'posttest'))
            ->addColumn('m5_tugas', function ($row) {
                if ($row->tugasKelompok && !empty($row->tugasKelompok->link_tugas)) {
                    $nilai = $row->tugasKelompok->nilai ?? 0;
                    $color = $nilai >= 65 ? 'text-success' : 'text-danger';
                    return '<span class="fw-bold ' . $color . '">' . $nilai . '</span>';
                }
                return '<span class="text-muted opacity-50">-</span>';
            })
            ->addColumn('nilai_tes', function ($row) {
                $postTestScores = $row->hasilTests->where('type', 'posttest')->pluck('skor')->toArray();
                $tugasScore = $row->tugasKelompok->nilai ?? 0;
                $allScores = array_merge($postTestScores, [$tugasScore]);
                $avg = count($allScores) > 0 ? array_sum($allScores) / 5 : 0; // Divided by 5 metrics (4 posttest + 1 tugas)
                return '<span class="fw-bold text-primary">' . round($avg, 2) . '</span>';
            })
            ->addColumn('action', function($row) {
                $btn = '<form action="' . route('hasiltest.resetByUser', $row->id) . '" method="POST" class="d-inline">';
                $btn .= csrf_field();
                $btn .= '<button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Reset</button>';
                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['checkbox', 'm1_pre', 'm1_post', 'm2_pre', 'm2_post', 'm3_pre', 'm3_post', 'm4_pre', 'm4_post', 'm5_tugas', 'nilai_tes', 'action']);
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
            ->with(['hasilTests', 'tugasKelompok']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('penilaian-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('hasiltest.index', ['table' => 'penilaian']))
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
            Column::make('DT_RowIndex')->title('NO')->orderable(false)->searchable(false)->addClass('text-center align-middle')->width(50),
            Column::make('name')->title('NAMA MAHASISWA')->addClass('fw-bold align-middle')->width(200),
            Column::computed('m1_pre')->title('M1 PRE')->addClass('text-center align-middle bg-light'),
            Column::computed('m1_post')->title('M1 POST')->addClass('text-center align-middle bg-light'),
            Column::computed('m2_pre')->title('M2 PRE')->addClass('text-center align-middle bg-light'),
            Column::computed('m2_post')->title('M2 POST')->addClass('text-center align-middle bg-light'),
            Column::computed('m3_pre')->title('M3 PRE')->addClass('text-center align-middle bg-light'),
            Column::computed('m3_post')->title('M3 POST')->addClass('text-center align-middle bg-light'),
            Column::computed('m4_pre')->title('M4 PRE')->addClass('text-center align-middle bg-light'),
            Column::computed('m4_post')->title('M4 POST')->addClass('text-center align-middle bg-light'),
            Column::computed('m5_tugas')->title('M5 TGS')->addClass('text-center align-middle'),
            Column::computed('nilai_tes')->title('NILAI TES')->addClass('text-center align-middle fw-bold bg-light'),
            Column::computed('action')->title('ACT')->addClass('text-center align-middle')
        ];
    }

    protected function filename(): string
    {
        return 'HasilTestPenilaian_' . date('YmdHis');
    }
}
