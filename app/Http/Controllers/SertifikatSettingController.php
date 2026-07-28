<?php

namespace App\Http\Controllers;

use App\Models\SertifikatSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class SertifikatSettingController extends Controller
{
    protected array $logoFields = ['logo_dikti', 'logo_belmawa', 'logo_pkkmb', 'logo_kampus', 'logo_lima'];

    public function index()
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            abort(403);
        }

        $setting = SertifikatSetting::current();
        return view('pages.sertifikatsetting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'stafbaak'])) {
            abort(403);
        }

        $request->validate([
            'kode_surat' => 'required|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|string|max:255',
            'nama_mengetahui' => 'required|string|max:255',
            'jabatan_mengetahui' => 'required|string|max:255',
            'nama_ketua_panitia' => 'required|string|max:255',
            'jabatan_ketua_panitia' => 'required|string|max:255',
            'logo_dikti' => 'nullable|image|max:2048',
            'logo_belmawa' => 'nullable|image|max:2048',
            'logo_pkkmb' => 'nullable|image|max:2048',
            'logo_kampus' => 'nullable|image|max:2048',
            'logo_lima' => 'nullable|image|max:2048',
        ]);

        $setting = SertifikatSetting::current();

        $data = $request->only([
            'kode_surat',
            'nama_kegiatan',
            'lokasi',
            'tanggal_pelaksanaan',
            'nama_mengetahui',
            'jabatan_mengetahui',
            'nama_ketua_panitia',
            'jabatan_ketua_panitia',
        ]);

        foreach ($this->logoFields as $field) {
            if ($request->hasFile($field)) {
                if ($setting->$field) {
                    Storage::disk('public')->delete($setting->$field);
                }
                $data[$field] = $request->file($field)->store('sertifikat_logo', 'public');
            }
        }

        $setting->update($data);

        Alert::success('Berhasil', 'Pengaturan sertifikat berhasil disimpan.')->toToast()->autoClose(3000);
        return redirect()->route('sertifikatsetting.index');
    }
}
