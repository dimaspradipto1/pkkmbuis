@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Data Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Data Users</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Data Users</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success rounded" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                                </button>
                                <a href="{{ route('users.create') }}" class="btn btn-primary rounded"><i class="bi bi-person-plus me-1"></i> Tambah User</a>
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

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title"><i class="bi bi-file-earmark-excel me-1"></i> Import User Excel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-info py-2 small mb-3">
                            Format header: <strong>name</strong>, <strong>email</strong>, <strong>password</strong>, <strong>id_pendaftar</strong>, <strong>role</strong>, <strong>fakultas</strong>, <strong>program_studi</strong>.
                            <br>
                            <a href="{{ route('users.template') }}" class="fw-bold"><i class="bi bi-cloud-download me-1"></i> Download Template</a>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label fw-bold small">Pilih File Excel (.xlsx, .xls, .csv)</label>
                            <input type="file" name="file" class="form-control" id="file" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4 rounded">Import Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal QR Code -->

    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3" id="qrName">-</h5>
                    <div id="qrcode" class="d-flex justify-content-center p-3 bg-white rounded border"></div>
                    <p class="text-muted small mt-3" id="qrReg">-</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let qrcode = new QRCode(document.getElementById("qrcode"), {
            width : 200,
            height : 200
        });

        function showQR(name, reg) {
            document.getElementById('qrName').innerText = name;
            document.getElementById('qrReg').innerText = reg;
            qrcode.clear();
            qrcode.makeCode(reg); // Using registration number as the QR content
            var myModal = new bootstrap.Modal(document.getElementById('qrModal'));
            myModal.show();
        }
    </script>
     @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
@endpush
