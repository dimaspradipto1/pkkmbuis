<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiFebDataTable;
use App\Http\Requests\EvaluasiFebRequest;
use App\Models\EvaluasiFeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiFebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiFebDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $userFaculty = Auth::user()->faculty_code;
            if ($userFaculty && $userFaculty !== 'FEB') {
                Alert::warning('Perhatian', 'Form evaluasi ini khusus untuk mahasiswa Fakultas Ekonomi dan Bisnis (FEB).')->toToast()->autoClose(4000);
                return redirect()->route('dashboard.index');
            }

            $evaluasi = EvaluasiFeb::where('user_id', Auth::id())->first();
            $questions = EvaluasiFeb::questions();
            $saranFields = EvaluasiFeb::saranFields();

            if ($evaluasi) {
                return view('pages.evaluasifeb.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasifeb.create', compact('questions', 'saranFields'));
            }
        }

        return $dataTable->render('pages.evaluasifeb.index', $this->getEvaluasiStats(EvaluasiFeb::class));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $userFaculty = Auth::user()->faculty_code;
            if ($userFaculty && $userFaculty !== 'FEB') {
                Alert::warning('Perhatian', 'Form evaluasi ini khusus untuk mahasiswa Fakultas Ekonomi dan Bisnis (FEB).')->toToast()->autoClose(4000);
                return redirect()->route('dashboard.index');
            }

            $evaluasi = EvaluasiFeb::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasifeb.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiFeb::questions();
        $saranFields = EvaluasiFeb::saranFields();
        return view('pages.evaluasifeb.create', compact('questions', 'saranFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiFebRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $userFaculty = Auth::user()->faculty_code;
            if ($userFaculty && $userFaculty !== 'FEB') {
                Alert::warning('Perhatian', 'Form evaluasi ini khusus untuk mahasiswa Fakultas Ekonomi dan Bisnis (FEB).')->toToast()->autoClose(4000);
                return redirect()->route('dashboard.index');
            }

            $existing = EvaluasiFeb::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi FEB berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasifeb.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiFeb::create($data);

        Alert::success('Berhasil', 'Evaluasi FEB berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasifeb.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiFeb::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiFeb::questions();
        $saranFields = EvaluasiFeb::saranFields();
        return view('pages.evaluasifeb.show', compact('evaluasi', 'questions', 'saranFields'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiFeb::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiFeb::questions();
        $saranFields = EvaluasiFeb::saranFields();
        return view('pages.evaluasifeb.edit', compact('evaluasi', 'questions', 'saranFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiFebRequest $request, $id)
    {
        $evaluasi = EvaluasiFeb::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi FEB berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasifeb.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiFeb::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi FEB berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasifeb.index');
    }

    public function bulkDelete(Request $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:' . (new EvaluasiFeb())->getTable() . ',id',
        ]);

        EvaluasiFeb::whereIn('id', $request->ids)->delete();

        Alert::success('Berhasil', 'Data evaluasi terpilih berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasifeb.index');
    }
}
