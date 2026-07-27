<?php

namespace App\Http\Controllers;

use App\Models\KedisiplinanAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class KedisiplinanAttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:png,jpg,jpeg,webp,gif,svg,bmp,ico,pdf,heic,heif,jfif|max:51200',
        ], [
            'files.required' => 'Pilih setidaknya satu file untuk diupload.',
            'files.*.mimes' => 'Format file yang diperbolehkan adalah Gambar (PNG, JPG, JPEG, WEBP, GIF, SVG, BMP, ICO) dan PDF.',
            'files.*.max' => 'Ukuran file maksimal adalah 50MB per file.',
        ]);

        $category = $request->input('category');
        $uploadedCount = 0;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                $size = $file->getSize();

                $path = $file->store("kedisiplinan_attachments/{$category}", 'public');

                KedisiplinanAttachment::create([
                    'category' => $category,
                    'file_name' => $originalName,
                    'file_path' => $path,
                    'file_type' => $extension,
                    'file_size' => $size,
                    'user_id' => Auth::id(),
                ]);

                $uploadedCount++;
            }
        }

        Alert::success('Berhasil', "{$uploadedCount} file lampiran berhasil diunggah.")
            ->toToast()
            ->autoClose(3000);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $attachment = KedisiplinanAttachment::findOrFail($id);

        // Deleting the model will trigger the booted deleting event which removes the file from storage
        $attachment->delete();

        Alert::success('Berhasil', 'File lampiran berhasil dihapus dari database & storage.')
            ->toToast()
            ->autoClose(3000);

        return redirect()->back();
    }
}
