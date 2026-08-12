<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiMenu;
use App\Models\EvaluasiQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiMenuController extends Controller
{
    protected static array $menuModelMap = [
        1  => \App\Models\EvaluasiPengenalanWawasanIbnuSina::class,
        2  => \App\Models\EvaluasiPelayananKemahasiswaanPusatPrestasi::class,
        3  => \App\Models\EvaluasiPelayanansistemAkademik::class,
        4  => \App\Models\EvaluasiPelayanansistemAdministrasiKeuangan::class,
        5  => \App\Models\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::class,
        6  => \App\Models\EvaluasiSistemPendidikanTinggidiIndonesia::class,
        7  => \App\Models\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::class,
        8  => \App\Models\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::class,
        9  => \App\Models\Perpustakaan::class,
        10 => \App\Models\EvaluasiIkaUis::class,
        11 => \App\Models\EvaluasiKewirausahaan::class,
        12 => \App\Models\EvaluasiPencarianBakatMahasiswa::class,
        13 => \App\Models\EvaluasiMotivasiWaliKotaBatam::class,
        14 => \App\Models\EvaluasiMotivasiGubernurKepulauanRiau::class,
        15 => \App\Models\EvaluasiFikes::class,
        16 => \App\Models\EvaluasiFst::class,
        17 => \App\Models\EvaluasiFeb::class,
    ];

    public function index()
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $menus = EvaluasiMenu::orderBy('nomor')->get();
        return view('pages.evaluasimenu.index', compact('menus'));
    }

    public function toggle($id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $menu = EvaluasiMenu::findOrFail($id);
        $menu->is_active = !$menu->is_active;
        $menu->save();

        $status = $menu->is_active ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Berhasil', "Menu '{$menu->nama}' berhasil {$status}.")->toToast()->autoClose(3000);

        return redirect()->back();
    }

    public function questions($id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $menu = EvaluasiMenu::findOrFail($id);
        $modelClass = static::$menuModelMap[$menu->nomor] ?? null;

        if (!$modelClass || !method_exists($modelClass, 'questions')) {
            Alert::error('Gagal', 'Model evaluasi tidak ditemukan.')->toToast()->autoClose(3000);
            return redirect()->back();
        }

        $questions = $modelClass::questions();

        if (method_exists($modelClass, 'saranFields')) {
            $defaultSaran = $modelClass::saranFields();
        } else {
            $defaultSaran = ['s1' => 'Berikan saran dan masukan terhadap pemateri'];
        }

        $saranQuestions = EvaluasiQuestion::getSaranQuestions($menu->id, $defaultSaran);

        return view('pages.evaluasimenu.questions', compact('menu', 'questions', 'saranQuestions'));
    }

    public function updateQuestions(Request $request, $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $menu = EvaluasiMenu::findOrFail($id);
        $questionsInput = $request->input('questions', []);
        $saranInput = $request->input('saran_questions', []);

        // Delete old question configuration for this menu
        EvaluasiQuestion::where('evaluasi_menu_id', $menu->id)->delete();

        // Save rating questions (q1, q2, ...)
        $qIndex = 1;
        foreach ($questionsInput as $pertanyaan) {
            $text = trim($pertanyaan);
            if (!empty($text)) {
                EvaluasiQuestion::create([
                    'evaluasi_menu_id' => $menu->id,
                    'question_key'     => 'q' . $qIndex,
                    'pertanyaan'       => $text,
                ]);
                $qIndex++;
            }
        }

        // Save essay/saran questions (s1, s2, ...)
        $sIndex = 1;
        foreach ($saranInput as $pertanyaan) {
            $text = trim($pertanyaan);
            if (!empty($text)) {
                EvaluasiQuestion::create([
                    'evaluasi_menu_id' => $menu->id,
                    'question_key'     => 's' . $sIndex,
                    'pertanyaan'       => $text,
                ]);
                $sIndex++;
            }
        }

        Alert::success('Berhasil', "Daftar soal evaluasi '{$menu->nama}' berhasil diperbarui.")->toToast()->autoClose(3000);

        return redirect()->route('evaluasimenu.index');
    }

    public function resetQuestions($id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $menu = EvaluasiMenu::findOrFail($id);
        EvaluasiQuestion::where('evaluasi_menu_id', $menu->id)->delete();

        Alert::success('Berhasil', "Pertanyaan '{$menu->nama}' telah dikembalikan ke default.")->toToast()->autoClose(3000);

        return redirect()->back();
    }
}
