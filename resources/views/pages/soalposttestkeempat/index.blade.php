@extends('dashboard.template')
@section('content')
    <div class="pagetitle">
        <h1>Soal Post Test Hari IV</h1>
        <nav><ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Soal Post Test Hari IV</li>
        </ol></nav>
    </div>
    <section class="section">
        <div class="row"><div class="col-lg-12">
            @if (Auth::user()->role != 'mahasiswa')
                @php
                    $posttest_is_active = \App\Models\PostTestSetting::isActive(4);
                @endphp
                <div class="alert alert-light border border-warning shadow-sm mb-3 rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <strong class="text-dark"><i class="bi bi-shield-lock-fill text-warning me-1 fs-5"></i> Status Akses Sesi Post Test Hari IV (Modul 4):</strong>
                            @if ($posttest_is_active)
                                <span class="badge bg-success px-3 py-2 ms-2"><i class="bi bi-check-circle-fill me-1"></i> Sesi DIBUKA (Aktif)</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 ms-2"><i class="bi bi-x-circle-fill me-1"></i> Sesi DITUTUP (Nonaktif)</span>
                            @endif
                        </div>
                        <form action="{{ route('modulposttest.toggle-active', 4) }}" method="POST" class="d-inline m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $posttest_is_active ? 'btn-danger' : 'btn-success' }} px-3 rounded shadow-sm fw-bold">
                                <i class="bi {{ $posttest_is_active ? 'bi-lock-fill' : 'bi-unlock-fill' }} me-1"></i>
                                {{ $posttest_is_active ? 'Tutup Sesi Post Test' : 'Buka Sesi Post Test' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
            <div class="card"><div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Soal Post Test Hari IV</h5>
                            @if (Auth::user()->role != 'mahasiswa')
                                <div class="d-flex gap-2">
                                    <a href="{{ route('soalposttestkeempat.export') }}" class="btn btn-outline-success btn-sm rounded shadow-sm px-3 text-success">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                                    </a>
                                    <button type="button" class="btn btn-success btn-sm rounded shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#importSoalModal">
                                        <i class="bi bi-cloud-arrow-up me-1"></i> Import Excel
                                    </button>
                                    <a href="{{ route('soalposttestkeempat.create') }}" class="btn btn-primary btn-sm shadow-sm px-3 text-white">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Soal
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>

                {{-- Modal Import Soal --}}
                <div class="modal fade" id="importSoalModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="modal-header bg-success text-white border-0 p-3">
                                <h5 class="modal-title fw-bold small"><i class="bi bi-file-earmark-excel me-1"></i> Import Soal Excel</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('soalposttestkeempat.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body p-4">
                                    <div class="alert alert-info border-0 py-2 small mb-3 rounded-3">
                                        Format header: <strong>soal, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban</strong>.
                                        <br>
                                        <a href="{{ route('soalposttestkeempat.template') }}" class="fw-bold text-primary"><i class="bi bi-cloud-download me-1"></i> Download Template</a>
                                    </div>
                                    <div class="mb-3">
                                        <label for="file" class="form-label fw-bold small">Pilih File Excel (.xlsx, .xls, .csv)</label>
                                        <input type="file" name="file" class="form-control" id="file" required>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-3 pt-0">
                                    <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success btn-sm px-4 rounded shadow-sm">Import Soal</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
