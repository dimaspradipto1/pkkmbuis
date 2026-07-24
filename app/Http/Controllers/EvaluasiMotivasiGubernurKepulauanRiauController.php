<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiMotivasiGubernurKepulauanRiauDataTable;
use App\Http\Requests\EvaluasiMotivasiGubernurKepulauanRiauRequest;
use App\Models\EvaluasiMotivasiGubernurKepulauanRiau;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiMotivasiGubernurKepulauanRiauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiMotivasiGubernurKepulauanRiauDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiMotivasiGubernurKepulauanRiau::where('user_id', Auth::id())->first();
            $questions = EvaluasiMotivasiGubernurKepulauanRiau::questions();

            if ($evaluasi) {
                return view('pages.evaluasimotivasigubernurkepulauanriau.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasimotivasigubernurkepulauanriau.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasimotivasigubernurkepulauanriau.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiMotivasiGubernurKepulauanRiau::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasimotivasigubernurkepulauanriau.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiMotivasiGubernurKepulauanRiau::questions();
        return view('pages.evaluasimotivasigubernurkepulauanriau.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiMotivasiGubernurKepulauanRiauRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiMotivasiGubernurKepulauanRiau::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Motivasi Gubernur Kepulauan Riau berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasimotivasigubernurkepulauanriau.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiMotivasiGubernurKepulauanRiau::create($data);

        Alert::success('Berhasil', 'Evaluasi Motivasi Gubernur Kepulauan Riau berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasimotivasigubernurkepulauanriau.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiMotivasiGubernurKepulauanRiau::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiMotivasiGubernurKepulauanRiau::questions();
        return view('pages.evaluasimotivasigubernurkepulauanriau.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiMotivasiGubernurKepulauanRiau::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiMotivasiGubernurKepulauanRiau::questions();
        return view('pages.evaluasimotivasigubernurkepulauanriau.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiMotivasiGubernurKepulauanRiauRequest $request, $id)
    {
        $evaluasi = EvaluasiMotivasiGubernurKepulauanRiau::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Motivasi Gubernur Kepulauan Riau berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasimotivasigubernurkepulauanriau.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiMotivasiGubernurKepulauanRiau::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Motivasi Gubernur Kepulauan Riau berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasimotivasigubernurkepulauanriau.index');
    }
}
