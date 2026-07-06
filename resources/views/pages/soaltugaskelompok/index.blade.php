@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tugas Kelompok (Modul 5)</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Tugas Kelompok</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Link Tugas Kelompok</h5>
                            @if (Auth::user()->role != 'mahasiswa')
                                <a href="{{ route('soaltugaskelompok.create') }}" class="btn btn-primary text-white">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Link Tugas
                                </a>
                            @endif
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
    @if (app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
@endpush
