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
                    Upload file materi untuk setiap modul. Format yang diterima: <strong>PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP</strong>. Maksimal <strong>10 MB</strong> per file.
                </p>

                <form action="{{ route('materimodul.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @foreach ([1, 2, 3, 4, 5] as $num)
                    <div class="row mb-4 align-items-center">
                        <label for="modul{{ $num }}" class="col-sm-2 col-form-label fw-semibold">
                            <i class="bi bi-journal-bookmark-fill me-1 text-primary"></i> Modul {{ $num }}
                        </label>
                        <div class="col-sm-10">
                            <input type="file" name="modul{{ $num }}" id="modul{{ $num }}"
                                class="form-control @error('modul'.$num) is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip">
                            @error('modul'.$num)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
