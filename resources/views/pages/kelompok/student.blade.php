@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Daftar Kelompok PKKMB</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Daftar Kelompok</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <!-- My Kelompok Card -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm border-0 bg-primary text-white">
                    <div class="card-body p-4">
                        @if($myKelompok)
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-white text-primary fw-bold px-3 py-1 mb-2">KELOMPOK ANDA</span>
                                    <h3 class="fw-bold text-white mb-1">{{ $myKelompok->nama_kelompok }}</h3>
                                    <p class="mb-0 text-white-50">
                                        <i class="bi bi-person-badge me-1"></i> Pendamping / Kakak Leting:
                                        <strong class="text-white">{{ $myKelompok->pendamping->name ?? 'Belum Ditentukan' }}</strong>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="fs-1 fw-bold">{{ $myKelompok->anggota->count() }}</span>
                                    <div class="small text-white-50">Anggota Tim</div>
                                </div>
                            </div>
                        @else
                            <div class="py-2">
                                <h5 class="fw-bold text-white mb-1"><i class="bi bi-exclamation-circle me-2"></i> Anda Belum Memiliki Kelompok</h5>
                                <p class="mb-0 text-white-50">Silakan hubungi Panitia / Kakak Leting untuk informasi pembagian kelompok PKKMB Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($myKelompok)
                <!-- My Teammates List -->
                <div class="col-lg-12 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h5 class="card-title p-0 mb-3 fw-bold text-primary">
                                <i class="bi bi-people-fill me-2"></i> Teman Satu Kelompok ({{ $myKelompok->nama_kelompok }})
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">NO</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>ID Pendaftar</th>
                                            <th>Program Studi</th>
                                            <th>Fakultas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($myKelompok->anggota as $index => $member)
                                            <tr class="{{ $member->id == Auth::id() ? 'table-primary fw-bold' : '' }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    {{ $member->name }}
                                                    @if($member->id == Auth::id())
                                                        <span class="badge bg-primary ms-1">Saya</span>
                                                    @endif
                                                </td>
                                                <td><small class="text-muted">{{ $member->id_pendaftar }}</small></td>
                                                <td><small>{{ $member->program_studi ?? '-' }}</small></td>
                                                <td><small>{{ $member->fakultas ?? '-' }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- All Groups Accordion/List -->
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title p-0 mb-4 fw-bold">Seluruh Kelompok PKKMB</h5>

                        <div class="row g-3">
                            @foreach($kelompoks as $k)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border shadow-none rounded-3 {{ ($myKelompok && $myKelompok->id == $k->id) ? 'border-primary border-2 bg-light' : '' }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $k->nama_kelompok }}</h6>
                                                <span class="badge bg-secondary rounded-pill">{{ $k->anggota_count }} Anggota</span>
                                            </div>
                                            <p class="small text-muted mb-2">
                                                <i class="bi bi-person-badge me-1"></i> Pendamping:
                                                <strong>{{ $k->pendamping->name ?? '-' }}</strong>
                                            </p>
                                            <a href="{{ route('kelompok.show', $k->slug) }}" class="btn btn-sm btn-outline-primary w-100 mt-2">
                                                <i class="bi bi-eye me-1"></i> Lihat Anggota Kelompok
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
