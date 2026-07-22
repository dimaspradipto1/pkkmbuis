@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Penilaian Kedisiplinan Hari III</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kedisiplinanketiga.index') }}">Kedisiplinan Hari III</a></li>
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
                        <h5 class="card-title">Form Edit Penilaian</h5>

                        <form action="{{ route('kedisiplinanketiga.update', $kedisiplinanKetiga->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="user_id" class="col-sm-2 col-form-label">Nama Pengguna</label>
                                <div class="col-sm-10">
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ (old('user_id', $kedisiplinanKetiga->user_id) == $user->id) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->nomor_registrasi }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Kelengkapan Atribut</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kelengkapan_atribut" id="atribut_lengkap" value="Lengkap" {{ old('kelengkapan_atribut', $kedisiplinanKetiga->kelengkapan_atribut) == 'Lengkap' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="atribut_lengkap">Lengkap</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kelengkapan_atribut" id="atribut_tidak_lengkap" value="Tidak Lengkap" {{ old('kelengkapan_atribut', $kedisiplinanKetiga->kelengkapan_atribut) == 'Tidak Lengkap' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="atribut_tidak_lengkap">Tidak Lengkap</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Ketepatan Waktu</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ketepatan_waktu" id="waktu_tepat" value="Tepat Waktu" {{ old('ketepatan_waktu', $kedisiplinanKetiga->ketepatan_waktu) == 'Tepat Waktu' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="waktu_tepat">Tepat Waktu</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ketepatan_waktu" id="waktu_terlambat" value="Terlambat" {{ old('ketepatan_waktu', $kedisiplinanKetiga->ketepatan_waktu) == 'Terlambat' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="waktu_terlambat">Terlambat</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Perilaku</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="perilaku" id="perilaku_baik" value="Baik" {{ old('perilaku', $kedisiplinanKetiga->perilaku) == 'Baik' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perilaku_baik">Baik</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="perilaku" id="perilaku_tidak_baik" value="Tidak Baik" {{ old('perilaku', $kedisiplinanKetiga->perilaku) == 'Tidak Baik' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perilaku_tidak_baik">Tidak Baik</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="catatan" class="col-sm-2 col-form-label">Catatan</label>
                                <div class="col-sm-10">
                                    <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan', $kedisiplinanKetiga->catatan) }}</textarea>
                                </div>
                            </div>

                        <div class="text-end mt-4">
                                <button type="submit" class="btn btn-warning text-white">Update Penilaian</button>
                                <a href="{{ route('kedisiplinanketiga.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#user_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih Pengguna...',
                allowClear: true
            });
        });
    </script>
@endpush
