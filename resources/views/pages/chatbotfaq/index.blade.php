@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Pengaturan Chatbot & Link WhatsApp Group</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pengaturan Chatbot & WA</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            {{-- WhatsApp Group Link Card --}}
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #059669 0%, #10B981 100%) !important; color: #ffffff !important;">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background-color: rgba(255,255,255,0.2) !important;">
                                <i class="bi bi-whatsapp fs-2 text-white"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-white" style="color: #ffffff !important;">Pengaturan Kontak WhatsApp (Group & Personal)</h5>
                                <p class="mb-0 extra-small" style="color: rgba(255,255,255,0.85) !important;">Pengaturan ini digunakan untuk fitur direct chat di Chatbot Mahasiswa (Group & Personal Admin).</p>
                            </div>
                        </div>

                        <form action="{{ route('chatbot-faq.update-wa') }}" method="POST">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-5 col-md-6">
                                    <label class="form-label small fw-bold mb-1 text-white">Nomor WA / Link WA Group Panitia:</label>
                                    <div class="input-group shadow-sm rounded overflow-hidden">
                                        <span class="input-group-text bg-white border-0 text-success fw-bold px-3">
                                            <i class="bi bi-people-fill me-1"></i> WA Group
                                        </span>
                                        <input type="text" name="link_wa_group" class="form-control border-0 px-3 py-2 text-dark" placeholder="Contoh: 6281234567890 atau https://chat.whatsapp.com/xxx" value="{{ old('link_wa_group', $dokumen->link_wa_group ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6">
                                    <label class="form-label small fw-bold mb-1 text-white">Nomor WA Personal Admin (Format 628xxx):</label>
                                    <div class="input-group shadow-sm rounded overflow-hidden">
                                        <span class="input-group-text bg-white border-0 text-success fw-bold px-3">
                                            <i class="bi bi-person-fill me-1"></i> Personal
                                        </span>
                                        <input type="text" name="no_wa_admin" class="form-control border-0 px-3 py-2 text-dark" placeholder="Contoh: 6281234567890 atau 081234567890" value="{{ old('no_wa_admin', $dokumen->no_wa_admin ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12">
                                    <button type="submit" class="btn btn-warning w-100 fw-bold py-2 shadow-sm text-dark d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-box-arrow-in-down"></i> <span>Simpan Kontak WA</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Custom FAQ Management Card --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-chat-left-text text-primary me-2"></i>Daftar Pertanyaan Custom Chatbot</h5>
                                <p class="text-muted extra-small mb-0">Tambah, edit, atau aktifkan pertanyaan custom yang akan tampil di Chatbot Mahasiswa.</p>
                            </div>
                            <button class="btn btn-success btn-sm rounded-pill px-3 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Pertanyaan Custom
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 30%;">Pertanyaan Custom</th>
                                        <th style="width: 45%;">Jawaban Otomatis Bot</th>
                                        <th style="width: 10%; text-align: center;">Status</th>
                                        <th style="width: 10%; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($faqs as $index => $faq)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-bold text-dark">{{ $faq->pertanyaan }}</td>
                                            <td>{{ Str::limit($faq->jawaban, 100) }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('chatbot-faq.toggle', $faq->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @if($faq->is_active)
                                                        <button type="submit" class="badge bg-success border-0 px-3 py-2 rounded-pill" title="Klik untuk menonaktifkan">Aktif ✓</button>
                                                    @else
                                                        <button type="submit" class="badge bg-secondary border-0 px-3 py-2 rounded-pill" title="Klik untuk mengaktifkan">Non-aktif ✕</button>
                                                    @endif
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-warning rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editFaqModal{{ $faq->id }}" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <form action="{{ route('chatbot-faq.destroy', $faq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        {{-- Modal Edit FAQ --}}
                                        <div class="modal fade" id="editFaqModal{{ $faq->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0 shadow-lg">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Pertanyaan Custom</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('chatbot-faq.update', $faq->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold extra-small text-muted">Pertanyaan Custom</label>
                                                                <input type="text" name="pertanyaan" class="form-control rounded-3" value="{{ old('pertanyaan', $faq->pertanyaan) }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold extra-small text-muted">Jawaban Otomatis Bot</label>
                                                                <textarea name="jawaban" rows="4" class="form-control rounded-3" required>{{ old('jawaban', $faq->jawaban) }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold extra-small text-muted">Urutan Tampilan</label>
                                                                <input type="number" name="urutan" class="form-control rounded-3" value="{{ old('urutan', $faq->urutan) }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                Belum ada pertanyaan custom yang ditambahkan. Pertanyaan basic standar tetap aktif di Chatbot.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal Tambah FAQ --}}
    <div class="modal fade" id="addFaqModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle-fill text-success me-2"></i>Tambah Pertanyaan Custom Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('chatbot-faq.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold extra-small text-muted">Pertanyaan Custom</label>
                            <input type="text" name="pertanyaan" class="form-control rounded-3" placeholder="Contoh: Di mana lokasi kumpul pembukaan PKKMB?" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold extra-small text-muted">Jawaban Otomatis Bot</label>
                            <textarea name="jawaban" rows="4" class="form-control rounded-3" placeholder="Masukkan jawaban lengkap dari bot di sini..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold extra-small text-muted">Urutan Tampilan</label>
                            <input type="number" name="urutan" class="form-control rounded-3" value="1" min="1">
                            <small class="text-muted extra-small d-block mt-1">Dimulai dari angka <strong>1</strong>. Angka terkecil akan tampil paling atas pada menu Chatbot.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Tambah Pertanyaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
