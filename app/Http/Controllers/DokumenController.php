<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\DataTables\DokumenDataTable;
use App\Http\Requests\DokumenRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DokumenController extends Controller
{
    public function index(DokumenDataTable $dataTable)
    {
        return $dataTable->render('pages.dokumen.index');
    }

    public function create()
    {
        if (Auth::user()->role != 'admin') abort(403);
        return view('pages.dokumen.create');
    }

    public function store(DokumenRequest $request)
    {
        if (Auth::user()->role != 'admin') abort(403);
        Dokumen::create($request->validated());
        Alert::success('Berhasil', 'Dokumen berhasil ditambahkan.')->toToast()->autoClose(3000);
        return redirect()->route('dokumen.index');
    }

    public function show(Dokumen $dokuman)
    {
        //
    }

    public function edit(Dokumen $dokuman)
    {
        if (Auth::user()->role != 'admin') abort(403);
        return view('pages.dokumen.edit', compact('dokuman'));
    }

    public function update(DokumenRequest $request, Dokumen $dokuman)
    {
        if (Auth::user()->role != 'admin') abort(403);
        $dokuman->update($request->validated());
        Alert::success('Berhasil', 'Dokumen berhasil diperbarui.')->toToast()->autoClose(3000);
        return redirect()->route('dokumen.index');
    }

    public function destroy(Dokumen $dokuman)
    {
        if (Auth::user()->role != 'admin') abort(403);
        $dokuman->delete();
        Alert::success('Berhasil', 'Dokumen berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('dokumen.index');
    }
}
