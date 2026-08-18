<?php

namespace App\Http\Controllers;

use App\Models\LpjAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LpjAttachmentController extends Controller
{
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'timevaluasi', 'panitia'])) {
            abort(403);
        }

        $request->validate([
            'link' => 'nullable|string|max:2048',
            'file' => 'nullable|file|mimes:pdf,doc,docx',
            'user_id' => 'nullable|exists:users,id',
        ], [
            'file.mimes' => 'Format file yang diperbolehkan hanya PDF atau Word (doc, docx).',
        ]);

        if (empty($request->input('link')) && !$request->hasFile('file')) {
            Alert::error('Isi link eksternal atau upload file terlebih dahulu.', 'Gagal')
                ->toToast()
                ->autoClose(3000);

            return redirect()->back();
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $originalName = basename($uploadedFile->getClientOriginalName());
            $folder = 'lpj_attachments/' . uniqid();
            $filePath = $uploadedFile->storeAs($folder, $originalName, 'public');
        }

        LpjAttachment::create([
            'link' => $request->input('link'),
            'file' => $filePath,
            'user_id' => $request->input('user_id') ?: Auth::id(),
        ]);

        Alert::success('Berhasil', 'Lampiran LPJ berhasil ditambahkan.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('lpj.index');
    }

    public function edit($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'timevaluasi', 'panitia'])) {
            abort(403);
        }

        $attachment = LpjAttachment::findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('pages.lpj.edit-attachment', compact('attachment', 'users'));
    }

    public function update(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'timevaluasi', 'panitia'])) {
            abort(403);
        }

        $attachment = LpjAttachment::findOrFail($id);

        $request->validate([
            'link' => 'nullable|string|max:2048',
            'file' => 'nullable|file|mimes:pdf,doc,docx',
            'user_id' => 'nullable|exists:users,id',
        ], [
            'file.mimes' => 'Format file yang diperbolehkan hanya PDF atau Word (doc, docx).',
        ]);

        $link = $request->input('link');
        $filePath = $attachment->file;

        if ($request->hasFile('file')) {
            // Remove the old physical file before storing the replacement
            if ($attachment->file && \Illuminate\Support\Facades\Storage::disk('public')->exists($attachment->file)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file);
            }

            $uploadedFile = $request->file('file');
            $originalName = basename($uploadedFile->getClientOriginalName());
            $folder = 'lpj_attachments/' . uniqid();
            $filePath = $uploadedFile->storeAs($folder, $originalName, 'public');
        }

        // Link and file can coexist on the same attachment; only the field
        // that was actually resubmitted/replaced changes here.
        if (empty($link) && empty($filePath)) {
            Alert::error('Isi link eksternal atau upload file terlebih dahulu.', 'Gagal')
                ->toToast()
                ->autoClose(3000);

            return redirect()->back();
        }

        $attachment->update([
            'link' => $link,
            'file' => $filePath,
            'user_id' => $request->input('user_id') ?: $attachment->user_id,
        ]);

        Alert::success('Berhasil', 'Lampiran LPJ berhasil diperbarui.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->route('lpj.index');
    }

    public function destroy($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'timevaluasi', 'panitia'])) {
            abort(403);
        }

        $attachment = LpjAttachment::findOrFail($id);

        // Deleting the model will trigger the booted deleting event which removes the file from storage
        $attachment->delete();

        Alert::success('Berhasil', 'Lampiran LPJ berhasil dihapus.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->back();
    }
}
