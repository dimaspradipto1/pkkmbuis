@extends('dashboard.template')

@section('content')
<style>
    .eval-completed-container {
        max-width: 750px;
        margin: 30px auto;
    }
    .eval-completed-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #dadce0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    .eval-completed-top-bar {
        height: 12px;
        background-color: #356B3A;
    }
    .eval-completed-body {
        padding: 36px 40px;
        text-align: center;
    }
    .eval-completed-title {
        font-size: 2.2rem;
        font-weight: 500;
        color: #202124;
        letter-spacing: -0.5px;
        margin-bottom: 18px;
        line-height: 1.25;
        text-align: center;
    }
    .eval-completed-subtitle {
        font-size: 1.05rem;
        color: #3c4043;
        line-height: 1.6;
        margin-bottom: 32px;
        text-align: center;
    }
    .eval-completed-actions {
        display: flex;
        gap: 14px;
        align-items: center;
        justify-content: center;
    }
    .btn-eval-outline {
        border: 1px solid #747775;
        color: #3c4043;
        background-color: #ffffff;
        border-radius: 8px;
        padding: 10px 22px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-eval-outline:hover {
        background-color: #f8f9fa;
        color: #1f1f1f;
        border-color: #5f6368;
    }
    .btn-eval-primary {
        background-color: #356B3A;
        border: 1px solid #356B3A;
        color: #ffffff;
        border-radius: 8px;
        padding: 10px 22px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-eval-primary:hover {
        background-color: #2a552e;
        border-color: #2a552e;
    }

    @media (max-width: 576px) {
        .eval-completed-container {
            margin: 15px auto;
        }
        .eval-completed-body {
            padding: 24px 20px;
        }
        .eval-completed-title {
            font-size: 1.6rem;
            margin-bottom: 14px;
        }
        .eval-completed-subtitle {
            font-size: 0.92rem;
            margin-bottom: 24px;
        }
        .eval-completed-actions {
            flex-direction: column;
            width: 100%;
            gap: 12px;
        }
        .eval-completed-actions a {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }
</style>

<div class="pagetitle">
    <h1>Evaluasi Perpustakaan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('evaluasiperpustakaan.index') }}">Evaluasi</a></li>
            <li class="breadcrumb-item active">Status Evaluasi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="eval-completed-container">
        <div class="eval-completed-card">
            <div class="eval-completed-top-bar"></div>
            <div class="eval-completed-body">
                <h1 class="eval-completed-title">Anda sudah menanggapi.</h1>
                <p class="eval-completed-subtitle">
                    Anda hanya dapat mengisi formulir evaluasi ini satu kali. Tanggapan Anda untuk <strong>Perpustakaan</strong> telah berhasil disimpan dalam sistem.
                </p>
                <div class="eval-completed-actions">
                    <a href="{{ route('dashboard') }}" class="btn-eval-outline">
                        <i class="bi bi-house me-2"></i> Kembali ke Dashboard
                    </a>
                    @if(Auth::user()->role != 'mahasiswa')
                        <a href="{{ route('evaluasiperpustakaan.show', $evaluasi->id) }}" class="btn-eval-primary">
                            <i class="bi bi-eye me-2"></i> Lihat Tanggapan Saya
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
