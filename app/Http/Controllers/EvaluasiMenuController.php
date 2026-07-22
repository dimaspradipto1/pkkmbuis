<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EvaluasiMenuController extends Controller
{
    public function index()
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $menus = EvaluasiMenu::orderBy('nomor')->get();
        return view('pages.evaluasimenu.index', compact('menus'));
    }

    public function toggle($id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $menu = EvaluasiMenu::findOrFail($id);
        $menu->is_active = !$menu->is_active;
        $menu->save();

        $status = $menu->is_active ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Berhasil', "Menu '{$menu->nama}' berhasil {$status}.")->toToast()->autoClose(3000);

        return redirect()->back();
    }
}
