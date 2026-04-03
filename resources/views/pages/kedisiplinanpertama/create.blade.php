@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Penilaian Kedisiplinan Hari I</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kedisiplinanpertama.index') }}">Kedisiplinan Hari I</a></li>
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
                        <h5 class="card-title">Form Penilaian Kedisiplinan</h5>

                        <form action="{{ route('kedisiplinanpertama.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="user_id" class="col-sm-2 col-form-label">Nama Pengguna</label>
                                <div class="col-sm-10">
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        <option value="" disabled selected>Pilih Pengguna...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->nomor_registrasi }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="kelengkapan_atribut" class="col-sm-2 col-form-label">Kelengkapan Atribut</label>
                                <div class="col-sm-10">
                                    <select name="kelengkapan_atribut" id="kelengkapan_atribut" class="form-select">
                                        <option value="Lengkap">Lengkap</option>
                                        <option value="Cukup Lengkap">Cukup Lengkap</option>
                                        <option value="Tidak Lengkap">Tidak Lengkap</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ketepatan_waktu" class="col-sm-2 col-form-label">Ketepatan Waktu</label>
                                <div class="col-sm-10">
                                    <select name="ketepatan_waktu" id="ketepatan_waktu" class="form-select">
                                        <option value="Tepat Waktu">Tepat Waktu</option>
                                        <option value="Terlambat">Terlambat</option>
                                        <option value="Sangat Terlambat">Sangat Terlambat</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="perilaku" class="col-sm-2 col-form-label">Perilaku</label>
                                <div class="col-sm-10">
                                    <select name="perilaku" id="perilaku" class="form-select">
                                        <option value="Sangat Baik">Sangat Baik</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Cukup">Cukup</option>
                                        <option value="Buruk">Buruk</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="catatan" class="col-sm-2 col-form-label">Catatan</label>
                                <div class="col-sm-10">
                                    <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                                </div>
                            </div>

                        <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                                <a href="{{ route('kedisiplinanpertama.index') }}" class="btn btn-secondary">Batal</a>
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
