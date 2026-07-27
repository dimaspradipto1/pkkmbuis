<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\KelompokNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KelompokNoteController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $user = Auth::user();
        $kelompok = Kelompok::with('dosenPendampings')->where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        $this->authorizeWrite($user, $kelompok);

        $request->validate([
            'content' => 'required|string',
        ], [
            'content.required' => 'Catatan tidak boleh kosong.',
        ]);

        KelompokNote::create([
            'kelompok_id' => $kelompok->id,
            'content' => $request->input('content'),
            'user_id' => $user->id,
        ]);

        Alert::success('Berhasil', 'Catatan berhasil disimpan.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('kelompok.show', $kelompok->slug);
    }

    public function destroy(string $slug, $id)
    {
        $user = Auth::user();
        $kelompok = Kelompok::with('dosenPendampings')->where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        $this->authorizeWrite($user, $kelompok);

        $note = KelompokNote::where('kelompok_id', $kelompok->id)->findOrFail($id);
        $note->delete();

        Alert::success('Berhasil', 'Catatan berhasil dihapus.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('kelompok.show', $kelompok->slug);
    }

    private function authorizeWrite($user, Kelompok $kelompok): void
    {
        if (in_array($user->role, ['admin', 'stafbaak', 'pimpinan'])) {
            return;
        }

        if ($user->role == 'dosenpendamping' && $kelompok->dosenPendampings->contains('id', $user->id)) {
            return;
        }

        abort(403);
    }
}
