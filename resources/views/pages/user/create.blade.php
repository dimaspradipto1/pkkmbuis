@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tambah User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Data Pengguna</a></li>
                <li class="breadcrumb-item active">Tambah Pengguna</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Tambah Pengguna</h5>

                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="id_pendaftar" class="col-sm-2 col-form-label">ID Pendaftar</label>
                                <div class="col-sm-10">
                                    <input type="text" name="id_pendaftar" class="form-control" id="id_pendaftar" value="{{ old('id_pendaftar') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Password minimal 8 karakter.</small>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="role" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select name="role" class="form-select" id="role" required>
                                        <option value="" disabled selected>Pilih Role...</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="stafbaak" {{ old('role') == 'stafbaak' ? 'selected' : '' }}>Staf BAAK</option>
                                        <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                        <option value="kakakleting" {{ old('role') == 'kakakleting' ? 'selected' : '' }}>Kakak Leting</option>
                                        <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                                <div class="col-sm-10">
                                    <select name="fakultas" class="form-select" id="fakultas">
                                        <option value="" selected>-- Pilih Fakultas --</option>
                                        <option value="FAKULTAS EKONOMI DAN BISNIS (FEB)" {{ old('fakultas') == 'FAKULTAS EKONOMI DAN BISNIS (FEB)' ? 'selected' : '' }}>FAKULTAS EKONOMI DAN BISNIS (FEB)</option>
                                        <option value="FAKULTAS SAINS DAN TEKNOLOGI (FST)" {{ old('fakultas') == 'FAKULTAS SAINS DAN TEKNOLOGI (FST)' ? 'selected' : '' }}>FAKULTAS SAINS DAN TEKNOLOGI (FST)</option>
                                        <option value="FAKULTAS ILMU KESEHATAN (FIKes)" {{ old('fakultas') == 'FAKULTAS ILMU KESEHATAN (FIKes)' ? 'selected' : '' }}>FAKULTAS ILMU KESEHATAN (FIKes)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="program_studi" class="col-sm-2 col-form-label">Program Studi</label>
                                <div class="col-sm-10">
                                    <select name="program_studi" class="form-select" id="program_studi">
                                        <option value="" selected>-- Pilih Program Studi --</option>
                                        <optgroup label="FAKULTAS EKONOMI DAN BISNIS (FEB)">
                                            <option value="S2 MAGISTER MANAJEMEN" {{ old('program_studi') == 'S2 MAGISTER MANAJEMEN' ? 'selected' : '' }}>S2 MAGISTER MANAJEMEN</option>
                                            <option value="S1 AKUNTANSI" {{ old('program_studi') == 'S1 AKUNTANSI' ? 'selected' : '' }}>S1 AKUNTANSI</option>
                                            <option value="S1 MANAJEMEN" {{ old('program_studi') == 'S1 MANAJEMEN' ? 'selected' : '' }}>S1 MANAJEMEN</option>
                                        </optgroup>
                                        <optgroup label="FAKULTAS SAINS DAN TEKNOLOGI (FST)">
                                            <option value="S1 TEKNIK INDUSTRI" {{ old('program_studi') == 'S1 TEKNIK INDUSTRI' ? 'selected' : '' }}>S1 TEKNIK INDUSTRI</option>
                                            <option value="S1 TEKNIK INFORMATIKA" {{ old('program_studi') == 'S1 TEKNIK INFORMATIKA' ? 'selected' : '' }}>S1 TEKNIK INFORMATIKA</option>
                                            <option value="S1 TEKNIK LOGISTIK" {{ old('program_studi') == 'S1 TEKNIK LOGISTIK' ? 'selected' : '' }}>S1 TEKNIK LOGISTIK</option>
                                            <option value="S1 SISTEM INFORMASI" {{ old('program_studi') == 'S1 SISTEM INFORMASI' ? 'selected' : '' }}>S1 SISTEM INFORMASI</option>
                                            <option value="S1 TEKNIK PERKAPALAN" {{ old('program_studi') == 'S1 TEKNIK PERKAPALAN' ? 'selected' : '' }}>S1 TEKNIK PERKAPALAN</option>
                                        </optgroup>
                                        <optgroup label="FAKULTAS ILMU KESEHATAN (FIKes)">
                                            <option value="S1 KESEHATAN DAN KESELAMATAN KERJA" {{ old('program_studi') == 'S1 KESEHATAN DAN KESELAMATAN KERJA' ? 'selected' : '' }}>S1 KESEHATAN DAN KESELAMATAN KERJA</option>
                                            <option value="S1 KESEHATAN LINGKUNGAN" {{ old('program_studi') == 'S1 KESEHATAN LINGKUNGAN' ? 'selected' : '' }}>S1 KESEHATAN LINGKUNGAN</option>
                                            <option value="S2 KESEHATAN MASYARAKAT" {{ old('program_studi') == 'S2 KESEHATAN MASYARAKAT' ? 'selected' : '' }}>S2 KESEHATAN MASYARAKAT</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Aktif</label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Simpan User</button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
@endsection