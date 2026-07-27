<?php

namespace App\Http\Controllers;

use App\Models\AbsenNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AbsenNoteController extends Controller
{
    public function store(Request $request)
    {
        if (in_array(Auth::user()->role, ['mahasiswa', 'kakakpendamping'])) {
            abort(403);
        }

        $request->validate([
            'category' => 'required|string',
            'content' => 'required|string',
        ], [
            'content.required' => 'Catatan tidak boleh kosong.',
        ]);

        AbsenNote::create([
            'category' => $request->input('category'),
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Catatan berhasil disimpan.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->back();
    }

    public function destroy($id)
    {
        if (in_array(Auth::user()->role, ['mahasiswa', 'kakakpendamping'])) {
            abort(403);
        }

        $note = AbsenNote::findOrFail($id);
        $note->delete();

        Alert::success('Berhasil', 'Catatan berhasil dihapus.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->back();
    }
}
