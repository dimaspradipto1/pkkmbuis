@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Scan Absensi Kehadiran</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('absenpertama.index') }}">Absensi Pertama</a></li>
                <li class="breadcrumb-item active">Scan</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <div class="card shadow-lg p-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Portal Scan Kehadiran</h5>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-4">{{ Auth::user()->role == 'mahasiswa' ? 'Silakan Scan Terhadap QR Admin' : 'Pilih Sesi & Klik Start' }}</h5>

                        @if(Auth::user()->role != 'mahasiswa')
                        <div class="mb-4">
                            <label class="form-label d-block fw-bold">Sesi Absensi (Khusus Admin)</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="sesi_scan" id="pagi" value="hadir_pagi" checked>
                                <label class="btn btn-outline-success" for="pagi"><i class="bi bi-sun me-1"></i> Sesi Pagi</label>

                                <input type="radio" class="btn-check" name="sesi_scan" id="sore" value="hadir_sore">
                                <label class="btn btn-outline-info" for="sore"><i class="bi bi-moon-stars me-1"></i> Sesi Sore</label>
                            </div>
                        </div>
                        @else
                            <input type="hidden" name="sesi_scan" id="sesi_mhs" value="auto">
                        @endif

                        <div id="reader" class="rounded overflow-hidden" style="width: 100%; border: 3px solid #00A538; background: #f8f9fa;"></div>
                        
                        <div id="result" class="mt-4 p-3 bg-light rounded d-none border">
                            <div class="spinner-border spinner-border-sm text-primary" role="status" id="loading-scan">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span id="scan-message" class="fw-bold ms-2">Memproses data...</span>
                        </div>

                        <div class="d-grid mt-4">
                            <button class="btn btn-danger btn-lg shadow" id="stop-scan" style="display:none;"><i class="bi bi-stop-circle me-1"></i> Berhenti</button>
                            <button class="btn btn-primary btn-lg shadow px-5" id="start-scan"><i class="bi bi-camera me-1"></i> Mulai Scan Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8 mx-auto mt-4">
                <div class="card shadow-sm border-0 bg-white p-3">
                    <h6 class="fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Aktivitas Scan Terakhir</h6>
                    <ul class="list-group list-group-flush" id="scan-history">
                        <li class="list-group-item text-muted text-center italic">Belum ada aktivitas scan hari ini.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Success -->
    <div class="modal fade" id="scanSuccessModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-5">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold" id="res-name">-</h4>
                    <p class="text-muted fs-5" id="res-msg">-</p>
                    <button type="button" class="btn btn-primary btn-lg w-100" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const isMahasiswa = "{{ Auth::user()->role == 'mahasiswa' ? 'true' : 'false' }}" === 'true';
        const config = { fps: 15, qrbox: { width: 280, height: 280 } };
        let isProcessing = false;

        document.getElementById('start-scan').addEventListener('click', () => {
            document.getElementById('start-scan').style.display = 'none';
            document.getElementById('stop-scan').style.display = 'block';
            
            html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
                .catch(err => {
                    Swal.fire('Error', 'Izin kamera ditolak atau tidak ditemukan kamera.', 'error');
                    document.getElementById('start-scan').style.display = 'block';
                    document.getElementById('stop-scan').style.display = 'none';
                });
        });

        document.getElementById('stop-scan').addEventListener('click', () => {
            stopCamera();
        });

        function stopCamera() {
            html5QrCode.stop().then(() => {
                document.getElementById('start-scan').style.display = 'block';
                document.getElementById('stop-scan').style.display = 'none';
            });
        }

        function onScanSuccess(decodedText) {
            if (isProcessing) return;
            
            isProcessing = true;
            const sesiCheck = document.querySelector('input[name="sesi_scan"]:checked');
            const sesi = sesiCheck ? sesiCheck.value : 'auto';
            
            // Visual feedback
            const resDiv = document.getElementById('result');
            const resMsg = document.getElementById('scan-message');
            resDiv.classList.remove('d-none');
            resMsg.innerText = "Mendaftarkan kehadiran...";

            fetch("{{ route('absen-scan.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({
                    barcode_data: decodedText,
                    sesi: sesi
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
            })
            .finally(() => {
                resDiv.classList.add('d-none');
            });
        }

        document.getElementById('scanSuccessModal').addEventListener('hidden.bs.modal', function () {
            isProcessing = false;
        });

        function showSuccess(data) {
            const userName = data.user ? data.user.name : "{{ Auth::user()->name }}";
            document.getElementById('res-name').innerText = userName;
            document.getElementById('res-msg').innerText = data.message;
            
            var modal = new bootstrap.Modal(document.getElementById('scanSuccessModal'));
            modal.show();
            addToHistory(data, userName);
        }

        function addToHistory(data, name) {
            const history = document.getElementById('scan-history');
            if (history.querySelector('.italic')) history.innerHTML = '';
            
            const li = document.createElement('li');
            li.className = "list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeInDown";
            const time = new Date().toLocaleTimeString();
            li.innerHTML = `
                <div>
                    <strong>${name}</strong><br>
                    <small class="text-muted">${time} - Selesai</small>
                </div>
                <span class="badge bg-success rounded-pill">Berhasil</span>
            `;
            history.prepend(li);
        }
    </script>
@endpush
