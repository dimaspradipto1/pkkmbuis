@extends('dashboard.template')

@section('content')
<style>
    .eval-completed-container {
        max-width: 680px;
        margin: 30px auto;
    }
    .eval-completed-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #dadce0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    .eval-completed-top-bar {
        height: 10px;
        background-color: #1b7a42; /* Warna Hijau UIS */
    }
    .eval-completed-body {
        padding: 32px 36px;
    }
    .eval-completed-title {
        font-size: 2rem;
        font-weight: 500;
        color: #202124;
        letter-spacing: -0.5px;
        margin-bottom: 16px;
        line-height: 1.25;
    }
    .eval-completed-subtitle {
        font-size: 0.95rem;
        color: #3c4043;
        line-height: 1.6;
        margin-bottom: 28px;
        text-align: justify; /* Rata Kiri Kanan */
    }
    .eval-completed-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Full width buttons on mobile */
    @media (max-width: 576px) {
        .eval-completed-container {
            margin: 15px auto;
        }
        .eval-completed-body {
            padding: 24px 20px;
        }
        .eval-completed-title {
            font-size: 1.5rem;
            margin-bottom: 12px;
        }
        .eval-completed-subtitle {
            font-size: 0.88rem;
            margin-bottom: 20px;
        }
        .eval-completed-actions {
            flex-direction: column;
            width: 100%;
            gap: 10px;
        }
        .eval-completed-actions .btn {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }
</style>

<div class="pagetitle">
    <h1>Evaluasi Pengenalan Wawasan Sejarah Ibnu Sina</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('evaluasipengenalanwawasanibnusina.index') }}">Evaluasi</a></li>
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
                    Anda hanya dapat mengisi formulir evaluasi ini satu kali. Tanggapan Anda untuk <strong>Pengenalan Wawasan Sejarah Ibnu Sina</strong> telah berhasil disimpan dalam sistem.
                </p>
                <div class="eval-completed-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-house me-1"></i> Kembali ke Dashboard
                    </a>
                    <a href="{{ route('evaluasipengenalanwawasanibnusina.show', $evaluasi->id) }}" class="btn btn-success text-white px-4 py-2" style="background-color: #1b7a42; border-color: #1b7a42;">
                        <i class="bi bi-eye me-1"></i> Lihat Tanggapan Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
