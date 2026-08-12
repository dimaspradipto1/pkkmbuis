@extends('dashboard.template')
@section('content')
    <div class="pagetitle">
        <h1>Tambah Materi Modul</h1>
        <nav><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('materimodul.index') }}">Materi Modul</a></li>
            <li class="breadcrumb-item active">Tambah</li>
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
                <h5 class="card-title">Form Unggah Materi Modul</h5>
                <p class="text-muted mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Pilih metode upload untuk setiap modul — bisa upload file langsung (format <strong>PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP</strong>,
                    <strong>tanpa batasan ukuran</strong>) atau masukkan <strong>link Google Drive</strong>. Keduanya bersifat opsional.
                </p>

                <form action="{{ route('materimodul.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @foreach ([1, 2, 3, 4, 5] as $num)
                    <div class="row mb-4 align-items-start">
                        <label class="col-sm-2 col-form-label fw-semibold">
                            <i class="bi bi-journal-bookmark-fill me-1 text-primary"></i> Modul {{ $num }}
                        </label>
                        <div class="col-sm-10">

                            {{-- Toggle pilih metode --}}
                            <div class="d-flex gap-2 mb-2">
                                <button type="button"
                                    class="btn btn-sm btn-primary toggle-btn"
                                    data-modul="{{ $num }}" data-type="file">
                                    <i class="bi bi-upload me-1"></i> Upload File
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-outline-secondary toggle-btn"
                                    data-modul="{{ $num }}" data-type="link">
                                    <i class="bi bi-link-45deg me-1"></i> Link Google Drive
                                </button>
                            </div>

                            {{-- Panel Upload File (default aktif) --}}
                            <div id="panel-file-{{ $num }}">
                                <input type="file" name="modul{{ $num }}" id="modul{{ $num }}"
                                    class="form-control @error('modul'.$num) is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip">
                                @error('modul'.$num)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload file materi untuk modul ini (opsional).</small>
                            </div>

                            {{-- Panel Link Google Drive (tersembunyi default) --}}
                            <div id="panel-link-{{ $num }}" class="d-none">
                                <input type="url" name="modul{{ $num }}_link" id="modul{{ $num }}_link"
                                    class="form-control @error('modul'.$num.'_link') is-invalid @enderror"
                                    placeholder="https://drive.google.com/..."
                                    value="{{ old('modul'.$num.'_link') }}"
                                    disabled>
                                @error('modul'.$num.'_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Masukkan link Google Drive yang bisa diakses publik (opsional).</small>
                            </div>

                        </div>
                    </div>
                    @endforeach

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-upload me-1"></i> Upload Materi
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
                // Disable link agar tidak ter-submit, aktifkan file
                if (linkInput) linkInput.disabled = true;
                if (fileInput) fileInput.disabled = false;
            } else {
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-info', 'text-white');
                document.getElementById('panel-link-' + modul).classList.remove('d-none');
                document.getElementById('panel-file-' + modul).classList.add('d-none');
                // Disable file agar tidak ter-submit, aktifkan link
                if (fileInput) fileInput.disabled = true;
                if (linkInput) linkInput.disabled = false;
            }
        });
    });
</script>
@endpush
