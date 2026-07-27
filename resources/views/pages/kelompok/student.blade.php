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
                <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg, #4154f1 0%, #2c389e 100%) !important; border-radius: 12px;">
                    <div class="card-body p-4 text-white">
                        @if($myKelompok)
                            @php
                                $pRaw = $myKelompok->pendamping->name ?? 'Belum Ditentukan';
                                // Clean up repetitive "Kakak Pendamping" prefix if present
                                $pClean = preg_replace('/^Kakak Pendamping\s*/i', '', $pRaw);
                                $dClean = $myKelompok->dosenPendampings->pluck('name')->implode(', ') ?: 'Belum Ditentukan';
                            @endphp
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <span class="badge bg-white text-primary fw-bold px-3 py-1 mb-2 shadow-sm" style="font-size: 0.72rem;">KELOMPOK ANDA</span>
                                    <h3 class="fw-bold text-white mb-2 fs-4 fs-md-3">{{ $myKelompok->nama_kelompok }}</h3>
                                    <p class="mb-0 text-white opacity-90 small">
                                        <i class="bi bi-person-badge me-1"></i> Kakak Pendamping:
                                        <strong class="text-white fw-bold">{{ $pClean }}</strong>
                                    </p>
                                    <p class="mb-0 text-white opacity-90 small">
                                        <i class="bi bi-mortarboard me-1"></i> Dosen Pendamping:
                                        <strong class="text-white fw-bold">{{ $dClean }}</strong>
                                    </p>
                                </div>
                                <div class="text-start text-sm-end text-white bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                                    <div class="fs-2 fw-bold text-white lh-1">{{ $myKelompok->anggota->count() }}</div>
                                    <div class="extra-small text-white opacity-75 mt-1">Anggota Tim</div>
                                </div>
                            </div>
                        @else
                            <div class="py-2 text-white">
                                <h5 class="fw-bold text-white mb-1"><i class="bi bi-exclamation-circle me-2"></i> Anda Belum Memiliki Kelompok</h5>
                                <p class="mb-0 text-white-50">Silakan hubungi Panitia / Kakak Pendamping untuk informasi pembagian kelompok PKKMB Anda.</p>
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

        </div>
    </section>
@endsection
