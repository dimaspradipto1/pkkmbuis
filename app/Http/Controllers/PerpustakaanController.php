<?php

namespace App\Http\Controllers;

use App\DataTables\PerpustakaanDataTable;
use App\Http\Requests\PerpustakaanRequest;
use App\Models\Perpustakaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PerpustakaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PerpustakaanDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = Perpustakaan::where('user_id', Auth::id())->first();
            $questions = Perpustakaan::questions();

            if ($evaluasi) {
                return view('pages.evaluasiperpustakaan.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasiperpustakaan.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasiperpustakaan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = Perpustakaan::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasiperpustakaan.completed', compact('evaluasi'));
            }
        }
        $questions = Perpustakaan::questions();
        return view('pages.evaluasiperpustakaan.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PerpustakaanRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = Perpustakaan::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Perpustakaan berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasiperpustakaan.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        Perpustakaan::create($data);

        Alert::success('Berhasil', 'Evaluasi Perpustakaan berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasiperpustakaan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = Perpustakaan::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = Perpustakaan::questions();
        return view('pages.evaluasiperpustakaan.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = Perpustakaan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = Perpustakaan::questions();
        return view('pages.evaluasiperpustakaan.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PerpustakaanRequest $request, $id)
    {
        $evaluasi = Perpustakaan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Perpustakaan berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasiperpustakaan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = Perpustakaan::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Perpustakaan berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasiperpustakaan.index');
    }
}
