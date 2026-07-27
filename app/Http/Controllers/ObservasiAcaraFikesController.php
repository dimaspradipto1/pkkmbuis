<?php

namespace App\Http\Controllers;

use App\DataTables\ObservasiAcaraFikesDataTable;
use App\Http\Requests\ObservasiAcaraFikesRequest;
use App\Models\ObservasiAcaraFikes;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ObservasiAcaraFikesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ObservasiAcaraFikesDataTable $dataTable)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        return $dataTable->render('pages.observasiacarafikes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        return view('pages.observasiacarafikes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ObservasiAcaraFikesRequest $request)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $data = $request->validated();
        $data['aspek_observasi'] = $this->formatAspekObservasi($data['aspek_observasi'] ?? []);
        $data['link_dokumen'] = $this->formatLinkDokumen($data['link_dokumen'] ?? []);

        ObservasiAcaraFikes::create($data);

        Alert::success('Observasi acara berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('observasiacarafikes.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $observasiAcaraFikes = ObservasiAcaraFikes::findOrFail($id);
        return view('pages.observasiacarafikes.edit', compact('observasiAcaraFikes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ObservasiAcaraFikesRequest $request, string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $observasiAcaraFikes = ObservasiAcaraFikes::findOrFail($id);

        $data = $request->validated();
        $data['aspek_observasi'] = $this->formatAspekObservasi($data['aspek_observasi'] ?? []);
        $data['link_dokumen'] = $this->formatLinkDokumen($data['link_dokumen'] ?? []);

        $observasiAcaraFikes->update($data);

        Alert::success('Observasi acara berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('observasiacarafikes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $observasiAcaraFikes = ObservasiAcaraFikes::findOrFail($id);
        $observasiAcaraFikes->delete();

        Alert::success('Observasi acara berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('observasiacarafikes.index');
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
