@extends('dashboard.template')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">Terima Kasih!</h3>
                    <p class="text-muted mb-4">
                        Anda telah mengisi <strong>Evaluasi Pelayanan Kemahasiswaan & Pusat Prestasi</strong>.
                    </p>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-house me-1"></i> Kembali ke Dashboard
                        </a>
                        <a href="{{ route('evaluasipelayanankemahasiswaanpusatprestasi.edit', $evaluasi->id) }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-pencil me-1"></i> Edit Jawaban Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
