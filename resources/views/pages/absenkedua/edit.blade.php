@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Absensi Kedua</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('absenkedua.index') }}">Absensi Kedua</a></li>
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
                        <h5 class="card-title">Form Edit Absensi</h5>

                        <form action="{{ route('absenkedua.update', $absenKedua->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="user_id" class="col-sm-2 col-form-label">Nama Pengguna</label>
                                <div class="col-sm-10">
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ (old('user_id', $absenKedua->user_id) == $user->id) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->nomor_registrasi }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="hadir_pagi" class="col-sm-2 col-form-label">Hadir Pagi</label>
                                <div class="col-sm-10">
                                    <select name="hadir_pagi" id="hadir_pagi" class="form-select">
                                        <option value="" disabled selected>Pilih Hadir Pagi...</option>
                                        <option value="Hadir" {{ (old('hadir_pagi', $absenKedua->hadir_pagi) == 'Hadir') ? 'selected' : '' }}>Hadir</option>
                                        <option value="Izin" {{ (old('hadir_pagi', $absenKedua->hadir_pagi) == 'Izin') ? 'selected' : '' }}>Izin</option>
                                        <option value="Tidak Hadir" {{ (old('hadir_pagi', $absenKedua->hadir_pagi) == 'Tidak Hadir') ? 'selected' : '' }}>Tidak Hadir</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="hadir_sore" class="col-sm-2 col-form-label">Hadir Sore</label>
                                <div class="col-sm-10">
                                    <select name="hadir_sore" id="hadir_sore" class="form-select">
                                        <option value="" disabled selected>Pilih Hadir Sore...</option>
                                        <option value="Hadir" {{ (old('hadir_sore', $absenKedua->hadir_sore) == 'Hadir') ? 'selected' : '' }}>Hadir</option>
                                        <option value="Izin" {{ (old('hadir_sore', $absenKedua->hadir_sore) == 'Izin') ? 'selected' : '' }}>Izin</option>
                                        <option value="Tidak Hadir" {{ (old('hadir_sore', $absenKedua->hadir_sore) == 'Tidak Hadir') ? 'selected' : '' }}>Tidak Hadir</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-warning text-white">Update Absensi</button>
                                <a href="{{ route('absenkedua.index') }}" class="btn btn-secondary">Batal</a>
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
