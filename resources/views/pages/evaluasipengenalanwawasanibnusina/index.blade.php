@extends('dashboard.template')

@section('content')
    @php
        if (!function_exists('getKategoriBadgeStyle')) {
            function getKategoriBadgeStyle($kat) {
                $k = strtolower(trim($kat));
                if (str_contains($k, 'sangat baik')) {
                    return 'bg-success text-white fw-bold shadow-sm';
                } elseif (str_contains($k, 'kurang baik')) {
                    return 'bg-warning text-dark fw-bold shadow-sm';
                } elseif (str_contains($k, 'tidak baik')) {
                    return 'bg-danger text-white fw-bold shadow-sm';
                } elseif (str_contains($k, 'baik')) {
                    return 'bg-primary text-white fw-bold shadow-sm';
                }
                return 'bg-secondary text-white fw-bold';
            }
        }
    @endphp

    <div class="pagetitle d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Evaluasi Pengenalan Wawasan Sejarah Ibnu Sina</h1>
            <nav>
                <ol class="breadcrumb mb-0 extra-small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Evaluasi</li>
                    <li class="breadcrumb-item active">Pengenalan Wawasan Sejarah Ibnu Sina</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('evaluasi.export', 1) }}" class="btn btn-success btn-sm shadow-sm rounded-pill px-3 py-2">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('evaluasipengenalanwawasanibnusina.create') }}" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3 py-2">
                <i class="bi bi-plus-lg me-1"></i> Isi Evaluasi
            </a>
        </div>
    </div>

    <section class="section">
        @include('partials.evaluasi-stats')

        {{-- Individual Responses DataTable --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 fs-6">
                            <i class="bi bi-list-check text-primary"></i>
                            Daftar Response Evaluasi Mahasiswa
                        </h6>
                        <div class="table-responsive">
                            {{ $dataTable->table() }}
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
