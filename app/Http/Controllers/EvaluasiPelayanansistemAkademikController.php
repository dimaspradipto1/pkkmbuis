<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiPelayanansistemAkademikDataTable;
use App\Http\Requests\EvaluasiPelayanansistemAkademikRequest;
use App\Models\EvaluasiPelayanansistemAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiPelayanansistemAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiPelayanansistemAkademikDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPelayanansistemAkademik::where('user_id', Auth::id())->first();
            $questions = EvaluasiPelayanansistemAkademik::questions();

            if ($evaluasi) {
                return view('pages.evaluasipelayanansistemakademik.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasipelayanansistemakademik.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasipelayanansistemakademik.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiPelayanansistemAkademik::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasipelayanansistemakademik.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiPelayanansistemAkademik::questions();
        return view('pages.evaluasipelayanansistemakademik.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiPelayanansistemAkademikRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiPelayanansistemAkademik::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Pelayanan Sistem Akademik berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasipelayanansistemakademik.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiPelayanansistemAkademik::create($data);

        Alert::success('Berhasil', 'Evaluasi Pelayanan Sistem Akademik berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipelayanansistemakademik.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiPelayanansistemAkademik::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPelayanansistemAkademik::questions();
        return view('pages.evaluasipelayanansistemakademik.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiPelayanansistemAkademik::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiPelayanansistemAkademik::questions();
        return view('pages.evaluasipelayanansistemakademik.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiPelayanansistemAkademikRequest $request, $id)
    {
        $evaluasi = EvaluasiPelayanansistemAkademik::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Pelayanan Sistem Akademik berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasipelayanansistemakademik.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiPelayanansistemAkademik::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Pelayanan Sistem Akademik berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasipelayanansistemakademik.index');
    }
}
