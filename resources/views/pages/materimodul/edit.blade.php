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
                    Upload file baru untuk mengganti materi yang sudah ada. Jika tidak diisi, file lama tetap tersimpan.
                    Format: <strong>PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP</strong> &mdash; Maks. <strong>10 MB</strong>.
                </p>

                <form action="{{ route('materimodul.update', $materiModul->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @foreach ([1, 2, 3, 4, 5] as $num)
                    @php $field = 'modul'.$num; $currentFile = $materiModul->$field; @endphp
                    <div class="row mb-4">
                        <label for="{{ $field }}" class="col-sm-2 col-form-label fw-semibold">
                            <i class="bi bi-journal-bookmark-fill me-1 text-primary"></i> Modul {{ $num }}
                        </label>
                        <div class="col-sm-10">
                            @if ($currentFile)
                                <div class="mb-2 d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-file-earmark-check me-1"></i> File tersimpan
                                    </span>
                                    <a href="{{ asset('storage/'.$currentFile) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download me-1"></i> Download File Saat Ini
                                    </a>
                                </div>
                            @else
                                <div class="mb-2">
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                        <i class="bi bi-x-circle me-1"></i> Belum ada file
                                    </span>
                                </div>
                            @endif

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
