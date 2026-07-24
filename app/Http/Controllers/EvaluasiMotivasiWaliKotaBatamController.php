<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiMotivasiWaliKotaBatamDataTable;
use App\Http\Requests\EvaluasiMotivasiWaliKotaBatamRequest;
use App\Models\EvaluasiMotivasiWaliKotaBatam;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiMotivasiWaliKotaBatamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiMotivasiWaliKotaBatamDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiMotivasiWaliKotaBatam::where('user_id', Auth::id())->first();
            $questions = EvaluasiMotivasiWaliKotaBatam::questions();

            if ($evaluasi) {
                return view('pages.evaluasimotivasiwalikotabatam.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasimotivasiwalikotabatam.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasimotivasiwalikotabatam.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiMotivasiWaliKotaBatam::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasimotivasiwalikotabatam.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiMotivasiWaliKotaBatam::questions();
        return view('pages.evaluasimotivasiwalikotabatam.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiMotivasiWaliKotaBatamRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiMotivasiWaliKotaBatam::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Motivasi Wali Kota Batam berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasimotivasiwalikotabatam.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiMotivasiWaliKotaBatam::create($data);

        Alert::success('Berhasil', 'Evaluasi Motivasi Wali Kota Batam berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasimotivasiwalikotabatam.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiMotivasiWaliKotaBatam::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiMotivasiWaliKotaBatam::questions();
        return view('pages.evaluasimotivasiwalikotabatam.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiMotivasiWaliKotaBatam::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiMotivasiWaliKotaBatam::questions();
        return view('pages.evaluasimotivasiwalikotabatam.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiMotivasiWaliKotaBatamRequest $request, $id)
    {
        $evaluasi = EvaluasiMotivasiWaliKotaBatam::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Motivasi Wali Kota Batam berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasimotivasiwalikotabatam.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiMotivasiWaliKotaBatam::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Motivasi Wali Kota Batam berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasimotivasiwalikotabatam.index');
    }
}
