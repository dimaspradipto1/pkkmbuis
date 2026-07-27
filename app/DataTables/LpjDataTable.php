<?php

namespace App\DataTables;

use App\Models\LpjAttachment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LpjDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<LpjAttachment> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('jenis', function ($item) {
                $badges = [];
                if (!empty($item->file)) {
                    $badges[] = '<span class="badge bg-primary">File</span>';
                }
                if (!empty($item->link)) {
                    $badges[] = '<span class="badge bg-success">Link</span>';
                }
                return implode(' ', $badges);
            })
            ->addColumn('konten', function ($item) {
                $parts = [];
                if (!empty($item->file)) {
                    $parts[] = '<div><i class="bi bi-file-earmark-text me-1 text-primary"></i>' . e(basename($item->file)) . '</div>';
                }
                if (!empty($item->link)) {
                    $parts[] = '<div><i class="bi bi-link-45deg me-1 text-success"></i>' . e(\Illuminate\Support\Str::limit($item->link, 80)) . '</div>';
                }
                return implode('', $parts);
            })
            ->addColumn('uploader', function ($item) {
                $name = $item->user->name ?? '-';
                $date = $item->created_at ? $item->created_at->format('d M Y H:i') : '-';

                return '<div>' . e($name) . '</div><small class="text-muted">' . e($date) . '</small>';
            })
            ->addColumn('action', function ($item) {
                $buttons = '';

                if (!empty($item->file)) {
                    $fileUrl = asset('storage/' . $item->file);
                    $buttons .= '<a href="' . e($fileUrl) . '" target="_blank" class="btn btn-sm btn-primary text-white px-3 rounded" title="Buka File"><i class="fa-solid fa-file"></i></a>';
                }

                if (!empty($item->link)) {
                    $buttons .= '<a href="' . e($item->link) . '" target="_blank" class="btn btn-sm btn-success text-white px-3 rounded" title="Buka Link"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>';
                }

                $buttons .= '<a href="' . route('lpj-attachments.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                $buttons .= '<form action="' . route('lpj-attachments.destroy', $item->id) . '" method="POST" style="display: inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded" onclick="return confirm(\'Yakin ingin menghapus lampiran ini?\')" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>';

                return '<div class="d-flex justify-content-center gap-1">' . $buttons . '</div>';
            })
            ->rawColumns(['action', 'DT_RowIndex', 'jenis', 'konten', 'uploader']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<LpjAttachment>
     */
    public function query(LpjAttachment $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('lpj-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
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
            Column::computed('jenis')
                ->title('Jenis')
                ->width(80)
                ->addClass('text-center'),
            Column::computed('konten')
                ->title('Link / Nama File'),
            Column::computed('uploader')
                ->title('Diunggah Oleh'),
            Column::computed('action')
                ->title('AKSI')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Lpj_' . date('YmdHis');
    }
}
