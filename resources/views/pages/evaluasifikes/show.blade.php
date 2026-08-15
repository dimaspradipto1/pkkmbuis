@extends('dashboard.template')

@section('content')
<style>
    .eval-container { max-width: 850px; margin: 0 auto; }
    .eval-card { background: #ffffff; border-radius: 10px; border: 1px solid #e1e7ec; box-shadow: 0 3px 10px rgba(0,0,0,0.03); padding: 20px 24px; margin-bottom: 16px; }
    .eval-header-card { background-color: #823ca2 !important; color: #ffffff; border-radius: 10px 10px 0 0; padding: 16px 24px; font-weight: 700; font-size: 1.1rem; border-top: 4px solid #ff9c00; }
    .eval-header-body { background-color: #ffffff; border-radius: 0 0 10px 10px; border: 1px solid #e1e7ec; border-top: none; padding: 20px 24px; margin-bottom: 24px; box-shadow: 0 3px 10px rgba(0,0,0,0.03); }
    .eval-text-break { word-break: break-word; word-wrap: break-word; overflow-wrap: anywhere; white-space: pre-wrap; }
    .score-badge { font-size: 1.1rem; font-weight: 700; padding: 6px 16px; border-radius: 20px; background-color: #823ca2 !important; color: #fff; }
</style>

<div class="pagetitle">
    <h1>Detail Evaluasi FIKes (Fakultas Ilmu Kesehatan)</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('evaluasifikes.index') }}">Evaluasi</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="eval-container">
        <div class="eval-header-card">DETAIL EVALUASI FIKES (FAKULTAS ILMU KESEHATAN)</div>
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
                                 $evaluasi->q11 + $evaluasi->q12 + $evaluasi->q13 + $evaluasi->q14 + $evaluasi->q15 +
                                 $evaluasi->q16 + $evaluasi->q17 + $evaluasi->q18 + $evaluasi->q19 + $evaluasi->q20 +
                                 $evaluasi->q21 + $evaluasi->q22;
                        $avg = round($total / 22, 2);
                    @endphp
                    <span class="text-muted d-block mb-1">Rata-Rata Skala Penilaian</span>
                    <span class="badge bg-primary score-badge">{{ $avg }} / 4</span>
                </div>
            </div>
        </div>

        {{-- Section 1: Pemateri & Materi --}}
        <div class="eval-header-card mb-0">PENILAIAN EVALUASI PEMATERI & MATERI</div>
        @foreach($questions as $key => $questionText)
            @if(in_array($key, ['q1', 'q2', 'q3', 'q4', 'q5']))
                <div class="eval-card" style="{{ $loop->last ? 'border-radius:0 0 10px 10px; margin-bottom:24px;' : 'border-radius:0;' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-dark">{{ $questionText }}</span>
                        <span class="badge bg-info text-white px-3 py-2" style="font-size: 0.95rem;">Skala: {{ $evaluasi->$key }} / 4</span>
                    </div>
                </div>
            @endif
        @endforeach

        {{-- Section 3: Saran per Pemateri --}}
        <div class="eval-header-card mb-0">SARAN DAN MASUKAN KEPADA MASING-MASING PEMATERI</div>
        @foreach($saranFields as $fieldKey => $fieldLabel)
            <div class="eval-card" style="{{ $loop->last ? 'border-radius:0 0 10px 10px; margin-bottom:24px;' : 'border-radius:0;' }}">
                <span class="fw-semibold text-dark d-block mb-2">{{ $fieldLabel }}:</span>
                <div class="p-3 bg-light rounded border eval-text-break">{{ $evaluasi->$fieldKey ?: '-' }}</div>
            </div>
        @endforeach

        {{-- Section 4: Fasilitas dan Penyelenggara --}}
        <div class="eval-header-card mb-0">FASILITAS DAN PENYELENGARA</div>
        @foreach($questions as $key => $questionText)
            @if(in_array($key, ['q14','q15','q16','q17','q18']))
                <div class="eval-card" style="{{ $loop->last ? 'border-radius:0 0 10px 10px; margin-bottom:24px;' : 'border-radius:0;' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-dark">{{ $questionText }}</span>
                        <span class="badge bg-success text-white px-3 py-2" style="font-size: 0.95rem;">Skala: {{ $evaluasi->$key }} / 4</span>
                    </div>
                </div>
            @endif
        @endforeach

        {{-- Section 5: Sarana dan Prasarana --}}
        <div class="eval-header-card mb-0">SARANA DAN PRASARANA</div>
        @foreach($questions as $key => $questionText)
            @if(in_array($key, ['q19','q20','q21','q22']))
                <div class="eval-card" style="{{ $loop->last ? 'border-radius:0 0 10px 10px; margin-bottom:24px;' : 'border-radius:0;' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-dark">{{ $questionText }}</span>
                        <span class="badge bg-success text-white px-3 py-2" style="font-size: 0.95rem;">Skala: {{ $evaluasi->$key }} / 4</span>
                    </div>
                </div>
            @endif
        @endforeach

        {{-- Saran Panitia --}}
        <div class="eval-header-card mb-0">SARAN DAN MASUKAN UNTUK PANITIA PELAKSANA</div>
        <div class="eval-card" style="border-radius:0 0 10px 10px; margin-bottom:24px;">
            <div class="p-3 bg-light rounded border eval-text-break">{{ $evaluasi->saran_panitia ?: '-' }}</div>
        </div>

        <div class="d-flex justify-content-between my-4">
            <a href="{{ route('evaluasifikes.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            @if(Auth::user()->role != 'mahasiswa')
                <a href="{{ route('evaluasifikes.edit', $evaluasi->id) }}" class="btn btn-primary px-4">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            @endif
        </div>
    </div>
</section>
@endsection
