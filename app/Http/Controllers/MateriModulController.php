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
        $request->validate([
            'modul1' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
            'modul2' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
            'modul3' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
            'modul4' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
            'modul5' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
        ]);

        $data = [];
        foreach ([1, 2, 3, 4, 5] as $num) {
            $field = 'modul' . $num;
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store("materi_modul/$field", 'public');
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

        $request->validate([
            'modul1' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
            'modul2' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
            'modul3' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
            'modul4' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
            'modul5' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
        ]);

        $data = [];
        foreach ([1, 2, 3, 4, 5] as $num) {
            $field = 'modul' . $num;
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($materiModul->$field) {
                    Storage::disk('public')->delete($materiModul->$field);
                }
                $data[$field] = $request->file($field)->store("materi_modul/$field", 'public');
            }
        }

        $materiModul->update($data);

        Alert::success('Materi modul berhasil diperbarui.', 'Success')->toToast()->autoClose(3000);
        return redirect()->route('materimodul.index');
    }

    public function destroy(string $id)
    {
        $materiModul = MateriModul::findOrFail($id);

        // Hapus semua file terkait
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
