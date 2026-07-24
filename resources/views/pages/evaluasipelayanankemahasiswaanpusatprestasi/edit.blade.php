@extends('dashboard.template')

@section('content')
<style>
    .eval-container {
        max-width: 850px;
        margin: 0 auto;
    }
    .eval-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e1e7ec;
        box-shadow: 0 3px 10px rgba(0,0,0,0.03);
        padding: 24px 28px;
        margin-bottom: 20px;
    }
    .eval-header-card {
        background-color: #90cbd7 !important;
        color: #1a202c;
        border-radius: 10px 10px 0 0;
        padding: 16px 24px;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }
    .eval-header-body {
        background-color: #ffffff;
        border-radius: 0 0 10px 10px;
        border: 1px solid #e1e7ec;
        border-top: none;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.03);
    }
    .eval-question-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 20px;
        line-height: 1.4;
    }
    .eval-question-title span.req {
        color: #e53e3e;
    }
    .eval-scale-wrapper {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        max-width: 650px;
        margin: 0 auto;
        padding: 10px 0;
    }
    .eval-scale-label {
        font-size: 0.85rem;
        color: #4a5568;
        font-weight: 500;
        line-height: 1.3;
    }
    .eval-scale-label.left {
        text-align: right;
        width: 130px;
        padding-right: 12px;
    }
    .eval-scale-label.right {
        text-align: left;
        width: 130px;
        padding-left: 12px;
    }
    .eval-radio-options {
        display: flex;
        justify-content: space-around;
        flex-grow: 1;
    }
    .eval-radio-col {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .eval-num {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
    }
    .eval-radio-col input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #2b7082;
        margin: 0;
    }
    .eval-input-line {
        border: none;
        border-bottom: 1px solid #cbd5e0;
        border-radius: 0;
        padding: 8px 0;
        width: 100%;
        background: transparent;
    }
    .eval-input-line:focus {
        outline: none;
        border-bottom: 2px solid #2b7082;
    }
    .eval-top-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e1e7ec;
        box-shadow: 0 3px 10px rgba(0,0,0,0.03);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .eval-top-bar {
        height: 10px;
        background-color: #90cbd7;
    }
    .eval-top-body {
        padding: 24px 28px;
    }
    .eval-top-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.5;
        margin-bottom: 16px;
    }
    .eval-top-divider {
        border-top: 1px solid #eeeeee;
        margin-bottom: 14px;
    }
    .eval-required-note {
        color: #d9534f;
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid py-3">
    <div class="eval-container">
        
        <form action="{{ route('evaluasipelayanankemahasiswaanpusatprestasi.update', $evaluasi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Main Header Card -->
            <div class="eval-top-card">
                <div class="eval-top-bar"></div>
                <div class="eval-top-body">
                    <h2 class="eval-top-title">
                        EVALUASI PENYAMPAIAN MATERI PKKMB {{ date('Y') }}<br>
                        (Pelayanan Kemahasiswaan & Pusat Prestasi)
                    </h2>
                    <div class="eval-top-divider"></div>
                    <div class="eval-required-note">
                        * Menunjukkan pertanyaan yang wajib diisi
                    </div>
                </div>
            </div>

            {{-- SECTION 1: PEMATERI --}}
            <div class="eval-header-card">
                PENILAIAN EVALUASI PEMATERI
            </div>
            <div class="eval-header-body">
                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                    Berikan tanggapan Anda terhadap keseluruhan Pemateri yang Anda ikuti.<br>
                    <strong>1 = Sangat tidak setuju</strong> &nbsp;|&nbsp; <strong>4 = Sangat setuju</strong>
                </p>
            </div>

            @foreach([
                'q1' => 'Pemateri Memahami materi',
                'q2' => 'Pamateri Berinteraksi dengan peserta',
                'q3' => 'Pemaparan jelas dan mudah dipahami',
                'q4' => 'Pamateri Mampu mengalokasikan waktu',
                'q5' => 'Pamateri Memberikan motivasi dan feedback',
                'q6' => 'Metode dan alat dalam penyajian menarik',
                'q7' => 'Pamateri Memberikan kesempatan berpartisipasi',
                'q8' => 'Pamateri Menjawab pertanyaan dengan benar dan jelas'
            ] as $key => $title)
                <div class="eval-card">
                    <div class="eval-question-title">
                        {{ $title }} <span class="req">*</span>
                    </div>
                    <div class="eval-scale-wrapper">
                        <div class="eval-scale-label left">Sangat tidak setuju</div>
                        <div class="eval-radio-options">
                            @for($i = 1; $i <= 4; $i++)
                                <div class="eval-radio-col">
                                    <span class="eval-num">{{ $i }}</span>
                                    <input type="radio" name="{{ $key }}" value="{{ $i }}" {{ old($key, $evaluasi->$key) == $i ? 'checked' : '' }} required>
                                </div>
                            @endfor
                        </div>
                        <div class="eval-scale-label right">Sangat setuju</div>
                    </div>
                    @error($key)
                        <div class="text-danger small mt-2 text-center">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            {{-- SECTION 2: ISI MATERI --}}
            <div class="eval-header-card mt-5">
                PENILAIAN EVALUASI ISI MATERI
            </div>
            <div class="eval-header-body">
                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                    Berikan tanggapan Anda terhadap keseluruhan Materi disajikan yang Anda ikuti.<br>
                    <strong>1 = Sangat tidak setuju</strong> &nbsp;|&nbsp; <strong>4 = Sangat setuju</strong>
                </p>
            </div>

            @foreach([
                'q9'  => 'Materi yang di sajikan Informatif',
                'q10' => 'Materi yang di sajikan Mudah dipahami',
                'q11' => 'Materi yang di sajikan Bermanfaat dan sesuai kebutuhan',
                'q12' => 'Materi yang di sajikan Relevan dengan Kegiatan PKKMB',
                'q13' => 'Materi yang di sajikan Mendukung peningkatan SDM MABA UIS'
            ] as $key => $title)
                <div class="eval-card">
                    <div class="eval-question-title">
                        {{ $title }} <span class="req">*</span>
                    </div>
                    <div class="eval-scale-wrapper">
                        <div class="eval-scale-label left">Sangat tidak setuju</div>
                        <div class="eval-radio-options">
                            @for($i = 1; $i <= 4; $i++)
                                <div class="eval-radio-col">
                                    <span class="eval-num">{{ $i }}</span>
                                    <input type="radio" name="{{ $key }}" value="{{ $i }}" {{ old($key, $evaluasi->$key) == $i ? 'checked' : '' }} required>
                                </div>
                            @endfor
                        </div>
                        <div class="eval-scale-label right">Sangat setuju</div>
                    </div>
                    @error($key)
                        <div class="text-danger small mt-2 text-center">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            {{-- SECTION 3: SARAN DAN MASUKAN --}}
            <div class="eval-card">
                <div class="eval-question-title">
                    Berikan saran dan masukan terhadap pemateri
                </div>
                <div>
                    <input type="text" name="saran_dan_masukan" class="eval-input-line" placeholder="Jawaban Anda" value="{{ old('saran_dan_masukan', $evaluasi->saran_dan_masukan) }}">
                </div>
                @error('saran_dan_masukan')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm fw-semibold" style="background-color: #2b7082; border-color: #2b7082;">
                    <i class="bi bi-check-circle me-1"></i> Perbarui Evaluasi
                </button>
                <a href="{{ route('evaluasipelayanankemahasiswaanpusatprestasi.index') }}" class="btn btn-light border px-4 py-2 rounded-3 text-muted">
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
