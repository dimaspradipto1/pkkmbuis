<?php

namespace App\Http\Controllers;

use App\Models\MateriModul;
use App\Models\ModulPostTest;
use App\Models\HasilTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ModulPostTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = (int) $request->query('modul', 1); // Default to modul 1
        $questions_pre  = collect();
        $questions_post = collect();
        $tugas_kelompok = null;

        // Map module ID to soal models
        switch ($id) {
            case 1:
                $questions_pre  = \App\Models\SoalPretestPertama::all();
                $questions_post = \App\Models\SoalPostTestPertama::all();
                break;
            case 2:
                $questions_pre  = \App\Models\SoalPretestKedua::all();
                $questions_post = \App\Models\SoalPostTestKedua::all();
                break;
            case 3:
                $questions_pre  = \App\Models\SoalPretestKetiga::all();
                $questions_post = \App\Models\SoalPostTestKetiga::all();
                break;
            case 4:
                $questions_pre  = \App\Models\SoalPretestKeempat::all();
                $questions_post = \App\Models\SoalPostTestKeempat::all();
                break;
            case 5:
                $questions_pre  = collect();
                $questions_post = collect();
                $tugas_kelompok = \App\Models\SoalTugasKelompok::where('user_id', Auth::id())->first();
                break;
            default:
                $questions_pre  = \App\Models\SoalPretestPertama::all();
                $questions_post = \App\Models\SoalPostTestPertama::all();
                break;
        }

        // Ambil data materi modul dari database
        $materiModul  = MateriModul::latest()->first();
        $modulKey     = 'modul' . $id;          // e.g. "modul1"
        $modulLinkKey = 'modul' . $id . '_link'; // e.g. "modul1_link"
        $materi_file  = $materiModul ? $materiModul->$modulKey : null;
        $materi_link  = $materiModul ? $materiModul->$modulLinkKey : null;
        $materi_id    = $materiModul ? $materiModul->id : null;

        $modul_title = "MODUL $id";

        $modules = [
            ['id' => 1, 'title' => 'Materi 1'],
            ['id' => 2, 'title' => 'Materi 2'],
            ['id' => 3, 'title' => 'Materi 3'],
            ['id' => 4, 'title' => 'Materi 4'],
            ['id' => 5, 'title' => 'Materi 5'],
        ];

        // Ambil hasil test user jika ada
        $hasil_pre  = HasilTest::where('user_id', Auth::id())->where('modul', $id)->where('type', 'pretest')->first();
        $hasil_post = HasilTest::where('user_id', Auth::id())->where('modul', $id)->where('type', 'posttest')->first();
        $posttest_is_active = \App\Models\PostTestSetting::isActive($id);

        return view('pages.modulposttest.index', compact(
            'questions_pre',
            'questions_post',
            'modules',
            'id',
            'modul_title',
            'materi_file',
            'materi_link',
            'materi_id',
            'hasil_pre',
            'hasil_post',
            'tugas_kelompok',
            'posttest_is_active'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $modul_id = (int) $request->input('modul_id');
        $type     = $request->input('type'); // 'pretest' or 'posttest'

        if ($modul_id == 5) {
            Alert::error('Salah Modul', 'Modul 5 tidak memiliki Pre/Post Test.');
            return redirect()->back();
        }
        
        // 1. Cek apakah sudah pernah mengerjakan tipe tes ini di modul ini
        $existing = HasilTest::where('user_id', Auth::id())
            ->where('modul', $modul_id)
            ->where('type', $type)
            ->first();
            
        if ($existing) {
            Alert::warning('Peringatan', 'Anda sudah mengerjakan ' . $type . ' di modul ini dan tidak dapat mengulanginya.');
            return redirect()->back();
        }

        // 2. Tambahan: Cek apakah sudah mengerjakan PRE TEST sebelum boleh mengerjakan POST TEST
        if ($type == 'posttest') {
            if (Auth::user()->role == 'mahasiswa' && !\App\Models\PostTestSetting::isActive($modul_id)) {
                Alert::error('Akses Ditolak', 'Sesi Post Test untuk Modul ini sedang tidak aktif / ditutup oleh Admin.');
                return redirect()->back()->with('active_tab', 'posttest');
            }

            $hasPreTest = HasilTest::where('user_id', Auth::id())
                ->where('modul', $modul_id)
                ->where('type', 'pretest')
                ->exists();
            
            if (!$hasPreTest) {
                Alert::error('Akses Ditolak', 'Silakan selesaikan Pre Test terlebih dahulu sebelum mengerjakan Post Test.');
                return redirect()->back()->with('active_tab', 'pretest');
            }
        }

        // 3. Ambil soal berdasarkan modul dan tipe
        $questions = collect();
        $prefix = ($type == 'pretest') ? 'pre_soal_' : 'post_soal_';

        switch ($modul_id) {
            case 1:
                $questions = ($type == 'pretest') ? \App\Models\SoalPretestPertama::all() : \App\Models\SoalPostTestPertama::all();
                break;
            case 2:
                $questions = ($type == 'pretest') ? \App\Models\SoalPretestKedua::all() : \App\Models\SoalPostTestKedua::all();
                break;
            case 3:
                $questions = ($type == 'pretest') ? \App\Models\SoalPretestKetiga::all() : \App\Models\SoalPostTestKetiga::all();
                break;
            case 4:
                $questions = ($type == 'pretest') ? \App\Models\SoalPretestKeempat::all() : \App\Models\SoalPostTestKeempat::all();
                break;
        }

        if ($questions->isEmpty()) {
            Alert::error('Gagal', 'Soal tidak ditemukan untuk modul ini.');
            return redirect()->back();
        }

        // 4. Hitung skor
        $jumlah_benar = 0;
        $total_soal   = $questions->count();
        $user_jawaban = [];

        foreach ($questions as $q) {
            $jawaban_user = $request->input($prefix . $q->id);
            $user_jawaban[$q->id] = $jawaban_user;
            
            if ($jawaban_user == $q->jawaban) {
                $jumlah_benar++;
            }
        }

        $skor = $jumlah_benar * 10;

        // 5. Simpan ke database
        HasilTest::create([
            'user_id'      => Auth::id(),
            'modul'        => $modul_id,
            'type'         => $type,
            'skor'         => $skor,
            'jumlah_benar' => $jumlah_benar,
            'total_soal'   => $total_soal,
            'jawaban'      => json_encode($user_jawaban),
        ]);

        if ($type == 'pretest') {
            Alert::success('Berhasil!', 'Pre Test berhasil diselesaikan! Materi Modul ' . $modul_id . ' kini telah terbuka. Skor Anda: ' . $skor)->autoClose(5000);
            return redirect()->back()->with('active_tab', 'materi');
        }

        Alert::success('Berhasil!', 'Jawaban ' . $type . ' telah disimpan. Skor Anda: ' . $skor)->autoClose(5000);
        return redirect()->back()->with('active_tab', 'posttest');
    }

    public function uploadTugasKelompok(Request $request)
    {
        $request->validate([
            'link_tugas' => 'required|url|max:2048',
        ]);

        \App\Models\SoalTugasKelompok::updateOrCreate(
            ['user_id' => Auth::id()],
            ['link_tugas' => $request->input('link_tugas')]
        );

        Alert::success('Berhasil!', 'Link tugas kelompok telah berhasil disimpan.')->autoClose(3000);
        return redirect()->back()->with('active_tab', 'tugas');
    }

    public function toggleActive($modul)
    {
        if (Auth::user()->role == 'mahasiswa') {
            abort(403, 'Akses tidak diizinkan.');
        }

        $modulId = (int) $modul;
        if ($modulId < 1 || $modulId > 4) {
            Alert::error('Gagal', 'Modul tidak valid.');
            return redirect()->back();
        }

        $isActive = \App\Models\PostTestSetting::toggle($modulId);
        $statusStr = $isActive ? 'DIBUKA (Aktif)' : 'DITUTUP (Nonaktif)';

        Alert::success('Berhasil', "Sesi Post Test Modul {$modulId} berhasil {$statusStr}.")->toToast()->autoClose(3000);

        return redirect()->back()->with('active_tab', 'posttest');
    }
}
