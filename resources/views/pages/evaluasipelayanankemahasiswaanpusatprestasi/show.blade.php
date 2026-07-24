@extends('dashboard.template')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Detail Response Evaluasi Pelayanan Kemahasiswaan & Pusat Prestasi</h1>
        <nav>
            <ol class="breadcrumb mb-0 extra-small">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('evaluasipelayanankemahasiswaanpusatprestasi.index') }}">Evaluasi</a></li>
                <li class="breadcrumb-item active">Detail Response</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('evaluasipelayanankemahasiswaanpusatprestasi.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<section class="section">
    <div class="row g-4">
        {{-- Student Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $evaluasi->user ? $evaluasi->user->name : '-' }}</h5>
                    <p class="text-muted small mb-2">{{ $evaluasi->user ? $evaluasi->user->id_pendaftar : '-' }}</p>
                    <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 px-3 py-1.5 rounded-pill mb-3">
                        {{ ($evaluasi->user && $evaluasi->user->kelompok) ? $evaluasi->user->kelompok->nama_kelompok : 'Tanpa Kelompok' }}
                    </span>

                    <hr class="my-3">

                    <div class="text-start extra-small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Fakultas:</span>
                            <span class="fw-semibold text-dark">{{ $evaluasi->user->fakultas ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Program Studi:</span>
                            <span class="fw-semibold text-dark">{{ $evaluasi->user->program_studi ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted">Waktu Mengisi:</span>
                            <span class="fw-semibold text-dark">{{ $evaluasi->created_at ? $evaluasi->created_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Response Detail Table --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-journal-check text-primary"></i>
                        Detail Jawaban Kuesioner (Skala 1 - 4)
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border text-start mb-0 extra-small">
                            <thead class="bg-light text-dark fw-bold text-uppercase">
                                <tr>
                                    <th class="text-center py-2" style="width: 50px;">NO</th>
                                    <th class="py-2">ITEM PERTANYAAN</th>
                                    <th class="text-center py-2" style="width: 120px;">NILAI (1-4)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $key => $title)
                                    <tr>
                                        <td class="text-center fw-bold py-2">{{ loopIndex($loop->index) }}</td>
                                        <td class="py-2">{{ $title }}</td>
                                        <td class="text-center py-2">
                                            @php $val = $evaluasi->$key; @endphp
                                            <span class="badge {{ $val >= 3 ? 'bg-success text-white' : ($val == 2 ? 'bg-warning text-dark' : 'bg-danger text-white') }} px-3 py-1 fw-bold fs-6">
                                                {{ $val }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-dark mb-1 small"><i class="bi bi-chat-left-text text-primary me-1"></i> Saran dan Masukan:</h6>
                        <p class="text-secondary small mb-0">{{ $evaluasi->saran_dan_masukan ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@php
    function loopIndex($i) {
        return $i + 1;
    }
@endphp
