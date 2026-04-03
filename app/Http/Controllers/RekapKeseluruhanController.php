<?php

namespace App\Http\Controllers;

use App\DataTables\RekapKeseluruhanDataTable;
use App\DataTables\RekapNilaiAkhirDataTable;
use Illuminate\Http\Request;

class RekapKeseluruhanController extends Controller
{
    public function index(RekapKeseluruhanDataTable $dtDetailed, RekapNilaiAkhirDataTable $dtFinal)
    {
        if (request()->ajax()) {
            if (request()->get('table') === 'rekapnilaiakhir') {
                return $dtFinal->ajax();
            }
            if (request()->get('table') === 'rekapdetail') {
                return $dtDetailed->ajax();
            }
            // Fallback
            return $dtDetailed->ajax();
        }

        return view('pages.rekapkeseluruhan.index', [
            'dtDetailed' => $dtDetailed->html(),
            'dtFinal' => $dtFinal->html(),
        ]);
    }
}
