<?php

namespace App\Http\Controllers;

use App\Models\ChatbotFaq;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ChatbotFaqController extends Controller
{
    public function index()
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403, 'Akses khusus Administrator / Staf BAAK.');
        }

        $faqs = ChatbotFaq::orderBy('urutan', 'asc')->orderBy('id', 'desc')->get();
        $dokumen = Dokumen::latest('id')->first();

        return view('pages.chatbotfaq.index', compact('faqs', 'dokumen'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
            'urutan' => 'nullable|integer',
        ]);

        ChatbotFaq::create([
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban,
            'urutan' => $request->urutan ?? 0,
            'is_active' => true,
        ]);

        Alert::success('Berhasil', 'Pertanyaan FAQ Chatbot baru berhasil ditambahkan.')->toToast()->autoClose(3000);
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $faq = ChatbotFaq::findOrFail($id);

        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
            'urutan' => 'nullable|integer',
        ]);

        $faq->update([
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban,
            'urutan' => $request->urutan ?? 0,
        ]);

        Alert::success('Berhasil', 'Pertanyaan FAQ Chatbot berhasil diperbarui.')->toToast()->autoClose(3000);
        return redirect()->back();
    }

    public function destroy($id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $faq = ChatbotFaq::findOrFail($id);
        $faq->delete();

        Alert::success('Berhasil', 'Pertanyaan FAQ Chatbot berhasil dihapus.')->toToast()->autoClose(3000);
        return redirect()->back();
    }

    public function toggle($id)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $faq = ChatbotFaq::findOrFail($id);
        $faq->is_active = !$faq->is_active;
        $faq->save();

        $status = $faq->is_active ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Berhasil', "Pertanyaan '{$faq->pertanyaan}' berhasil {$status}.")->toToast()->autoClose(3000);

        return redirect()->back();
    }

    public function updateWaGroup(Request $request)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'stafbaak') {
            abort(403);
        }

        $request->validate([
            'no_wa_admin' => 'nullable|string',
            'link_wa_group' => 'nullable|string',
        ]);

        $noWa = $request->no_wa_admin;
        if ($noWa) {
            $noWa = preg_replace('/[^0-9]/', '', $noWa);
            if (str_starts_with($noWa, '0')) {
                $noWa = '62' . substr($noWa, 1);
            }
        }

        $linkWa = $request->link_wa_group;
        if ($linkWa && !str_contains($linkWa, 'http')) {
            $linkWa = 'https://chat.whatsapp.com/' . ltrim($linkWa, '/');
        }

        $dokumen = Dokumen::latest('id')->first();
        if (!$dokumen) {
            $dokumen = Dokumen::create([
                'link_wa_group' => $linkWa,
                'no_wa_admin' => $noWa,
            ]);
        } else {
            $dokumen->update([
                'link_wa_group' => $linkWa,
                'no_wa_admin' => $noWa,
            ]);
        }

        Alert::success('Berhasil', 'Nomor WhatsApp Panitia berhasil disimpan.')->toToast()->autoClose(3000);
        return redirect()->back();
    }
}
