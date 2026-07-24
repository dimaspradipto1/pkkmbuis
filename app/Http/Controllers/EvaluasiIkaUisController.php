<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiIkaUisDataTable;
use App\Http\Requests\EvaluasiIkaUisRequest;
use App\Models\EvaluasiIkaUis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiIkaUisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiIkaUisDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiIkaUis::where('user_id', Auth::id())->first();
            $questions = EvaluasiIkaUis::questions();

            if ($evaluasi) {
                return view('pages.evaluasiikauis.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasiikauis.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasiikauis.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiIkaUis::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasiikauis.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiIkaUis::questions();
        return view('pages.evaluasiikauis.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiIkaUisRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiIkaUis::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi IKA UIS berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasiikauis.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiIkaUis::create($data);

        Alert::success('Berhasil', 'Evaluasi IKA UIS berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasiikauis.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiIkaUis::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiIkaUis::questions();
        return view('pages.evaluasiikauis.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiIkaUis::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiIkaUis::questions();
        return view('pages.evaluasiikauis.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiIkaUisRequest $request, $id)
    {
        $evaluasi = EvaluasiIkaUis::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi IKA UIS berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasiikauis.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiIkaUis::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi IKA UIS berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasiikauis.index');
    }
}
