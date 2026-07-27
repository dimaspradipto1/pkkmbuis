@if (Auth::check() && in_array(Auth::user()->role, ['admin', 'kakakpendamping', 'dosenpendamping']))
<div class="card shadow-sm border-0 mt-4 rounded-3">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 text-dark fw-bold">
            <i class="bi bi-journal-text me-2 text-primary"></i> Catatan untuk Kakak Pendamping <span class="text-danger" style="color: #dc3545 !important;">*</span>
        </h5>
        <span class="badge bg-light text-secondary border">
            {{ count($notes) }} Catatan
        </span>
    </div>
    <div class="card-body">

        @if (Auth::check() && !in_array(Auth::user()->role, ['mahasiswa', 'kakakpendamping']))
            <form action="{{ route('kedisiplinan-notes.store') }}" method="POST" class="bg-light p-3 rounded-3 border mb-4">
                @csrf
                <input type="hidden" name="category" value="{{ $category }}">

                <div class="mb-2">
                    <label for="note_content_{{ $category }}" class="form-label fw-semibold small mb-1">Tambah Catatan Baru <span class="text-danger">*</span>:</label>
                    <textarea name="content" id="note_content_{{ $category }}"
                              class="form-control @error('content') is-invalid @enderror"
                              rows="3" placeholder="Tulis catatan di sini..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="bi bi-send-fill me-1"></i> Simpan Catatan
                    </button>
                </div>
            </form>
        @endif

        @if (count($notes) > 0)
            <div class="d-flex flex-column gap-3">
                @foreach ($notes as $note)
                    <div class="border rounded-3 p-3 bg-light position-relative">
                        <div class="d-flex justify-content-between align-items-start mb-2 pe-4">
                            <div>
                                <span class="fw-bold text-dark small"><i class="bi bi-person-circle me-1 text-primary"></i>{{ $note->user->name ?? 'Pengguna' }}</span>
                                @if($note->user)
                                    <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.65rem;">{{ strtoupper($note->user->role) }}</span>
                                @endif
                            </div>
                            <span class="text-muted extra-small text-nowrap"><i class="bi bi-clock me-1"></i>{{ $note->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <p class="mb-0 text-dark" style="white-space: pre-line;">{{ $note->content }}</p>

                        @if (Auth::check() && !in_array(Auth::user()->role, ['mahasiswa', 'kakakpendamping']))
                            <form action="{{ route('kedisiplinan-notes.destroy', $note->id) }}" method="POST"
                                  class="position-absolute top-0 end-0 m-2"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Hapus Catatan">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-muted bg-light rounded border border-dashed">
                <i class="bi bi-journal-x fs-1 d-block text-secondary mb-2"></i>
                <p class="mb-0 fw-semibold">Belum ada catatan.</p>
                <small class="text-muted">Gunakan form di atas untuk menambahkan catatan.</small>
            </div>
        @endif

    </div>
</div>
@endif
