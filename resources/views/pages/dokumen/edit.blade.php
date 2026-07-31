@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Dokumen</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen</a></li>
                <li class="breadcrumb-item active">Edit</li>
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
                        <h5 class="card-title">Form Edit Dokumen</h5>
                        <form action="{{ route('dokumen.update', $dokuman->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Link Buku Saku</label>
                                <input type="url" class="form-control" name="link_buku_saku" value="{{ old('link_buku_saku', $dokuman->link_buku_saku) }}" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Link Daftar Kelompok</label>
                                <input type="url" class="form-control" name="link_daftar_kelompok" value="{{ old('link_daftar_kelompok', $dokuman->link_daftar_kelompok) }}" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Link Rundown</label>
                                <input type="url" class="form-control" name="link_rundown" value="{{ old('link_rundown', $dokuman->link_rundown) }}" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Link Tata Tertib Kehidupan Mahasiswa</label>
                                <input type="url" class="form-control" name="link_tata_tertib_kehidupan_mahasiswa" value="{{ old('link_tata_tertib_kehidupan_mahasiswa', $dokuman->link_tata_tertib_kehidupan_mahasiswa) }}" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Link Video Tutorial Penggunaan Sistem PKKMB</label>
                                <input type="url" class="form-control" name="link_video_tutorial_penggunaan_sistem_PKKMB" value="{{ old('link_video_tutorial_penggunaan_sistem_PKKMB', $dokuman->link_video_tutorial_penggunaan_sistem_PKKMB) }}" placeholder="https://...">
                            </div>
                            <div class="text-end">
                                <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
