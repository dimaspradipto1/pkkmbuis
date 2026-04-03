<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\HasilTest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class HasilTestRekapDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('m1_pre', function($row) { return $row->m1_pre ?: '-'; })
            ->addColumn('m1_post', function($row) { return $row->m1_post ?: '-'; })
            ->addColumn('m2_pre', function($row) { return $row->m2_pre ?: '-'; })
            ->addColumn('m2_post', function($row) { return $row->m2_post ?: '-'; })
            ->addColumn('m3_pre', function($row) { return $row->m3_pre ?: '-'; })
            ->addColumn('m3_post', function($row) { return $row->m3_post ?: '-'; })
            ->addColumn('m4_pre', function($row) { return $row->m4_pre ?: '-'; })
            ->addColumn('m4_post', function($row) { return $row->m4_post ?: '-'; })
            ->addColumn('total', function($row) {
                $total = (int)($row->m1_pre ?? 0) + (int)($row->m1_post ?? 0) + 
                         (int)($row->m2_pre ?? 0) + (int)($row->m2_post ?? 0) + 
                         (int)($row->m3_pre ?? 0) + (int)($row->m3_post ?? 0) + 
                         (int)($row->m4_pre ?? 0) + (int)($row->m4_post ?? 0);
                return $total ?: '-';
            })
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->join('hasil_tests', 'users.id', '=', 'hasil_tests.user_id')
            ->select([
                'users.id',
                'users.name',
                DB::raw('MAX(CASE WHEN hasil_tests.modul = 1 AND hasil_tests.type = "pretest" THEN hasil_tests.skor ELSE 0 END) as m1_pre'),
                DB::raw('MAX(CASE WHEN hasil_tests.modul = 1 AND hasil_tests.type = "posttest" THEN hasil_tests.skor ELSE 0 END) as m1_post'),
                DB::raw('MAX(CASE WHEN hasil_tests.modul = 2 AND hasil_tests.type = "pretest" THEN hasil_tests.skor ELSE 0 END) as m2_pre'),
                DB::raw('MAX(CASE WHEN hasil_tests.modul = 2 AND hasil_tests.type = "posttest" THEN hasil_tests.skor ELSE 0 END) as m2_post'),
                DB::raw('MAX(CASE WHEN hasil_tests.modul = 3 AND hasil_tests.type = "pretest" THEN hasil_tests.skor ELSE 0 END) as m3_pre'),
                DB::raw('MAX(CASE WHEN hasil_tests.modul = 3 AND hasil_tests.type = "posttest" THEN hasil_tests.skor ELSE 0 END) as m3_post'),
                DB::raw('MAX(CASE WHEN hasil_tests.modul = 4 AND hasil_tests.type = "pretest" THEN hasil_tests.skor ELSE 0 END) as m4_pre'),
                DB::raw('MAX(CASE WHEN hasil_tests.modul = 4 AND hasil_tests.type = "posttest" THEN hasil_tests.skor ELSE 0 END) as m4_post')
            ])
            ->groupBy('users.id', 'users.name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('hasiltest-rekap-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        'excel', 'csv', 'pdf', 'print', 'reset', 'reload'
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->orderable(false)->searchable(false),
            Column::make('name')->title('Nama Mahasiswa'),
            Column::make('m1_pre')->title('M1 Pre')->orderable(false),
            Column::make('m1_post')->title('M1 Post')->orderable(false),
            Column::make('m2_pre')->title('M2 Pre')->orderable(false),
            Column::make('m2_post')->title('M2 Post')->orderable(false),
            Column::make('m3_pre')->title('M3 Pre')->orderable(false),
            Column::make('m3_post')->title('M3 Post')->orderable(false),
            Column::make('m4_pre')->title('M4 Pre')->orderable(false),
            Column::make('m4_post')->title('M4 Post')->orderable(false),
            Column::computed('total')->title('Total')->addClass('fw-bold'),
        ];
    }
}
