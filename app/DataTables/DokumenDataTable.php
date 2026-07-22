<?php

namespace App\DataTables;

use App\Models\Dokumen;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DokumenDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                if (Auth::user()->role != 'admin') return '';
                
                $editBtn = '<a href="' . route('dokumen.edit', $item->id) . '" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>';
                $deleteBtn = '<form action="' . route('dokumen.destroy', $item->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin hapus?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                              </form>';
                return $editBtn . ' ' . $deleteBtn;
            })
            ->editColumn('link_buku_saku', function($item) {
                return $item->link_buku_saku ? '<a href="'.$item->link_buku_saku.'" target="_blank" class="btn btn-sm btn-outline-info">Buka Link</a>' : '-';
            })
            ->editColumn('link_daftar_kelompok', function($item) {
                return $item->link_daftar_kelompok ? '<a href="'.$item->link_daftar_kelompok.'" target="_blank" class="btn btn-sm btn-outline-info">Buka Link</a>' : '-';
            })
            ->editColumn('link_rundown', function($item) {
                return $item->link_rundown ? '<a href="'.$item->link_rundown.'" target="_blank" class="btn btn-sm btn-outline-info">Buka Link</a>' : '-';
            })
            ->rawColumns(['action', 'link_buku_saku', 'link_daftar_kelompok', 'link_rundown'])
            ->setRowId('id');
    }

    public function query(Dokumen $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('dokumen-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->parameters([
                        'dom' => 'Bfrtip',
                        'buttons' => ['excel', 'csv', 'pdf', 'print', 'reset', 'reload'],
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false)->width(50),
            Column::make('link_buku_saku')->title('Link Buku Saku'),
            Column::make('link_daftar_kelompok')->title('Link Daftar Kelompok'),
            Column::make('link_rundown')->title('Link Rundown'),
            Column::computed('action')
                  ->title('Aksi')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Dokumen_' . date('YmdHis');
    }
}
