<?php

namespace App\Http\Controllers;

use App\DataTables\LpjDataTable;
use App\Http\Requests\LpjRequest;
use App\Models\Lpj;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LpjController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LpjDataTable $dataTable)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        if (Auth::user()->role == 'timevaluasi') {
            return redirect()->route('lpj.create');
        }

        return $dataTable->render('pages.lpj.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        return view('pages.lpj.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LpjRequest $request)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        Lpj::create($request->validated());

        Alert::success('LPJ berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('lpj.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $lpj = Lpj::findOrFail($id);
        return view('pages.lpj.edit', compact('lpj'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LpjRequest $request, string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $lpj = Lpj::findOrFail($id);
        $lpj->update($request->validated());

        Alert::success('LPJ berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('lpj.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $lpj = Lpj::findOrFail($id);
        $lpj->delete();

        Alert::success('LPJ berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('lpj.index');
    }
}
