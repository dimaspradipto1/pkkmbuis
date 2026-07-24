<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiKewirausahaanDataTable;
use App\Http\Requests\EvaluasiKewirausahaanRequest;
use App\Models\EvaluasiKewirausahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiKewirausahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiKewirausahaanDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiKewirausahaan::where('user_id', Auth::id())->first();
            $questions = EvaluasiKewirausahaan::questions();

            if ($evaluasi) {
                return view('pages.evaluasikewirausahaan.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasikewirausahaan.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasikewirausahaan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiKewirausahaan::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasikewirausahaan.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiKewirausahaan::questions();
        return view('pages.evaluasikewirausahaan.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiKewirausahaanRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiKewirausahaan::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Kewirausahaan berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasikewirausahaan.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiKewirausahaan::create($data);

        Alert::success('Berhasil', 'Evaluasi Kewirausahaan berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasikewirausahaan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiKewirausahaan::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiKewirausahaan::questions();
        return view('pages.evaluasikewirausahaan.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiKewirausahaan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiKewirausahaan::questions();
        return view('pages.evaluasikewirausahaan.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiKewirausahaanRequest $request, $id)
    {
        $evaluasi = EvaluasiKewirausahaan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Kewirausahaan berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasikewirausahaan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiKewirausahaan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Kewirausahaan berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasikewirausahaan.index');
    }
}
