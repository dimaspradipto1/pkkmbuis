@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Dokumen</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Tambah Dokumen</h5>
                        <form action="{{ route('dokumen.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Link Buku Saku</label>
                                <input type="url" class="form-control" name="link_buku_saku" value="{{ old('link_buku_saku') }}" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Link Daftar Kelompok</label>
                                <input type="url" class="form-control" name="link_daftar_kelompok" value="{{ old('link_daftar_kelompok') }}" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Link Rundown</label>
                                <input type="url" class="form-control" name="link_rundown" value="{{ old('link_rundown') }}" placeholder="https://...">
                            </div>
                            <div class="text-end">
                                <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
