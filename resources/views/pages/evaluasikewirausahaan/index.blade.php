@extends('dashboard.template')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Evaluasi Kewirausahaan</h1>
            <nav>
                <ol class="breadcrumb mb-0 extra-small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Evaluasi</li>
                    <li class="breadcrumb-item active">Kewirausahaan</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('evaluasikewirausahaan.create') }}" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3 py-2">
            <i class="bi bi-plus-lg me-1"></i> Isi Evaluasi
        </a>
    </div>

    <section class="section">
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
    {{ $dataTable->scripts() }}
@endpush
