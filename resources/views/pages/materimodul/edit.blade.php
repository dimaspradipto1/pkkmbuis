@extends('dashboard.template')
@section('content')
    <div class="pagetitle">
        <h1>Edit Materi Modul</h1>
        <nav><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('materimodul.index') }}">Materi Modul</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol></nav>
    </div>

    <section class="section">
        <div class="row"><div class="col-lg-12">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card"><div class="card-body">
                <h5 class="card-title">Form Edit Materi Modul</h5>
                <p class="text-muted mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Pilih metode upload untuk setiap modul. Upload file <strong>tidak ada batasan ukuran</strong>.
                    Format file: <strong>PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP</strong>.
                    Atau masukkan <strong>link Google Drive</strong> sebagai alternatif. Jika dikosongkan, data lama tetap tersimpan.
                </p>

                <form action="{{ route('materimodul.update', $materiModul->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @foreach ([1, 2, 3, 4, 5] as $num)
                    @php
                        $field     = 'modul' . $num;
                        $linkField = 'modul' . $num . '_link';
                        $currentFile = $materiModul->$field;
                        $currentLink = $materiModul->$linkField;
                        // Tentukan tab aktif: kalau ada link & tidak ada file → tab link, selainnya → tab file
                        $activeTab = (!$currentFile && $currentLink) ? 'link' : 'file';
                    @endphp
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label fw-semibold">
                            <i class="bi bi-journal-bookmark-fill me-1 text-primary"></i> Modul {{ $num }}
                        </label>
                        <div class="col-sm-10">

                            {{-- Status saat ini --}}
                            <div class="mb-2 d-flex align-items-center gap-2 flex-wrap">
                                @if ($currentFile)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-file-earmark-check me-1"></i> File tersimpan
                                    </span>
                                    <a href="{{ asset('storage/' . $currentFile) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download me-1"></i> Download File Saat Ini
                                    </a>
                                @elseif ($currentLink)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                        <i class="bi bi-link-45deg me-1"></i> Link tersimpan
                                    </span>
                                    <a href="{{ $currentLink }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link Saat Ini
                                    </a>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                        <i class="bi bi-x-circle me-1"></i> Belum ada file / link
                                    </span>
                                @endif
                            </div>

                            {{-- Toggle pilih metode --}}
                            <div class="d-flex gap-2 mb-2" id="toggle-{{ $num }}">
                                <button type="button"
                                    class="btn btn-sm {{ $activeTab === 'file' ? 'btn-primary' : 'btn-outline-secondary' }} toggle-btn"
                                    data-modul="{{ $num }}" data-type="file">
                                    <i class="bi bi-upload me-1"></i> Upload File
                                </button>
                                <button type="button"
                                    class="btn btn-sm {{ $activeTab === 'link' ? 'btn-info text-white' : 'btn-outline-secondary' }} toggle-btn"
                                    data-modul="{{ $num }}" data-type="link">
                                    <i class="bi bi-link-45deg me-1"></i> Link Google Drive
                                </button>
                            </div>

                            {{-- Panel Upload File --}}
                            <div id="panel-file-{{ $num }}" class="{{ $activeTab === 'file' ? '' : 'd-none' }}">
                                <input type="file" name="{{ $field }}" id="{{ $field }}"
                                    class="form-control @error($field) is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip">
                                @error($field)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    @if ($currentFile)
                                        Upload file baru untuk mengganti. Biarkan kosong jika tidak ingin diganti.
                                    @else
                                        Upload file materi untuk modul ini.
                                    @endif
                                </small>
                            </div>

                            {{-- Panel Link Google Drive --}}
                            <div id="panel-link-{{ $num }}" class="{{ $activeTab === 'link' ? '' : 'd-none' }}">
                                <input type="url" name="{{ $linkField }}" id="{{ $linkField }}"
                                    class="form-control @error($linkField) is-invalid @enderror"
                                    placeholder="https://drive.google.com/..."
                                    value="{{ old($linkField, $currentLink) }}"
                                    {{ $activeTab !== 'link' ? 'disabled' : '' }}>
                                @error($linkField)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Masukkan link Google Drive yang bisa diakses. Pastikan link sudah di-share publik.
                                    @if ($currentLink)
                                        Kosongkan jika tidak ingin mengubah link.
                                    @endif
                                </small>
                            </div>

                        </div>
                    </div>
                    @endforeach

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-warning text-white px-4">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('materimodul.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div></div>
        </div></div>
    </section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var modul = this.dataset.modul;
            var type  = this.dataset.type;

            // Update button styles
            document.querySelectorAll('[data-modul="' + modul + '"].toggle-btn').forEach(function(b) {
                b.classList.remove('btn-primary', 'btn-info', 'text-white', 'btn-outline-secondary');
                b.classList.add('btn-outline-secondary');
            });

            var fileInput = document.getElementById('modul' + modul);
            var linkInput = document.getElementById('modul' + modul + '_link');

            if (type === 'file') {
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');
                document.getElementById('panel-file-' + modul).classList.remove('d-none');
                document.getElementById('panel-link-' + modul).classList.add('d-none');
                // Disable link input agar tidak ter-submit (nilai tetap ada)
                if (linkInput) linkInput.disabled = true;
                if (fileInput) fileInput.disabled = false;
            } else {
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-info', 'text-white');
                document.getElementById('panel-link-' + modul).classList.remove('d-none');
                document.getElementById('panel-file-' + modul).classList.add('d-none');
                // Disable file input agar tidak ter-submit
                if (fileInput) fileInput.disabled = true;
                if (linkInput) linkInput.disabled = false;
            }
        });
    });
</script>
@endpush
