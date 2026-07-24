<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiPelayananKemahasiswaanPusatPrestasiDataTable;
use App\Http\Requests\EvaluasiPelayananKemahasiswaanPusatPrestasiRequest;
use App\Models\EvaluasiPelayananKemahasiswaanPusatPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiPelayananKemahasiswaanPusatPrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiPelayananKemahasiswaanPusatPrestasiDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPelayananKemahasiswaanPusatPrestasi::where('user_id', Auth::id())->first();
            $questions = EvaluasiPelayananKemahasiswaanPusatPrestasi::questions();

            if ($evaluasi) {
                return view('pages.evaluasipelayanankemahasiswaanpusatprestasi.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasipelayanankemahasiswaanpusatprestasi.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasipelayanankemahasiswaanpusatprestasi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPelayananKemahasiswaanPusatPrestasi::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasipelayanankemahasiswaanpusatprestasi.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiPelayananKemahasiswaanPusatPrestasi::questions();
        return view('pages.evaluasipelayanankemahasiswaanpusatprestasi.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiPelayananKemahasiswaanPusatPrestasiRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiPelayananKemahasiswaanPusatPrestasi::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Pelayanan Kemahasiswaan & Pusat Prestasi berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasipelayanankemahasiswaanpusatprestasi.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiPelayananKemahasiswaanPusatPrestasi::create($data);

        Alert::success('Berhasil', 'Evaluasi Pelayanan Kemahasiswaan & Pusat Prestasi berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipelayanankemahasiswaanpusatprestasi.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiPelayananKemahasiswaanPusatPrestasi::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPelayananKemahasiswaanPusatPrestasi::questions();
        return view('pages.evaluasipelayanankemahasiswaanpusatprestasi.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiPelayananKemahasiswaanPusatPrestasi::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPelayananKemahasiswaanPusatPrestasi::questions();
        return view('pages.evaluasipelayanankemahasiswaanpusatprestasi.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiPelayananKemahasiswaanPusatPrestasiRequest $request, $id)
    {
        $evaluasi = EvaluasiPelayananKemahasiswaanPusatPrestasi::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Pelayanan Kemahasiswaan & Pusat Prestasi berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipelayanankemahasiswaanpusatprestasi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiPelayananKemahasiswaanPusatPrestasi::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Pelayanan Kemahasiswaan & Pusat Prestasi berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasipelayanankemahasiswaanpusatprestasi.index');
    }
}
