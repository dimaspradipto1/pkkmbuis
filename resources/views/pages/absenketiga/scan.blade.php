@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Scan Absensi Kehadiran Ketiga</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('absenketiga.index') }}">Absensi Ketiga</a></li>
                <li class="breadcrumb-item active">Scan</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <div class="card shadow-lg p-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Scan Absensi Kehadiran</h5>
                        <a href="{{ route('absenketiga.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-4">Pilih Sesi & Klik Start</h5>

                        <div class="mb-4">
                            <label class="form-label d-block fw-bold">Sesi Absensi</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="sesi_scan" id="pagi" value="hadir_pagi" checked>
                                <label class="btn btn-outline-success" for="pagi"><i class="bi bi-sun me-1"></i> Sesi Pagi</label>

                                <input type="radio" class="btn-check" name="sesi_scan" id="sore" value="hadir_sore">
                                <label class="btn btn-outline-info" for="sore"><i class="bi bi-moon-stars me-1"></i> Sesi Sore</label>
                            </div>
                        </div>

                        <div id="reader" class="rounded overflow-hidden" style="width: 100%; border: 2px dashed #00A538;"></div>
                        
                        <div id="result" class="mt-4 p-3 bg-light rounded d-none">
                            <div class="spinner-border spinner-border-sm text-primary" role="status" id="loading-scan">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span id="scan-message" class="fw-bold">Processing scan...</span>
                        </div>

                        <div class="d-grid mt-4">
                            <button class="btn btn-danger btn-lg shadow" id="stop-scan" style="display:none;"><i class="bi bi-stop-circle me-1"></i> Stop Scanner</button>
                            <button class="btn btn-success btn-lg shadow" id="start-scan"><i class="bi bi-camera me-1"></i> Start Scanner</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12 mt-4">
                <div class="card shadow-sm border-0 bg-white p-3">
                    <h6 class="fw-bold"><i class="bi bi-clock-history me-2"></i> Riwayat Scan Baru Saja</h6>
                    <ul class="list-group list-group-flush" id="scan-history">
                        <li class="list-group-item text-muted text-center italic">Belum ada riwayat scan.</li>
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
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="fw-bold" id="res-name">-</h4>
                    <p class="text-muted" id="res-msg">-</p>
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Lanjut Scan Berikutnya</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        let isProcessing = false;

        document.getElementById('start-scan').addEventListener('click', () => {
            document.getElementById('start-scan').style.display = 'none';
            document.getElementById('stop-scan').style.display = 'block';
            
            html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
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
            const sesi = document.querySelector('input[name="sesi_scan"]:checked').value;
            
            // Visual feedback
            const resDiv = document.getElementById('result');
            const resMsg = document.getElementById('scan-message');
            resDiv.classList.remove('d-none');
            resMsg.innerText = "Memproses: " + decodedText;

            fetch("{{ route('absenketiga-scan.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({
                    nomor_registrasi: decodedText,
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
                        title: 'Scan Gagal',
                        text: data.message
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server.'
                });
            })
            .finally(() => {
                // We reset isProcessing only after modal is closed to prevent double scans
                resDiv.classList.add('d-none');
            });
        }

        document.getElementById('scanSuccessModal').addEventListener('hidden.bs.modal', function () {
            isProcessing = false;
        });

        function showSuccess(data) {
            document.getElementById('res-name').innerText = data.user.name;
            document.getElementById('res-msg').innerText = data.message;
            
            var modal = new bootstrap.Modal(document.getElementById('scanSuccessModal'));
            modal.show();

            // Beep sound (Optional)
            // addToHistory
            addToHistory(data);
        }

        function addToHistory(data) {
            const history = document.getElementById('scan-history');
            if (history.querySelector('.italic')) history.innerHTML = '';
            
            const li = document.createElement('li');
            li.className = "list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeInDown";
            li.innerHTML = `
                <div>
                    <strong>${data.user.name}</strong><br>
                    <small class="text-muted">${data.user.nomor_registrasi} - ${new Date().toLocaleTimeString()}</small>
                </div>
                <span class="badge bg-success rounded-pill">Hadir</span>
            `;
            history.prepend(li);
        }
    </script>
@endpush
