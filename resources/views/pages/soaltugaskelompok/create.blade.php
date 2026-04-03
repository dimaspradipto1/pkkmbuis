@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Link Tugas Kelompok</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('soaltugaskelompok.index') }}">Tugas Kelompok</a></li>
                <li class="breadcrumb-item active">Tambah</li>
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
                        <h5 class="card-title">Form Tambah Link Tugas Kelompok</h5>

                        <form action="{{ route('soaltugaskelompok.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <label for="link_tugas" class="col-sm-2 col-form-label">Link Tugas</label>
                                <div class="col-sm-10">
                                    <input type="url" name="link_tugas" id="link_tugas"
                                        class="form-control @error('link_tugas') is-invalid @enderror"
                                        value="{{ old('link_tugas') }}"
                                        placeholder="https://..."
                                        required>
                                    @error('link_tugas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Masukkan URL lengkap, contoh: https://docs.google.com/...</small>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Link</button>
                                <a href="{{ route('soaltugaskelompok.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
