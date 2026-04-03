@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Soal Pretest Hari I</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Soal Pretest Hari I</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Soal Pretest Hari I</h5>
                            @if (Auth::user()->role != 'mahasiswa')
                                <div class="d-flex gap-2">
                                    <a href="{{ route('soalpretestpertama.export') }}" class="btn btn-outline-success btn-sm rounded shadow-sm px-3">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                                    </a>
                                    <button type="button" class="btn btn-success btn-sm rounded shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#importSoalModal">
                                        <i class="bi bi-cloud-arrow-up me-1"></i> Import Excel
                                    </button>
                                    <a href="{{ route('soalpretestpertama.create') }}" class="btn btn-primary btn-sm shadow-sm px-3 text-white">
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
                            <form action="{{ route('soalpretestpertama.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body p-4">
                                    <div class="alert alert-info border-0 py-2 small mb-3 rounded-3">
                                        Format header: <strong>soal, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban</strong>.
                                        <br>
                                        <a href="{{ route('soalpretestpertama.template') }}" class="fw-bold text-primary"><i class="bi bi-cloud-download me-1"></i> Download Template</a>
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
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
