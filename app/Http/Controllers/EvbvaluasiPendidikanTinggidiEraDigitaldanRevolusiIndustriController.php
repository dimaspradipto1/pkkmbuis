<?php

namespace App\Http\Controllers;

use App\DataTables\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustriDataTable;
use App\Http\Requests\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustriRequest;
use App\Models\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustriDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::where('user_id', Auth::id())->first();
            $questions = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::questions();

            if ($evaluasi) {
                return view('pages.evaluasipendidikantinggieradigital.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasipendidikantinggieradigital.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasipendidikantinggieradigital.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasipendidikantinggieradigital.completed', compact('evaluasi'));
            }
        }
        $questions = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::questions();
        return view('pages.evaluasipendidikantinggieradigital.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustriRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Pendidikan Tinggi di Era Digital dan Revolusi Industri berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasipendidikantinggieradigital.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::create($data);

        Alert::success('Berhasil', 'Evaluasi Pendidikan Tinggi di Era Digital dan Revolusi Industri berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipendidikantinggieradigital.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::questions();
        return view('pages.evaluasipendidikantinggieradigital.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::questions();
        return view('pages.evaluasipendidikantinggieradigital.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustriRequest $request, $id)
    {
        $evaluasi = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Pendidikan Tinggi di Era Digital dan Revolusi Industri berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipendidikantinggieradigital.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Pendidikan Tinggi di Era Digital dan Revolusi Industri berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasipendidikantinggieradigital.index');
    }
}
