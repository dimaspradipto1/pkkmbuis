@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Kelompok</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kelompok.index') }}">Manajemen Kelompok</a></li>
                <li class="breadcrumb-item active">Edit Kelompok</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title p-0 mb-4 fw-bold text-primary">Form Edit Kelompok</h5>

                        <form action="{{ route('kelompok.update', $kelompok->slug) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama_kelompok" class="form-label fw-bold small">Nama Kelompok <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kelompok" id="nama_kelompok" class="form-control" value="{{ old('nama_kelompok', $kelompok->nama_kelompok) }}" required>
                                @error('nama_kelompok')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="pendamping_id" class="form-label fw-bold small">Pendamping / Kakak Leting</label>
                                <select name="pendamping_id" id="pendamping_id" class="form-select">
                                    <option value="">-- Pilih Pendamping (Opsional) --</option>
                                    @foreach($pendampings as $p)
                                        <option value="{{ $p->id }}" {{ old('pendamping_id', $kelompok->pendamping_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ strtoupper($p->role) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="keterangan" class="form-label fw-bold small">Keterangan / Catatan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3">{{ old('keterangan', $kelompok->keterangan) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('kelompok.index') }}" class="btn btn-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-warning text-white px-4">Update Kelompok</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
