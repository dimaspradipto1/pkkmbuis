@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Observasi Acara 2</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Observasi Acara 2</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Observasi Acara 2</h5>
                            <a href="{{ route('observasiacara2.create') }}" class="btn btn-primary btn-sm shadow-sm px-3 text-white">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Observasi
                            </a>
                        </div>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4 rounded-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary me-1"></i> Petunjuk Penggunaan:</h6>
                        <ul class="mb-0 ps-3">
                            <li class="mb-2"><strong>Waktu (Realisasi):</strong> Isi dengan waktu mulai dan selesai kegiatan yang sebenarnya untuk mengukur ketepatan waktu.</li>
                            <li class="mb-2"><strong>Skala Penilaian:</strong> Gunakan skala 1 hingga 5 untuk menilai setiap aspek. (1: Sangat Kurang, 2: Kurang, 3: Cukup, 4: Baik, 5: Sangat Baik).</li>
                            <li class="mb-2"><strong>Catatan/Keterangan:</strong> Tuliskan temuan spesifik, kendala yang terjadi, hal-hal positif yang menonjol, atau rekomendasi perbaikan.</li>
                            <li class="mb-0"><strong>Dokumentasi:</strong> Lampirkan link foto/video dokumentasi observasi jika diperlukan.</li>
                        </ul>
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
