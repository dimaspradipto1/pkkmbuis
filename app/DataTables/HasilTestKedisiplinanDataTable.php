<?php

namespace App\DataTables;

use App\Models\HasilTest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HasilTestKedisiplinanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('d1_a', fn($row) => $this->formatDisiplin($row->kedisiplinanPertama?->kelengkapan_atribut))
            ->addColumn('d1_w', fn($row) => $this->formatDisiplin($row->kedisiplinanPertama?->ketepatan_waktu))
            ->addColumn('d1_p', fn($row) => $this->formatDisiplin($row->kedisiplinanPertama?->perilaku))
            ->addColumn('d2_a', fn($row) => $this->formatDisiplin($row->kedisiplinanKedua?->kelengkapan_atribut))
            ->addColumn('d2_w', fn($row) => $this->formatDisiplin($row->kedisiplinanKedua?->ketepatan_waktu))
            ->addColumn('d2_p', fn($row) => $this->formatDisiplin($row->kedisiplinanKedua?->perilaku))
            ->addColumn('d3_a', fn($row) => $this->formatDisiplin($row->kedisiplinanKetiga?->kelengkapan_atribut))
            ->addColumn('d3_w', fn($row) => $this->formatDisiplin($row->kedisiplinanKetiga?->ketepatan_waktu))
            ->addColumn('d3_p', fn($row) => $this->formatDisiplin($row->kedisiplinanKetiga?->perilaku))
            ->rawColumns(['d1_a', 'd1_w', 'd1_p', 'd2_a', 'd2_w', 'd2_p', 'd3_a', 'd3_w', 'd3_p']);
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

    public function query(\App\Models\User $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('role', 'mahasiswa')
            ->with(['kedisiplinanPertama', 'kedisiplinanKedua', 'kedisiplinanKetiga']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('kedisiplinan-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('hasiltest.index', ['table' => 'kedisiplinan']))
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
            Column::computed('d1_a')->title('D1 A')->addClass('text-center align-middle'),
            Column::computed('d1_w')->title('D1 W')->addClass('text-center align-middle'),
            Column::computed('d1_p')->title('D1 P')->addClass('text-center align-middle'),
            Column::computed('d2_a')->title('D2 A')->addClass('text-center align-middle'),
            Column::computed('d2_w')->title('D2 W')->addClass('text-center align-middle'),
            Column::computed('d2_p')->title('D2 P')->addClass('text-center align-middle'),
            Column::computed('d3_a')->title('D3 A')->addClass('text-center align-middle'),
            Column::computed('d3_w')->title('D3 W')->addClass('text-center align-middle'),
            Column::computed('d3_p')->title('D3 P')->addClass('text-center align-middle'),
        ];
    }

    protected function filename(): string
    {
        return 'HasilTestKedisiplinan_' . date('YmdHis');
    }
}
