<div class="card shadow-sm border-0 mt-4 rounded-3">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 text-dark fw-bold">
            <i class="bi bi-paperclip me-2 text-primary"></i> Lampiran LPJ
        </h5>
        <span class="badge bg-light text-secondary border">
            {{ count($attachments) }} Lampiran
        </span>
    </div>
    <div class="card-body">

        @if (count($attachments) > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                @foreach ($attachments as $item)
                    @php
                        $isFile = !empty($item->file);
                        $fileUrl = $isFile ? asset('storage/' . $item->file) : null;
                        $ext = $isFile ? strtolower(pathinfo($item->file, PATHINFO_EXTENSION)) : null;
                    @endphp
                    <div class="col">
                        <div class="card h-100 border shadow-sm rounded-3 overflow-hidden position-relative">
                            <div class="card-body p-3">
                                <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                    <a href="{{ route('lpj-attachments.edit', $item->id) }}" class="btn btn-warning btn-sm rounded-circle p-1" style="width: 28px; height: 28px;" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('lpj-attachments.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lampiran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-circle p-1" style="width: 28px; height: 28px;" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>

                                @if ($isFile)
                                    <div class="d-flex align-items-center gap-2 mb-2 pe-5">
                                        <i class="bi bi-file-earmark-{{ $ext === 'pdf' ? 'pdf' : 'word' }}-fill text-{{ $ext === 'pdf' ? 'danger' : 'primary' }}" style="font-size: 2rem;"></i>
                                        <div class="text-truncate">
                                            <div class="fw-bold text-truncate" style="font-size: 0.85rem;" title="{{ basename($item->file) }}">{{ basename($item->file) }}</div>
                                            <span class="badge bg-light text-secondary border">{{ strtoupper($ext) }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 fw-bold">
                                        <i class="bi bi-download me-1"></i> Buka / Unduh
                                    </a>
                                @endif

                                @if (!empty($item->link))
                                    <div class="d-flex align-items-center gap-2 mb-2 {{ $isFile ? 'mt-3 pt-2 border-top' : 'pe-5' }}">
                                        <i class="bi bi-link-45deg text-success" style="font-size: {{ $isFile ? '1.4rem' : '2rem' }};"></i>
                                        <div class="text-truncate">
                                            <div class="fw-bold text-truncate" style="font-size: 0.85rem;" title="{{ $item->link }}">{{ $item->link }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ $item->link }}" target="_blank" class="btn btn-sm btn-outline-success w-100 fw-bold">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                    </a>
                                @endif

                                <div class="text-muted extra-small mt-2">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $item->created_at->format('d M Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-muted bg-light rounded border border-dashed">
                <i class="bi bi-paperclip fs-1 d-block text-secondary mb-2"></i>
                <p class="mb-0 fw-semibold">Belum ada lampiran.</p>
                <small class="text-muted">Tambahkan link eksternal atau upload file PDF/Word melalui form Tambah LPJ.</small>
            </div>
        @endif

    </div>
</div>
