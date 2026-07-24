<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiSistemPendidikanTinggidiIndonesiaDataTable;
use App\Http\Requests\EvaluasiSistemPendidikanTinggidiIndonesiaRequest;
use App\Models\EvaluasiSistemPendidikanTinggidiIndonesia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiSistemPendidikanTinggidiIndonesiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiSistemPendidikanTinggidiIndonesiaDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiSistemPendidikanTinggidiIndonesia::where('user_id', Auth::id())->first();
            $questions = EvaluasiSistemPendidikanTinggidiIndonesia::questions();

            if ($evaluasi) {
                return view('pages.evaluasisistempendidikantinggidiindonesia.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasisistempendidikantinggidiindonesia.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasisistempendidikantinggidiindonesia.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiSistemPendidikanTinggidiIndonesia::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasisistempendidikantinggidiindonesia.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiSistemPendidikanTinggidiIndonesia::questions();
        return view('pages.evaluasisistempendidikantinggidiindonesia.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiSistemPendidikanTinggidiIndonesiaRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiSistemPendidikanTinggidiIndonesia::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Sistem Pendidikan Tinggi di Indonesia berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasisistempendidikantinggidiindonesia.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiSistemPendidikanTinggidiIndonesia::create($data);

        Alert::success('Berhasil', 'Evaluasi Sistem Pendidikan Tinggi di Indonesia berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasisistempendidikantinggidiindonesia.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiSistemPendidikanTinggidiIndonesia::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiSistemPendidikanTinggidiIndonesia::questions();
        return view('pages.evaluasisistempendidikantinggidiindonesia.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiSistemPendidikanTinggidiIndonesia::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiSistemPendidikanTinggidiIndonesia::questions();
        return view('pages.evaluasisistempendidikantinggidiindonesia.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiSistemPendidikanTinggidiIndonesiaRequest $request, $id)
    {
        $evaluasi = EvaluasiSistemPendidikanTinggidiIndonesia::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Sistem Pendidikan Tinggi di Indonesia berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasisistempendidikantinggidiindonesia.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiSistemPendidikanTinggidiIndonesia::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Sistem Pendidikan Tinggi di Indonesia berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasisistempendidikantinggidiindonesia.index');
    }
}
