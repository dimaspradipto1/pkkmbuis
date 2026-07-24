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
        background-color: #356B3A !important;
        color: #ffffff;
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
        accent-color: #356B3A;
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
        border-bottom-color: #356B3A;
        box-shadow: none;
    }
    .eval-action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        margin-bottom: 40px;
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
        background-color: #356B3A;
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
        text-align: center;
    }
    .eval-top-divider {
        border-top: 1px solid #eeeeee;
        margin-bottom: 14px;
    }
    .eval-required-note {
        color: #d9534f;
        font-size: 0.85rem;
    }
    .eval-text-break {
        word-break: break-word;
        word-wrap: break-word;
        overflow-wrap: anywhere;
    }

    @media (max-width: 576px) {
        .eval-top-body {
            padding: 16px 16px;
        }
        .eval-top-title {
            font-size: 1.1rem;
            margin-bottom: 14px;
        }
        .eval-required-note {
            font-size: 0.78rem;
        }
        .eval-card {
            padding: 16px 14px;
            margin-bottom: 14px;
            border-radius: 8px;
        }
        .eval-header-card {
            padding: 12px 16px;
            font-size: 0.95rem;
        }
        .eval-header-body {
            padding: 14px 16px;
            margin-bottom: 16px;
        }
        .eval-question-title {
            font-size: 0.9rem;
            margin-bottom: 16px;
        }
        .eval-scale-wrapper {
            gap: 2px;
            padding: 4px 0;
        }
        .eval-scale-label.left, .eval-scale-label.right {
            width: 72px;
            min-width: 72px;
            font-size: 0.72rem;
            text-align: center;
            color: #64748b;
        }
        .eval-radio-options {
            justify-content: space-between;
            margin: 0 4px;
        }
        .eval-radio-col {
            min-width: 26px;
        }
        .eval-num {
            font-size: 0.85rem;
            margin-bottom: 6px;
        }
        .eval-radio-col input[type="radio"] {
            width: 18px;
            height: 18px;
        }
        .eval-action-bar {
            flex-direction: column;
            gap: 14px;
            align-items: stretch;
            text-align: center;
        }
        .eval-action-bar > div {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .eval-action-bar .btn {
            flex-grow: 1;
        }
    }
</style>

<div class="pagetitle">
    <h1>Edit Evaluasi Perpustakaan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('evaluasiperpustakaan.index') }}">Evaluasi</a></li>
            <li class="breadcrumb-item active">Edit Form</li>
        </ol>
    </nav>
</div>

<div class="container-fluid py-3">
    <div class="eval-container">
        <form action="{{ route('evaluasiperpustakaan.update', $evaluasi->id) }}" method="POST" id="evalForm">
            @csrf
            @method('PUT')

            <!-- Main Header Card -->
            <div class="eval-top-card">
                <div class="eval-top-bar"></div>
                <div class="eval-top-body">
                    <h2 class="eval-top-title">
                        EDIT EVALUASI PENYAMPAIAN MATERI PKKMB {{ date('Y') }}<br>
                        (Perpustakaan)
                    </h2>
                    <div class="eval-top-divider"></div>
                    <div class="eval-required-note">
                        * Menunjukkan pertanyaan yang wajib diisi
                    </div>
                </div>
            </div>

            <!-- Header Section 1: Pemateri -->
            <div class="eval-header-card">
                PENILAIAN EVALUASI PEMATERI
            </div>
            <div class="eval-header-body">
                <p class="mb-1 text-secondary" style="font-size: 0.95rem;">
                    Berikan tanggapan Anda terhadap keseluruhan Pemateri yang Anda ikuti.
                </p>
                <p class="mb-0 text-secondary" style="font-size: 0.95rem;">
                    1 = Sangat tidak setuju 4 = Sangat setuju
                </p>
            </div>

            <!-- Questions q1 to q8 (Pemateri) -->
            @foreach($questions as $key => $questionText)
                @if(in_array($key, ['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8']))
                    <div class="eval-card">
                        <div class="eval-question-title">
                            {{ $questionText }} <span class="req">*</span>
                        </div>

                        <div class="eval-scale-wrapper">
                            <div class="eval-scale-label left">Sangat tidak setuju</div>
                            <div class="eval-radio-options">
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="eval-radio-col">
                                        <span class="eval-num">{{ $i }}</span>
                                        <input type="radio" 
                                               name="{{ $key }}" 
                                               id="{{ $key }}_{{ $i }}" 
                                               value="{{ $i }}" 
                                               {{ old($key, $evaluasi->$key) == $i ? 'checked' : '' }} 
                                               required>
                                    </div>
                                @endfor
                            </div>
                            <div class="eval-scale-label right">Sangat setuju</div>
                        </div>
                        @error($key)
                            <div class="text-danger mt-2 small text-center">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            @endforeach

            <!-- Header Section 2: Isi Materi -->
            <div class="eval-header-card">
                PENILAIAN EVALUASI ISI MATERI
            </div>
            <div class="eval-header-body">
                <p class="mb-1 text-secondary" style="font-size: 0.95rem;">
                    Berikan tanggapan Anda terhadap keseluruhan Materi disajikan yang Anda ikuti.
                </p>
                <p class="mb-0 text-secondary" style="font-size: 0.95rem;">
                    1 = Sangat tidak setuju 4 = Sangat setuju
                </p>
            </div>

            <!-- Questions q9 to q13 (Isi Materi) -->
            @foreach($questions as $key => $questionText)
                @if(in_array($key, ['q9', 'q10', 'q11', 'q12', 'q13']))
                    <div class="eval-card">
                        <div class="eval-question-title">
                            {{ $questionText }} <span class="req">*</span>
                        </div>

                        <div class="eval-scale-wrapper">
                            <div class="eval-scale-label left">Sangat tidak setuju</div>
                            <div class="eval-radio-options">
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="eval-radio-col">
                                        <span class="eval-num">{{ $i }}</span>
                                        <input type="radio" 
                                               name="{{ $key }}" 
                                               id="{{ $key }}_{{ $i }}" 
                                               value="{{ $i }}" 
                                               {{ old($key, $evaluasi->$key) == $i ? 'checked' : '' }} 
                                               required>
                                    </div>
                                @endfor
                            </div>
                            <div class="eval-scale-label right">Sangat setuju</div>
                        </div>
                        @error($key)
                            <div class="text-danger mt-2 small text-center">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            @endforeach

            <!-- Saran & Masukan Card -->
            <div class="eval-card">
                <div class="eval-question-title">
                    Berikan saran dan masukan terhadap pemateri
                </div>
                <div class="mt-3">
                    <textarea name="saran_dan_masukan" 
                              class="form-control eval-input-line eval-text-break @error('saran_dan_masukan') is-invalid @enderror" 
                              rows="2" 
                              placeholder="Jawaban Anda">{{ old('saran_dan_masukan', $evaluasi->saran_dan_masukan) }}</textarea>
                    @error('saran_dan_masukan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="eval-action-bar">
                <div>
                    <a href="{{ route('evaluasiperpustakaan.index') }}" class="btn btn-outline-secondary px-4 me-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        Update Evaluasi
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
