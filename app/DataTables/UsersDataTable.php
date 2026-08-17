<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->addColumn('checkbox', function($item){
                if (Auth::user()->role == 'mahasiswa') {
                    return '';
                }
                return '<input type="checkbox" name="ids[]" value="'.$item->id.'" class="form-check-input record-checkbox">';
            })
            ->editColumn('id_pendaftar', function($item){
                return $item->id_pendaftar;
            })
            ->addColumn('is_active', function($item){
                return $item->is_active ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('action', function($item){
                if (Auth::user()->role == 'mahasiswa') {
                    return '';
                }
                
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="' . route('users.updatePassword', $item->id) . '" class="btn btn-sm btn-info text-white px-3 rounded" title="Update Password"><i class="fa-solid fa-key"></i></a>
                        <a href="' . route('users.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded" ><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('users.destroy', $item->id) . '" method="POST" style="display: inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->editColumn('role', function($item){
                if ($item->role == 'panitia') {
                    if (!empty($item->jabatan_panitia)) {
                        return '<span class="badge bg-primary">Panitia</span><br><small class="text-muted fw-semibold">' . e($item->jabatan_panitia) . '</small>';
                    }
                    return '<span class="badge bg-primary">Panitia</span>';
                }
                return ucfirst($item->role);
            })
            ->rawColumns(['DT_RowIndex', 'checkbox', 'action', 'is_active', 'role']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery();

        if (Auth::user()->role == 'mahasiswa') {
            $query->where('id', Auth::id());
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(2)
                    ->selectStyleMulti()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ])->parameters([
                        'scrollX' => true,
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('checkbox')
                ->title(Auth::user()->role != 'mahasiswa' ? '<input type="checkbox" id="select-all" class="form-check-input">' : '')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false)
                ->width(40)
                ->addClass('text-center align-middle'),
            Column::make('DT_RowIndex')
                ->title('NO'),
            Column::make('name')
                ->title('Nama'),
            Column::make('id_pendaftar')
                ->title('ID Pendaftar'),
            Column::make('nim')
                ->title('NIM')
                ->defaultContent('-'),
            Column::make('email')
                ->title('Email'),
            Column::make('no_wa')
                ->title('No. WhatsApp'),
            Column::make('fakultas')
                ->title('Fakultas'),
            Column::make('program_studi')
                ->title('Program Studi'),
            Column::make('role')
                ->title('Role'),
            Column::make('is_active')
                ->title('status'),
        ];

        if (Auth::user()->role != 'mahasiswa') {
            $columns[] = Column::computed('action')
                ->title('AKSI')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center');
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
