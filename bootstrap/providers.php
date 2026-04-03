<?php
use Maatwebsite\Excel\ExcelServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    Yajra\DataTables\DataTablesServiceProvider::class,
    RealRashid\SweetAlert\SweetAlertServiceProvider::class,
    Yajra\DataTables\ButtonsServiceProvider::class,
    ExcelServiceProvider::class,
];
