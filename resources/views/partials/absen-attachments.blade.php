@if (Auth::check() && in_array(Auth::user()->role, ['admin', 'kakakpendamping', 'dosenpendamping']))
<div class="card shadow-sm border-0 mt-4 rounded-3">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 text-dark fw-bold">
            <i class="bi bi-paperclip me-2 text-primary"></i> Lampiran Dokumen & Media (Gambar / PDF)
        </h5>
        <span class="badge bg-light text-secondary border">
            {{ count($attachments) }} File Diunggah
        </span>
    </div>
    <div class="card-body">

        @if (Auth::check() && Auth::user()->role != 'mahasiswa')
            <!-- Upload Form -->
            <form action="{{ route('absen-attachments.store') }}" method="POST" enctype="multipart/form-data" class="bg-light p-3 rounded-3 border mb-4">
                @csrf
                <input type="hidden" name="category" value="{{ $category }}">
                
                <div class="row align-items-center g-2">
                    <div class="col-md-8 col-lg-9">
                        <label for="attachment_files_{{ $category }}" class="form-label fw-semibold small mb-1">
                            Pilih File (Bisa pilih banyak file Gambar atau PDF sekaligus, Tanpa Batasan):
                        </label>
                        <input type="file" name="files[]" id="attachment_files_{{ $category }}" 
                               class="form-control form-control-sm @error('files') is-invalid @enderror" 
                               multiple accept="image/*,.pdf,application/pdf" required>
                        @error('files')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 col-lg-3 d-grid">
                        <label class="form-label d-none d-md-block mb-1">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload File
                        </button>
                    </div>
                </div>
                <div class="form-text mt-1 text-muted small">
                    <i class="bi bi-info-circle me-1"></i> Format yang didukung: <strong>Gambar (PNG, JPG, WEBP, GIF, SVG, BMP, ICO), PDF</strong> (Ukuran maks: 50MB/file).
                </div>
            </form>
        @endif

        <!-- File List & Preview Cards -->
        @if (count($attachments) > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                @foreach ($attachments as $item)
                    @php
                        $fileUrl = asset('storage/' . $item->file_path);
                        $isImage = in_array(strtolower($item->file_type), ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg', 'bmp', 'ico', 'heic', 'heif', 'jfif']);
                        $isPdf = strtolower($item->file_type) === 'pdf';
                    @endphp
                    <div class="col">
                        <div class="card h-100 border shadow-sm rounded-3 overflow-hidden">
                            <!-- Header / Preview area -->
                            <div class="bg-light text-center p-3 border-bottom position-relative d-flex align-items-center justify-content-center" style="height: 180px;">
                                @if ($isImage)
                                    <img src="{{ $fileUrl }}" alt="{{ $item->file_name }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 160px; object-fit: contain; cursor: pointer;"
                                         onclick="previewImageModal('{{ $fileUrl }}', '{{ e($item->file_name) }}')">
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-success shadow-sm">
                                        <i class="bi bi-image me-1"></i> {{ strtoupper($item->file_type) }}
                                    </span>
                                @elseif ($isPdf)
                                    <div class="text-center p-3">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 3.8rem;"></i>
                                        <div class="mt-2">
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-danger px-3 py-1 extra-small rounded-pill fw-bold">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Buka Dokumen PDF
                                            </a>
                                        </div>
                                    </div>
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-danger shadow-sm">
                                        <i class="bi bi-file-pdf me-1"></i> PDF DOKUMEN
                                    </span>
                                @else
                                    <div class="text-center">
                                        <i class="bi bi-file-earmark-text text-secondary" style="font-size: 4rem;"></i>
                                    </div>
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-secondary shadow-sm">
                                        {{ strtoupper($item->file_type) }}
                                    </span>
                                @endif

                                @if (Auth::check() && Auth::user()->role != 'mahasiswa')
                                    <form action="{{ route('absen-attachments.destroy', $item->id) }}" method="POST" 
                                          class="position-absolute top-0 end-0 m-2"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-circle p-1" style="width: 32px; height: 32px;" title="Hapus File">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Card Body / Info & Link -->
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-truncate mb-1 text-dark" title="{{ $item->file_name }}" style="font-size: 0.88rem;">
                                        {{ $item->file_name }}
                                    </h6>
                                    <div class="text-muted extra-small mb-2 d-flex justify-content-between">
                                        <span><i class="bi bi-calendar3 me-1"></i>{{ $item->created_at->format('d M Y H:i') }}</span>
                                        @if ($item->file_size)
                                            <span class="fw-bold text-dark">{{ number_format($item->file_size / 1024, 1) }} KB</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Access Link Section -->
                                <div class="pt-2 border-top">
                                    <label class="form-label extra-small text-secondary fw-semibold mb-1">
                                        <i class="bi bi-link-45deg me-1"></i> Link Akses File:
                                    </label>
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="text" readonly value="{{ $fileUrl }}" id="file_link_{{ $item->id }}" class="form-control form-control-sm bg-light text-dark extra-small" style="font-size: 0.75rem;">
                                        <button class="btn btn-outline-secondary extra-small fw-bold px-3" type="button" onclick="copyAttachmentLink('file_link_{{ $item->id }}')" title="Salin Link">
                                            <i class="bi bi-clipboard me-1"></i> Salin
                                        </button>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm {{ $isPdf ? 'btn-danger text-white' : 'btn-primary' }} w-100 fw-bold extra-small py-2">
                                            <i class="bi {{ $isPdf ? 'bi-file-earmark-pdf-fill' : 'bi-box-arrow-up-right' }} me-1"></i> Buka {{ $isPdf ? 'Dokumen PDF' : 'File Link' }}
                                        </a>
                                        <a href="{{ $fileUrl }}" download="{{ $item->file_name }}" class="btn btn-sm btn-outline-secondary fw-bold px-3 extra-small py-2 d-flex align-items-center" title="Download File">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-muted bg-light rounded border border-dashed">
                <i class="bi bi-folder-x fs-1 d-block text-secondary mb-2"></i>
                <p class="mb-0 fw-semibold">Belum ada file lampiran yang diunggah.</p>
                <small class="text-muted">Gunakan form di atas untuk mengunggah dokumen PDF atau file gambar (PNG, JPG, WEBP, GIF, dll).</small>
            </div>
        @endif

    </div>
</div>
@endif

<!-- Modal Image Preview -->
<div class="modal fade" id="attachmentImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="attachmentModalTitle">Preview Gambar</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="" id="attachmentModalImg" class="img-fluid rounded border shadow-sm" style="max-height: 75vh;">
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-between">
                <span class="text-muted small" id="attachmentModalPath"></span>
                <a href="" id="attachmentModalBtn" target="_blank" class="btn btn-sm btn-primary fw-bold">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Ukuran Penuh
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyAttachmentLink(elementId) {
        const input = document.getElementById(elementId);
        if (input) {
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value).then(() => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Link akses berhasil disalin!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    alert('Link akses berhasil disalin!');
                }
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    }

    function previewImageModal(url, title) {
        document.getElementById('attachmentModalImg').src = url;
        document.getElementById('attachmentModalTitle').innerText = title;
        document.getElementById('attachmentModalPath').innerText = url;
        document.getElementById('attachmentModalBtn').href = url;
        
        const modalEl = document.getElementById('attachmentImageModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
</script>
@endpush
