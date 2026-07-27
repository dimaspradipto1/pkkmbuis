<?php

namespace App\DataTables;

use App\Models\ObservasiAcara;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ObservasiAcaraDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<ObservasiAcara> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('aspek_observasi', function ($item) {
                if (empty($item->aspek_observasi)) {
                    return '-';
                }
                return nl2br(e($item->aspek_observasi));
            })
            ->addColumn('link_dokumen', function ($item) {
                if (empty($item->link_dokumen)) {
                    return '-';
                }
                $links = array_values(array_filter(array_map('trim', explode("\n", $item->link_dokumen))));
                if (empty($links)) {
                    return '-';
                }
                return implode(' ', array_map(function ($link, $index) {
                    return '<a href="' . e($link) . '" target="_blank" class="btn btn-sm btn-outline-primary px-2 py-1 mb-1 me-1"><i class="bi bi-link-45deg"></i> Link ' . ($index + 1) . '</a>';
                }, $links, array_keys($links)));
            })
            ->addColumn('action', function ($item) {
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="' . route('observasiacara.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('observasiacara.destroy', $item->id) . '" method="POST" style="display: inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded" onclick="return confirm(\'Yakin ingin menghapus data observasi ini?\')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['action', 'DT_RowIndex', 'aspek_observasi', 'link_dokumen']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<ObservasiAcara>
     */
    public function query(ObservasiAcara $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'asc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('observasiacara-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->ordering(false)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload'),
                    ])->parameters([
                        'scrollX' => true,
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('NO')
                ->orderable(false)
                ->searchable(false),
            Column::make('waktu_runddown')
                ->title('Waktu Rundown'),
            Column::make('waktu_realisasi')
                ->title('Waktu Realisasi'),
            Column::make('kegiatan')
                ->title('Kegiatan'),
            Column::make('aspek_observasi')
                ->title('Aspek Observasi'),
            Column::make('skala')
                ->title('Skala'),
            Column::make('catatan')
                ->title('Catatan'),
            Column::make('link_dokumen')
                ->title('Dokumen'),
            Column::computed('action')
                ->title('AKSI')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ObservasiAcara_' . date('YmdHis');
    }
}
