<?php

namespace App\Http\Controllers;

use App\DataTables\KelulusanDataTable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KelulusanController extends Controller
{
    public function index(KelulusanDataTable $dataTable)
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            abort(403);
        }

        return $dataTable->render('pages.kelulusan.index');
    }

    public function toggle($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            abort(403);
        }

        $user = User::where('role', 'mahasiswa')->findOrFail($id);
        $user->kelulusan_is_active = !$user->kelulusan_is_active;
        $user->save();

        $status = $user->kelulusan_is_active ? 'ditampilkan' : 'disembunyikan';
        Alert::success('Berhasil', "Status kelulusan untuk '{$user->name}' berhasil {$status}.")->toToast()->autoClose(3000);

        return redirect()->back();
    }
}
