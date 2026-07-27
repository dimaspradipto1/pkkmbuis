<?php

namespace App\Http\Controllers;

use App\DataTables\ObservasiAcaraDataTable;
use App\Http\Requests\ObservasiAcaraRequest;
use App\Models\ObservasiAcara;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ObservasiAcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ObservasiAcaraDataTable $dataTable)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        return $dataTable->render('pages.observasiacara.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        return view('pages.observasiacara.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ObservasiAcaraRequest $request)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $data = $request->validated();
        $data['aspek_observasi'] = $this->formatAspekObservasi($data['aspek_observasi'] ?? []);
        $data['link_dokumen'] = $this->formatLinkDokumen($data['link_dokumen'] ?? []);

        ObservasiAcara::create($data);

        Alert::success('Observasi acara berhasil ditambahkan.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('observasiacara.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $observasiAcara = ObservasiAcara::findOrFail($id);
        return view('pages.observasiacara.edit', compact('observasiAcara'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ObservasiAcaraRequest $request, string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $observasiAcara = ObservasiAcara::findOrFail($id);

        $data = $request->validated();
        $data['aspek_observasi'] = $this->formatAspekObservasi($data['aspek_observasi'] ?? []);
        $data['link_dokumen'] = $this->formatLinkDokumen($data['link_dokumen'] ?? []);

        $observasiAcara->update($data);

        Alert::success('Observasi acara berhasil diperbarui.', 'Success')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('observasiacara.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'timevaluasi') {
            abort(403);
        }

        $observasiAcara = ObservasiAcara::findOrFail($id);
        $observasiAcara->delete();

        Alert::success('Observasi acara berhasil dihapus.', 'Deleted')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('observasiacara.index');
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
