<?php

namespace App\Http\Controllers;

use App\DataTables\MateriModulDataTable;
use App\Models\MateriModul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class MateriModulController extends Controller
{
    public function index(MateriModulDataTable $dataTable)
    {
        return $dataTable->render('pages.materimodul.index');
    }

    public function create()
    {
        return view('pages.materimodul.create');
    }

    public function store(Request $request)
    {
        $rules = [];
        foreach ([1, 2, 3, 4, 5] as $num) {
            $rules['modul' . $num]        = 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip';
            $rules['modul' . $num . '_link'] = 'nullable|url|max:2048';
        }
        $request->validate($rules);

        $data = [];
        foreach ([1, 2, 3, 4, 5] as $num) {
            $field     = 'modul' . $num;
            $linkField = 'modul' . $num . '_link';

            if ($request->hasFile($field)) {
                $data[$field]     = $request->file($field)->store("materi_modul/$field", 'public');
                $data[$linkField] = null; // clear link if file uploaded
            }

            if ($request->filled($linkField) && !$request->hasFile($field)) {
                $data[$linkField] = $request->input($linkField);
                $data[$field]     = null; // clear file path if link provided
            }
        }

        MateriModul::create($data);

        Alert::success('Materi modul berhasil diunggah.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('materimodul.index');
    }

    public function edit(string $id)
    {
        $materiModul = MateriModul::findOrFail($id);
        return view('pages.materimodul.edit', compact('materiModul'));
    }

    public function update(Request $request, string $id)
    {
        $materiModul = MateriModul::findOrFail($id);

        $rules = [];
        foreach ([1, 2, 3, 4, 5] as $num) {
            $rules['modul' . $num]        = 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip';
            $rules['modul' . $num . '_link'] = 'nullable|url|max:2048';
        }
        $request->validate($rules);

        $data = [];
        foreach ([1, 2, 3, 4, 5] as $num) {
            $field     = 'modul' . $num;
            $linkField = 'modul' . $num . '_link';

            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($materiModul->$field) {
                    Storage::disk('public')->delete($materiModul->$field);
                }
                $data[$field]     = $request->file($field)->store("materi_modul/$field", 'public');
                $data[$linkField] = null; // clear link when file uploaded
            } elseif ($request->filled($linkField)) {
                // Pakai link — hapus file lama jika ada
                if ($materiModul->$field) {
                    Storage::disk('public')->delete($materiModul->$field);
                }
                $data[$field]     = null;
                $data[$linkField] = $request->input($linkField);
            }
            // Jika keduanya kosong → tidak ada perubahan (lama tetap tersimpan)
        }

        $materiModul->update($data);

        Alert::success('Materi modul berhasil diperbarui.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('materimodul.index');
    }

    public function destroy(string $id)
    {
        $materiModul = MateriModul::findOrFail($id);

        foreach ([1, 2, 3, 4, 5] as $num) {
            $field = 'modul' . $num;
            if ($materiModul->$field) {
                Storage::disk('public')->delete($materiModul->$field);
            }
        }

        $materiModul->delete();

        Alert::success('Materi modul berhasil dihapus.', 'Deleted')->toToast()->autoClose(3000);
        return redirect()->route('materimodul.index');
    }

    public function download($id, $modul)
    {
        $materiModul = MateriModul::findOrFail($id);
        $field = 'modul' . $modul;

        if (!$materiModul->$field || !Storage::disk('public')->exists($materiModul->$field)) {
            abort(404, 'File materi tidak ditemukan');
        }

        return response()->download(storage_path('app/public/' . $materiModul->$field));
    }

    public function viewFile($id, $modul)
    {
        $materiModul = MateriModul::findOrFail($id);
        $field = 'modul' . $modul;

        if (!$materiModul->$field || !Storage::disk('public')->exists($materiModul->$field)) {
            abort(404, 'File materi tidak ditemukan');
        }

        return response()->file(storage_path('app/public/' . $materiModul->$field));
    }
}
