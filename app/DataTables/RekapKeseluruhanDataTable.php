<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RekapKeseluruhanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('a1_pagi', fn($row) => $row->absenPertama?->hadir_pagi ?? '-')
            ->addColumn('a1_sore', fn($row) => $row->absenPertama?->hadir_sore ?? '-')
            ->addColumn('a2_pagi', fn($row) => $row->absenKedua?->hadir_pagi ?? '-')
            ->addColumn('a2_sore', fn($row) => $row->absenKedua?->hadir_sore ?? '-')
            ->addColumn('a3_pagi', fn($row) => $row->absenKetiga?->hadir_pagi ?? '-')
            ->addColumn('a3_sore', fn($row) => $row->absenKetiga?->hadir_sore ?? '-')

            // Kedisiplinan Hari 1
            ->addColumn('d1_atribut', fn($row) => $row->kedisiplinanPertama?->kelengkapan_atribut ?? '-')
            ->addColumn('d1_waktu', fn($row) => $row->kedisiplinanPertama?->ketepatan_waktu ?? '-')
            ->addColumn('d1_perilaku', fn($row) => $row->kedisiplinanPertama?->perilaku ?? '-')
            
            // Kedisiplinan Hari 2
            ->addColumn('d2_atribut', fn($row) => $row->kedisiplinanKedua?->kelengkapan_atribut ?? '-')
            ->addColumn('d2_waktu', fn($row) => $row->kedisiplinanKedua?->ketepatan_waktu ?? '-')
            ->addColumn('d2_perilaku', fn($row) => $row->kedisiplinanKedua?->perilaku ?? '-')

            // Kedisiplinan Hari 3
            ->addColumn('d3_atribut', fn($row) => $row->kedisiplinanKetiga?->kelengkapan_atribut ?? '-')
            ->addColumn('d3_waktu', fn($row) => $row->kedisiplinanKetiga?->ketepatan_waktu ?? '-')
            ->addColumn('d3_perilaku', fn($row) => $row->kedisiplinanKetiga?->perilaku ?? '-')

            ->addColumn('m1_pre', fn($row) => $row->hasilTests->where('modul', 1)->where('type', 'pretest')->first()?->skor ?? '-')
            ->addColumn('m1_post', fn($row) => $row->hasilTests->where('modul', 1)->where('type', 'posttest')->first()?->skor ?? '-')
            ->addColumn('m2_pre', fn($row) => $row->hasilTests->where('modul', 2)->where('type', 'pretest')->first()?->skor ?? '-')
            ->addColumn('m2_post', fn($row) => $row->hasilTests->where('modul', 2)->where('type', 'posttest')->first()?->skor ?? '-')
            ->addColumn('m3_pre', fn($row) => $row->hasilTests->where('modul', 3)->where('type', 'pretest')->first()?->skor ?? '-')
            ->addColumn('m3_post', fn($row) => $row->hasilTests->where('modul', 3)->where('type', 'posttest')->first()?->skor ?? '-')
            ->addColumn('m4_pre', fn($row) => $row->hasilTests->where('modul', 4)->where('type', 'pretest')->first()?->skor ?? '-')
            ->addColumn('m4_post', fn($row) => $row->hasilTests->where('modul', 4)->where('type', 'posttest')->first()?->skor ?? '-')

            ->addColumn('m5_tugas', fn($row) => ($row->tugasKelompok && !empty($row->tugasKelompok->link_tugas)) ? '100' : '-');
    }

    public function query(User $model): QueryBuilder
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
            ->setTableId('rekapkeseluruhan-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => route('rekapkeseluruhan.index'),
                'data' => 'function(d) { d.table = "rekapdetail"; }',
            ])
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'scrollX' => true,
                'fixedHeader' => true,
                'autoWidth' => false,
                'pageLength' => 100,
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('NO')->orderable(false)->searchable(false)->addClass('text-center'),
            Column::make('name')->title('NAMA MAHASISWA')->addClass('text-center'),

            // Absensi
            Column::computed('a1_pagi')->title('H1 Pagi')->addClass('text-center'),
            Column::computed('a1_sore')->title('H1 Sore')->addClass('text-center'),
            Column::computed('a2_pagi')->title('H2 Pagi')->addClass('text-center'),
            Column::computed('a2_sore')->title('H2 Sore')->addClass('text-center'),
            Column::computed('a3_pagi')->title('H3 Pagi')->addClass('text-center'),
            Column::computed('a3_sore')->title('H3 Sore')->addClass('text-center'),

            // Kedisiplinan 1
            Column::computed('d1_atribut')->title('D1 Atribut')->addClass('text-center'),
            Column::computed('d1_waktu')->title('D1 Waktu')->addClass('text-center'),
            Column::computed('d1_perilaku')->title('D1 Perilaku')->addClass('text-center'),

            // Kedisiplinan 2
            Column::computed('d2_atribut')->title('D2 Atribut')->addClass('text-center'),
            Column::computed('d2_waktu')->title('D2 Waktu')->addClass('text-center'),
            Column::computed('d2_perilaku')->title('D2 Perilaku')->addClass('text-center'),

            // Kedisiplinan 3
            Column::computed('d3_atribut')->title('D3 Atribut')->addClass('text-center'),
            Column::computed('d3_waktu')->title('D3 Waktu')->addClass('text-center'),
            Column::computed('d3_perilaku')->title('D3 Perilaku')->addClass('text-center'),

            // Nilai
            Column::computed('m1_pre')->title('M1 Pre'),
            Column::computed('m1_post')->title('M1 Post'),
            Column::computed('m2_pre')->title('M2 Pre'),
            Column::computed('m2_post')->title('M2 Post'),
            Column::computed('m3_pre')->title('M3 Pre'),
            Column::computed('m3_post')->title('M3 Post'),
            Column::computed('m4_pre')->title('M4 Pre'),
            Column::computed('m4_post')->title('M4 Post'),
            Column::computed('m5_tugas')->title('M5 Tugas'),
        ];
    }
}
