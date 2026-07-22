<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiPengenalanWawasanIbnuSinaDataTable;
use App\Http\Requests\EvaluasiPengenalanWawasanIbnuSinaRequest;
use App\Models\EvaluasiPengenalanWawasanIbnuSina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiPengenalanWawasanIbnuSinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiPengenalanWawasanIbnuSinaDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPengenalanWawasanIbnuSina::where('user_id', Auth::id())->first();
            $questions = EvaluasiPengenalanWawasanIbnuSina::questions();

            if ($evaluasi) {
                return view('pages.evaluasipengenalanwawasanibnusina.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasipengenalanwawasanibnusina.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasipengenalanwawasanibnusina.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPengenalanWawasanIbnuSina::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasipengenalanwawasanibnusina.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiPengenalanWawasanIbnuSina::questions();
        return view('pages.evaluasipengenalanwawasanibnusina.create', compact('questions'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiPengenalanWawasanIbnuSinaRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiPengenalanWawasanIbnuSina::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi penyampaian materi berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasipengenalanwawasanibnusina.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiPengenalanWawasanIbnuSina::create($data);

        Alert::success('Berhasil', 'Evaluasi penyampaian materi berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipengenalanwawasanibnusina.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiPengenalanWawasanIbnuSina::with('user.kelompok')->findOrFail($id);
        $questions = EvaluasiPengenalanWawasanIbnuSina::questions();
        return view('pages.evaluasipengenalanwawasanibnusina.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiPengenalanWawasanIbnuSina::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $questions = EvaluasiPengenalanWawasanIbnuSina::questions();
        return view('pages.evaluasipengenalanwawasanibnusina.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiPengenalanWawasanIbnuSinaRequest $request, $id)
    {
        $evaluasi = EvaluasiPengenalanWawasanIbnuSina::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi penyampaian materi berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipengenalanwawasanibnusina.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiPengenalanWawasanIbnuSina::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi penyampaian materi berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasipengenalanwawasanibnusina.index');
    }
}
