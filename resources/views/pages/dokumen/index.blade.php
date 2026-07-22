@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Manajemen Dokumen (Buku Saku, Kelompok, Rundown)</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dokumen</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Dokumen
                            @if(Auth::user()->role == 'admin')
                            <a href="{{ route('dokumen.create') }}" class="btn btn-primary float-end">Tambah Dokumen</a>
                            @endif
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
