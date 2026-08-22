<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiFikesDataTable;
use App\Http\Requests\EvaluasiFikesRequest;
use App\Models\EvaluasiFikes;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiFikesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiFikesDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiFikes::where('user_id', Auth::id())->first();
            $questions = EvaluasiFikes::questions();
            $saranFields = EvaluasiFikes::saranFields();

            if ($evaluasi) {
                return view('pages.evaluasifikes.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasifikes.create', compact('questions', 'saranFields'));
            }
        }

        return $dataTable->render('pages.evaluasifikes.index', $this->getEvaluasiStats(EvaluasiFikes::class));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiFikes::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasifikes.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiFikes::questions();
        $saranFields = EvaluasiFikes::saranFields();
        return view('pages.evaluasifikes.create', compact('questions', 'saranFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiFikesRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiFikes::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi FIKes berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasifikes.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiFikes::create($data);

        Alert::success('Berhasil', 'Evaluasi FIKes berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasifikes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiFikes::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiFikes::questions();
        $saranFields = EvaluasiFikes::saranFields();
        return view('pages.evaluasifikes.show', compact('evaluasi', 'questions', 'saranFields'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiFikes::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiFikes::questions();
        $saranFields = EvaluasiFikes::saranFields();
        return view('pages.evaluasifikes.edit', compact('evaluasi', 'questions', 'saranFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiFikesRequest $request, $id)
    {
        $evaluasi = EvaluasiFikes::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi FIKes berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasifikes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiFikes::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi FIKes berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasifikes.index');
    }
}
