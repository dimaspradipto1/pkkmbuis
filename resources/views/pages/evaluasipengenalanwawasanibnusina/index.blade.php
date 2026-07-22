@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Evaluasi Pengenalan Wawasan Sejarah Ibnu Sina</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Evaluasi</li>
                <li class="breadcrumb-item active">Pengenalan Wawasan Sejarah Ibnu Sina</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between align-items-center">
                            <span>Daftar Response Evaluasi</span>
                            <a href="{{ route('evaluasipengenalanwawasanibnusina.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Isi Evaluasi
                            </a>
                        </h5>
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
