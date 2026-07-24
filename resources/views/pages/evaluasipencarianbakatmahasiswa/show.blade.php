@extends('dashboard.template')

@section('content')
<style>
    .eval-container { max-width: 850px; margin: 0 auto; }
    .eval-card { background: #ffffff; border-radius: 10px; border: 1px solid #e1e7ec; box-shadow: 0 3px 10px rgba(0,0,0,0.03); padding: 20px 24px; margin-bottom: 16px; }
    .eval-header-card { background-color: #356B3A !important; color: #ffffff; border-radius: 10px 10px 0 0; padding: 16px 24px; font-weight: 700; font-size: 1.1rem; }
    .eval-header-body { background-color: #ffffff; border-radius: 0 0 10px 10px; border: 1px solid #e1e7ec; border-top: none; padding: 20px 24px; margin-bottom: 24px; box-shadow: 0 3px 10px rgba(0,0,0,0.03); }
    .eval-text-break { word-break: break-word; word-wrap: break-word; overflow-wrap: anywhere; white-space: pre-wrap; }
    .score-badge { font-size: 1.1rem; font-weight: 700; padding: 6px 16px; border-radius: 20px; }
    @media (max-width: 576px) {
        .eval-card { padding: 14px 16px; margin-bottom: 12px; }
        .eval-header-card { padding: 12px 16px; font-size: 0.95rem; }
        .eval-header-body { padding: 14px 16px; margin-bottom: 16px; }
    }
</style>

<div class="pagetitle">
    <h1>Detail Evaluasi Pencarian Bakat Mahasiswa</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('evaluasipencarianbakatmahasiswa.index') }}">Evaluasi</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="eval-container">
        <!-- Header Banner Card -->
        <div class="eval-header-card">
            DETAIL EVALUASI PENCARIAN BAKAT MAHASISWA
        </div>
        <div class="eval-header-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Nama Mahasiswa:</strong> {{ $evaluasi->user->name ?? '-' }}</p>
                    <p class="mb-1"><strong>NPM / ID Pendaftar:</strong> {{ $evaluasi->user->id_pendaftar ?? '-' }}</p>
                    <p class="mb-0"><strong>Kelompok:</strong> {{ $evaluasi->user->kelompok->nama_kelompok ?? '-' }}</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    @php
                        $total = $evaluasi->q1 + $evaluasi->q2 + $evaluasi->q3 + $evaluasi->q4 + $evaluasi->q5 +
                                 $evaluasi->q6 + $evaluasi->q7 + $evaluasi->q8 + $evaluasi->q9 + $evaluasi->q10 +
                                 $evaluasi->q11 + $evaluasi->q12 + $evaluasi->q13;
                        $avg = round($total / 13, 2);
                    @endphp
                    <span class="text-muted d-block mb-1">Rata-Rata Skala Penilaian</span>
                    <span class="badge bg-primary score-badge">{{ $avg }} / 4</span>
                </div>
            </div>
        </div>

        <!-- Questions Answers -->
        @foreach($questions as $key => $questionText)
            <div class="eval-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-semibold text-dark">{{ $loop->iteration }}. {{ $questionText }}</span>
                    </div>
                    <div>
                        <span class="badge bg-info text-white px-3 py-2" style="font-size: 0.95rem;">
                            Skala: {{ $evaluasi->$key }} / 4
                        </span>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Saran & Masukan Card -->
        <div class="eval-card">
            <span class="fw-semibold text-dark d-block mb-2">Saran dan Masukan terhadap Pemateri:</span>
            <div class="p-3 bg-light rounded border eval-text-break">
                {{ $evaluasi->saran_dan_masukan ?: '-' }}
            </div>
        </div>

        <div class="d-flex justify-content-between my-4">
            <a href="{{ route('evaluasipencarianbakatmahasiswa.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <div>
                @if(Auth::user()->role != 'mahasiswa')
                    <a href="{{ route('evaluasipencarianbakatmahasiswa.edit', $evaluasi->id) }}" class="btn btn-primary px-4 me-2">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
