<?php

namespace App\Http\Controllers;

use App\DataTables\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegaraDataTable;
use App\Http\Requests\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegaraRequest;
use App\Models\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegaraDataTable $dataTable)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::where('user_id', Auth::id())->first();
            $questions = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::questions();

            if ($evaluasi) {
                return view('pages.evaluasikehidupanberbangsabelanegara.completed', compact('evaluasi'));
            } else {
                return view('pages.evaluasikehidupanberbangsabelanegara.create', compact('questions'));
            }
        }

        return $dataTable->render('pages.evaluasikehidupanberbangsabelanegara.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $evaluasi = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::where('user_id', Auth::id())->first();
            if ($evaluasi) {
                return view('pages.evaluasikehidupanberbangsabelanegara.completed', compact('evaluasi'));
            }
        }
        $questions = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::questions();
        return view('pages.evaluasikehidupanberbangsabelanegara.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegaraRequest $request)
    {
        if (Auth::user()->role == 'mahasiswa') {
            $existing = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::where('user_id', Auth::id())->first();
            if ($existing) {
                $existing->update($request->validated());
                Alert::success('Berhasil', 'Evaluasi Kehidupan Berbangsa, Bernegara dan Pembinaan Kesadaran Bela Negara berhasil diperbarui.')->toToast()->autoClose(3000);
                return redirect()->route('evaluasikehidupanberbangsabelanegara.index');
            }
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::create($data);

        Alert::success('Berhasil', 'Evaluasi Kehidupan Berbangsa, Bernegara dan Pembinaan Kesadaran Bela Negara berhasil disimpan.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasikehidupanberbangsabelanegara.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evaluasi = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::with('user.kelompok')->findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::questions();
        return view('pages.evaluasikehidupanberbangsabelanegara.show', compact('evaluasi', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluasi = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $questions = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::questions();
        return view('pages.evaluasikehidupanberbangsabelanegara.edit', compact('evaluasi', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegaraRequest $request, $id)
    {
        $evaluasi = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa' && $evaluasi->user_id != Auth::id()) {
            abort(403);
        }
        $evaluasi->update($request->validated());

        Alert::success('Berhasil', 'Evaluasi Kehidupan Berbangsa, Bernegara dan Pembinaan Kesadaran Bela Negara berhasil diperbarui.')->toToast()->autoClose(3000);

        return redirect()->route('evaluasikehidupanberbangsabelanegara.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::findOrFail($id);
        if (Auth::user()->role == 'mahasiswa') {
            abort(403);
        }
        $evaluasi->delete();

        Alert::success('Berhasil', 'Evaluasi Kehidupan Berbangsa, Bernegara dan Pembinaan Kesadaran Bela Negara berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->route('evaluasikehidupanberbangsabelanegara.index');
    }
}
