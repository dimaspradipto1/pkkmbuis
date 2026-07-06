@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Data Absensi Kedua</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Absensi Kedua</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Absensi Hari II</h5>
                            <div class="d-flex gap-2">
                                @if (Auth::user()->role != 'mahasiswa')
                                    <button type="button" class="btn btn-outline-dark" onclick="showAttendanceQR(2)">
                                        <i class="bi bi-qr-code me-1"></i> QR Absensi
                                    </button>
                                    <a href="{{ route('absenkedua.create') }}" class="btn btn-primary text-white">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah
                                    </a>
                                @else
                                    <a href="{{ route('absen-scan.index') }}" class="btn btn-success">
                                        <i class="bi bi-qr-code-scan me-1"></i> Scan Absensi
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
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
