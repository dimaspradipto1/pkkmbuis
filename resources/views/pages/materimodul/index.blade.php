@extends('dashboard.template')
@section('content')
    <div class="pagetitle">
        <h1>Materi Modul</h1>
        <nav><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Materi Modul</li>
        </ol></nav>
    </div>
    <section class="section">
        <div class="row"><div class="col-lg-12">
            <div class="card"><div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">List Materi Modul</h5>
                    @if (Auth::user()->role == 'admin' )
                        <a href="{{ route('materimodul.create') }}" class="btn btn-primary text-white">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Materi
                        </a>
                    @endif
                </div>
                <div class="table-responsive">{!! $dataTable->table() !!}</div>
            </div></div>
        </div></div>
    </section>
@endsection
@push('scripts')
     @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
@endpush
