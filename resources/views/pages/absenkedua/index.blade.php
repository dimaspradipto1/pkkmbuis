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
                                    <button type="button" class="btn btn-outline-dark" onclick="showSessionQR('Hadir Pagi (Hari II)', 'ABSEN_2_PAGI')">
                                        <i class="bi bi-qr-code me-1"></i> QR Pagi
                                    </button>
                                    <button type="button" class="btn btn-outline-dark" onclick="showSessionQR('Hadir Sore (Hari II)', 'ABSEN_2_SORE')">
                                        <i class="bi bi-qr-code me-1"></i> QR Sore
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

    <!-- Modal QR Code -->
    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title w-100 fw-bold mt-2" id="qrTitle">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="qrcode" class="d-flex justify-content-center p-3 bg-white rounded border shadow-sm mx-auto" style="width: fit-content;"></div>
                    <p class="text-muted small mt-3 mb-0" id="qrSubtitle">-</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let qrcode = new QRCode(document.getElementById("qrcode"), {
            width : 256,
            height : 256,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        function showSessionQR(title, code) {
            document.getElementById('qrTitle').innerText = title;
            document.getElementById('qrSubtitle').innerText = "Tunjukkan QR ini kepada mahasiswa";
            qrcode.clear();
            qrcode.makeCode(code); 
            var myModal = new bootstrap.Modal(document.getElementById('qrModal'));
            myModal.show();
        }

        function showQR(name, reg) {
            document.getElementById('qrTitle').innerText = name;
            document.getElementById('qrSubtitle').innerText = reg;
            qrcode.clear();
            qrcode.makeCode(reg); 
            var myModal = new bootstrap.Modal(document.getElementById('qrModal'));
            myModal.show();
        }
    </script>
    {!! $dataTable->scripts() !!}
@endpush
