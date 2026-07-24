<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkunganDataTable;
use App\Http\Requests\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkunganRequest;
use App\Models\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkunganDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::where('user_id', Auth::id())->first();
            $questions = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::questions();

            if ($evaluasi) {
                return view('pages.evaluasipengenalank3l.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasipengenalank3l.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasipengenalank3l.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasipengenalank3l.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::questions();
        return view('pages.evaluasipengenalank3l.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkunganRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Pengenalan K3L berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasipengenalank3l.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::create($data);

        Alert::success('Berhasil', 'Evaluasi Pengenalan K3L berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipengenalank3l.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::questions();
        return view('pages.evaluasipengenalank3l.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::questions();
        return view('pages.evaluasipengenalank3l.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkunganRequest $request, $id)
    {
        $evaluasi = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Pengenalan K3L berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipengenalank3l.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Pengenalan K3L berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasipengenalank3l.index');
    }
}
