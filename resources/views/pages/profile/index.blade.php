@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>My Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">My Profile</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <!-- Left Card: Profile Overview -->
            <div class="col-xl-4 col-lg-5">
                <div class="card text-center p-4">
                    <div class="card-body profile-card d-flex flex-column align-items-center justify-content-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=00A551&color=fff&size=120&rounded=true" alt="Profile" class="rounded-circle mb-3 shadow-sm border border-3 border-success">
                        <h4 class="fw-bold mb-1" style="color: #012970;">{{ $user->name }}</h4>
                        <p class="text-muted mb-2">{{ $user->email ?? '-' }}</p>
                        
                        <div class="mt-2">
                            <span class="badge bg-success text-uppercase px-3 py-2 fs-7 rounded-pill shadow-sm">
                                <i class="bi bi-shield-check me-1"></i> {{ $user->role }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Card: Profile Edit Form -->
            <div class="col-xl-8 col-lg-7">
                <div class="card p-3">
                    <div class="card-body pt-3">
                        <h5 class="card-title mb-4"><i class="bi bi-person-lines-fill me-2 text-success"></i>Detail Profil</h5>
                        
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label fw-bold">Nama Lengkap</label>
                                <div class="col-md-8">
                                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label fw-bold">Alamat Email</label>
                                <div class="col-md-8">
                                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label fw-bold">Nomor Registrasi</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control bg-light" value="{{ $user->nomor_registrasi }}" readonly disabled>
                                    <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Nomor registrasi tidak dapat diubah.</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label fw-bold">Peran (Role)</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control bg-light text-uppercase" value="{{ $user->role }}" readonly disabled>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label class="col-md-4 col-form-label fw-bold">Status Akun</label>
                                <div class="col-md-8 d-flex align-items-center">
                                    @if($user->is_active)
                                        <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i> Nonaktif</span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
