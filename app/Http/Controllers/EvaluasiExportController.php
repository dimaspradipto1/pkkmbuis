<?php

namespace App\Http\Controllers;

use App\Exports\EvaluasiExport;
use App\Models\EvaluasiMenu;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class EvaluasiExportController extends Controller
{
    protected static array $menuModelMap = [
        1  => \App\Models\EvaluasiPelayananKemahasiswaanPusatPrestasi::class,
        2  => \App\Models\EvaluasiPelayanansistemAkademik::class,
        3  => \App\Models\EvaluasiPelayanansistemAdministrasiKeuangan::class,
        4  => \App\Models\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::class,
        5  => \App\Models\EvaluasiSistemPendidikanTinggidiIndonesia::class,
        6  => \App\Models\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::class,
        7  => \App\Models\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::class,
        8  => \App\Models\Perpustakaan::class,
        9  => \App\Models\EvaluasiIkaUis::class,
        10 => \App\Models\EvaluasiMotivasiGubernurKepulauanRiau::class,
        11 => \App\Models\EvaluasiFikes::class,
        12 => \App\Models\EvaluasiFst::class,
        13 => \App\Models\EvaluasiFeb::class,
    ];

    public function export($id)
    {
        $menu = EvaluasiMenu::findOrFail($id);
        $modelClass = $menu->model_class;

        if (!$modelClass) {
            abort(404, 'Model Evaluasi tidak ditemukan.');
        }

        $fileName = 'Evaluasi_' . Str::slug($menu->nama, '_') . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new EvaluasiExport($modelClass, $menu->id, 'LAPORAN EVALUASI: ' . $menu->nama . ' - PKKMB UIS 2026'), $fileName);
    }
}
