<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiFstDataTable;
use App\Http\Requests\EvaluasiFstRequest;
use App\Models\EvaluasiFst;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiFstController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiFstDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiFst::where('user_id', Auth::id())->first();
            $questions = EvaluasiFst::questions();
            $saranFields = EvaluasiFst::saranFields();

            if ($evaluasi) {
                return view('pages.evaluasifst.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasifst.create', compact('questions', 'saranFields'));
            }
        }

        return $dataTable->render('pages.evaluasifst.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiFst::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasifst.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiFst::questions();
        $saranFields = EvaluasiFst::saranFields();
        return view('pages.evaluasifst.create', compact('questions', 'saranFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiFstRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiFst::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi FST berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasifst.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiFst::create($data);

        Alert::success('Berhasil', 'Evaluasi FST berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasifst.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiFst::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiFst::questions();
        $saranFields = EvaluasiFst::saranFields();
        return view('pages.evaluasifst.show', compact('evaluasi', 'questions', 'saranFields'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiFst::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiFst::questions();
        $saranFields = EvaluasiFst::saranFields();
        return view('pages.evaluasifst.edit', compact('evaluasi', 'questions', 'saranFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiFstRequest $request, $id)
    {
        $evaluasi = EvaluasiFst::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi FST berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasifst.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiFst::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi FST berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasifst.index');
    }
}
