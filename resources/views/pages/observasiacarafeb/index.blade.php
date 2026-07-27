@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Observasi Acara Feb</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Observasi Acara Feb</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Observasi Acara Feb</h5>
                            <a href="{{ route('observasiacarafeb.create') }}" class="btn btn-primary btn-sm shadow-sm px-3 text-white">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Observasi
                            </a>
                        </div>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4 rounded-3">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h5 class="card-title mb-0 text-dark fw-bold">
                            <i class="bi bi-info-circle-fill me-2 text-primary"></i> Petunjuk Penggunaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-clock-history text-primary me-1"></i> Waktu (Realisasi)</div>
                                    <div class="text-muted small mb-0">Isi dengan waktu mulai dan selesai kegiatan yang sebenarnya untuk mengukur ketepatan waktu.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-star-fill text-primary me-1"></i> Skala Penilaian</div>
                                    <div class="text-muted small mb-0">Gunakan skala 1 hingga 5 untuk menilai setiap aspek. (1: Sangat Kurang, 2: Kurang, 3: Cukup, 4: Baik, 5: Sangat Baik).</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-journal-text text-primary me-1"></i> Catatan/Keterangan</div>
                                    <div class="text-muted small mb-0">Tuliskan temuan spesifik, kendala yang terjadi, hal-hal positif yang menonjol, atau rekomendasi perbaikan.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-link-45deg text-primary me-1"></i> Dokumentasi</div>
                                    <div class="text-muted small mb-0">Lampirkan link foto/video dokumentasi observasi jika diperlukan.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
@endpush
