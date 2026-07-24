<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiPelayanansistemAdministrasiKeuanganDataTable;
use App\Http\Requests\EvaluasiPelayanansistemAdministrasiKeuanganRequest;
use App\Models\EvaluasiPelayanansistemAdministrasiKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiPelayanansistemAdministrasiKeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiPelayanansistemAdministrasiKeuanganDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPelayanansistemAdministrasiKeuangan::where('user_id', Auth::id())->first();
            $questions = EvaluasiPelayanansistemAdministrasiKeuangan::questions();

            if ($evaluasi) {
                return view('pages.evaluasipelayanansistemadministrasikeuangan.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasipelayanansistemadministrasikeuangan.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasipelayanansistemadministrasikeuangan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPelayanansistemAdministrasiKeuangan::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasipelayanansistemadministrasikeuangan.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiPelayanansistemAdministrasiKeuangan::questions();
        return view('pages.evaluasipelayanansistemadministrasikeuangan.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiPelayanansistemAdministrasiKeuanganRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiPelayanansistemAdministrasiKeuangan::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Pelayanan Sistem Administrasi Keuangan berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasipelayanansistemadministrasikeuangan.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiPelayanansistemAdministrasiKeuangan::create($data);

        Alert::success('Berhasil', 'Evaluasi Pelayanan Sistem Administrasi Keuangan berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipelayanansistemadministrasikeuangan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiPelayanansistemAdministrasiKeuangan::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPelayanansistemAdministrasiKeuangan::questions();
        return view('pages.evaluasipelayanansistemadministrasikeuangan.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiPelayanansistemAdministrasiKeuangan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPelayanansistemAdministrasiKeuangan::questions();
        return view('pages.evaluasipelayanansistemadministrasikeuangan.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiPelayanansistemAdministrasiKeuanganRequest $request, $id)
    {
        $evaluasi = EvaluasiPelayanansistemAdministrasiKeuangan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Pelayanan Sistem Administrasi Keuangan berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipelayanansistemadministrasikeuangan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiPelayanansistemAdministrasiKeuangan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Pelayanan Sistem Administrasi Keuangan berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasipelayanansistemadministrasikeuangan.index');
    }
}
