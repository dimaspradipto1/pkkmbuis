<?php

namespace App\Http\Controllers;

use App\DataTables\ObservasiAcaraFstDataTable;
use App\Http\Requests\ObservasiAcaraFstRequest;
use App\Models\ObservasiAcaraFst;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ObservasiAcaraFstController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ObservasiAcaraFstDataTable $dataTable)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        return $dataTable->render('pages.observasiacarafst.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        return view('pages.observasiacarafst.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ObservasiAcaraFstRequest $request)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $data = $request->validated();
        $data['aspek_observasi'] = $this->formatAspekObservasi($data['aspek_observasi'] ?? []);
        $data['link_dokumen'] = $this->formatLinkDokumen($data['link_dokumen'] ?? []);

        ObservasiAcaraFst::create($data);

        Alert::success('Observasi acara berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('observasiacarafst.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $observasiAcaraFst = ObservasiAcaraFst::findOrFail($id);
        return view('pages.observasiacarafst.edit', compact('observasiAcaraFst'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ObservasiAcaraFstRequest $request, string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $observasiAcaraFst = ObservasiAcaraFst::findOrFail($id);

        $data = $request->validated();
        $data['aspek_observasi'] = $this->formatAspekObservasi($data['aspek_observasi'] ?? []);
        $data['link_dokumen'] = $this->formatLinkDokumen($data['link_dokumen'] ?? []);

        $observasiAcaraFst->update($data);

        Alert::success('Observasi acara berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('observasiacarafst.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $observasiAcaraFst = ObservasiAcaraFst::findOrFail($id);
        $observasiAcaraFst->delete();

        Alert::success('Observasi acara berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('observasiacarafst.index');
    }

    /**
     * Combine the "aspek observasi" repeater inputs into a single numbered text block.
     */
    private function formatAspekObservasi(array $items): ?string
    {
        $items = array_values(array_filter(array_map('trim', $items), fn ($item) => $item !== ''));

        if (empty($items)) {
            return null;
        }

        return implode("\n", array_map(fn ($index, $item) => ($index + 1) . '. ' . $item, array_keys($items), $items));
    }

    /**
     * Combine the "link dokumen" repeater inputs into a single newline-separated text block.
     */
    private function formatLinkDokumen(array $items): ?string
    {
        $items = array_values(array_filter(array_map('trim', $items), fn ($item) => $item !== ''));

        if (empty($items)) {
            return null;
        }

        return implode("\n", $items);
    }
}
