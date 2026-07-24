<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiPencarianBakatMahasiswaDataTable;
use App\Http\Requests\EvaluasiPencarianBakatMahasiswaRequest;
use App\Models\EvaluasiPencarianBakatMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiPencarianBakatMahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiPencarianBakatMahasiswaDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPencarianBakatMahasiswa::where('user_id', Auth::id())->first();
            $questions = EvaluasiPencarianBakatMahasiswa::questions();

            if ($evaluasi) {
                return view('pages.evaluasipencarianbakatmahasiswa.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasipencarianbakatmahasiswa.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasipencarianbakatmahasiswa.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPencarianBakatMahasiswa::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasipencarianbakatmahasiswa.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiPencarianBakatMahasiswa::questions();
        return view('pages.evaluasipencarianbakatmahasiswa.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiPencarianBakatMahasiswaRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiPencarianBakatMahasiswa::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Pencarian Bakat Mahasiswa berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasipencarianbakatmahasiswa.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiPencarianBakatMahasiswa::create($data);

        Alert::success('Berhasil', 'Evaluasi Pencarian Bakat Mahasiswa berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipencarianbakatmahasiswa.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiPencarianBakatMahasiswa::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPencarianBakatMahasiswa::questions();
        return view('pages.evaluasipencarianbakatmahasiswa.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiPencarianBakatMahasiswa::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPencarianBakatMahasiswa::questions();
        return view('pages.evaluasipencarianbakatmahasiswa.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiPencarianBakatMahasiswaRequest $request, $id)
    {
        $evaluasi = EvaluasiPencarianBakatMahasiswa::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Pencarian Bakat Mahasiswa berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipencarianbakatmahasiswa.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiPencarianBakatMahasiswa::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Pencarian Bakat Mahasiswa berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasipencarianbakatmahasiswa.index');
    }
}
