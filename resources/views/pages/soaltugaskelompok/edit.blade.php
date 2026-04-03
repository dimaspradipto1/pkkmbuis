@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Link Tugas Kelompok</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('soaltugaskelompok.index') }}">Tugas Kelompok</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Edit Link Tugas Kelompok</h5>

                        <form action="{{ route('soaltugaskelompok.update', $soalTugasKelompok->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="link_tugas" class="col-sm-2 col-form-label">Link Tugas</label>
                                <div class="col-sm-10">
                                    <input type="url" name="link_tugas" id="link_tugas"
                                        class="form-control @error('link_tugas') is-invalid @enderror"
                                        value="{{ old('link_tugas', $soalTugasKelompok->link_tugas) }}"
                                        placeholder="https://..."
                                        required>
                                    @error('link_tugas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Masukkan URL lengkap, contoh: https://docs.google.com/...</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <a href="{{ $soalTugasKelompok->link_tugas }}" target="_blank" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link Saat Ini
                                    </a>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-warning text-white">Update Link</button>
                                <a href="{{ route('soaltugaskelompok.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
