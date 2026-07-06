@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Update Password</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.index') }}">My Profile</a></li>
                <li class="breadcrumb-item active">Update Password</li>
            </ol>
        </nav>
    </div>

    <section class="section profile-password">
        <div class="row">
            <div class="col-xl-8 col-lg-10 mx-auto">
                <div class="card p-3 shadow-sm border-0">
                    <div class="card-body pt-3">
                        <h5 class="card-title mb-4"><i class="bi bi-key-fill me-2 text-warning"></i>Ganti Password</h5>

                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="current_password" class="col-md-4 col-form-label fw-bold">Password Saat Ini</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('current_password', this)">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="new_password" class="col-md-4 col-form-label fw-bold">Password Baru</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('new_password', this)">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="new_password_confirmation" class="col-md-4 col-form-label fw-bold">Konfirmasi Password Baru</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input name="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('profile.index') }}" class="btn btn-secondary px-4 me-2 shadow-sm"><i class="bi bi-x-circle me-1"></i> Batal</a>
                                <button type="submit" class="btn btn-warning text-white px-4 shadow-sm"><i class="bi bi-save me-1"></i> Perbarui Password</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function togglePasswordVisibility(fieldId, button) {
            const inputField = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            if (inputField.type === 'password') {
                inputField.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                inputField.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
    </script>
@endsection
