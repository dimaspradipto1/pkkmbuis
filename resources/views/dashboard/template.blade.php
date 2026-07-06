<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>PKKMB UIS - Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/logo_ibsi.png" rel="icon">
    <link href="assets/img/logo_ibsi.png" rel="uis">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    
    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    {{--  datatables CSS  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">

    <style>
        :root {
            --uis-green: #00A551;
            --uis-green-dark: #087C39;
            --uis-yellow: #FFF742;
            --bg-light: #f6f9ff;
            --card-edge: rgba(0, 165, 81, 0.1);
        }

        body {
            background-color: var(--bg-light) !important;
            color: #444444;
            font-family: 'Poppins', sans-serif;
        }

        /* Sidebar: Professional Light Style */
        .sidebar {
            background: #ffffff !important;
            border-right: 1px solid #e2e8f0;
            box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.1);
        }

        .sidebar-nav .nav-link {
            background: transparent !important;
            color: #012970 !important;
            font-weight: 600;
            border-radius: 8px;
            margin: 5px 15px;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .sidebar-nav .nav-link i {
            color: var(--uis-green) !important;
            font-size: 1.1rem;
        }

        .sidebar-nav .nav-link:hover {
            background: #f6f9ff !important;
            color: var(--uis-green-dark) !important;
        }

        .sidebar-nav .nav-link:not(.collapsed) {
            background: #f6f9ff !important;
            color: var(--uis-green) !important;
            border-left: 4px solid var(--uis-green);
        }

        /* Header: Clean UIS Style */
        .header {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0px 2px 20px rgba(1, 41, 112, 0.1);
        }

        .header .logo span {
            color: #012970 !important;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .header .toggle-sidebar-btn {
            color: var(--uis-green) !important;
        }

        /* Clean White Cards */
        .card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1) !important;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: var(--uis-green) !important;
        }

        .card-title {
            color: #012970 !important;
            font-weight: 600 !important;
            font-size: 1.1rem !important;
            padding: 20px 0 15px 0;
        }

        .card-title span { color: #899bbd !important; font-weight: 400; font-size: 0.9rem; }

        /* Stats Section */
        .info-card h6 {
            color: #012970 !important;
            font-weight: 700 !important;
            font-size: 1.8rem !important;
        }

        .card-icon {
            width: 64px; height: 64px;
            border-radius: 50% !important;
            background: #f6f9ff !important;
        }

        .sales-card .card-icon { color: var(--uis-green) !important; }
        .revenue-card .card-icon { color: #f9b115 !important; }

        /* Professional Tables */
        .table {
            color: #444444 !important;
        }

        .table thead th {
            background: #f6f9ff !important;
            color: #012970 !important;
            border-bottom: 2px solid #e2e8f0 !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table td {
            border-bottom: 1px solid #ebeef4 !important;
            vertical-align: middle;
            padding: 12px;
        }

        /* Badge Branding */
        .badge {
            font-weight: 500;
            padding: 5px 12px;
        }

        .bg-success { background-color: var(--uis-green) !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f6f9ff; }
        ::-webkit-scrollbar-thumb { background: #cfd4da; border-radius: 10px; }
        /* Mobile Responsiveness Improvements */
        @media (max-width: 576px) {
            body {
                font-size: 0.85rem !important;
            }
            .pagetitle h1 {
                font-size: 1.25rem !important;
            }
            .card-title {
                font-size: 0.95rem !important;
                padding: 10px 0 10px 0 !important;
            }
            .header .logo span {
                font-size: 15px !important;
            }
            .btn {
                font-size: 0.8rem !important;
                padding: 6px 12px !important;
            }
            .modal-title {
                font-size: 1.1rem !important;
            }
            .card {
                margin-bottom: 15px !important;
                padding: 10px !important;
            }
            .profile-card img {
                width: 90px !important;
                height: 90px !important;
            }
        }
    </style>




</head>

<body>

    @include('dashboard.header')
    @include('dashboard.sidebar')

    <main id="main" class="main">
        @include('sweetalert::alert')
        @yield('content')
    </main><!-- End #main -->

    @include('dashboard.footer')



    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- datatables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://kit.fontawesome.com/63b8672806.js" crossorigin="anonymous"></script>

    @if (Auth::check() && Auth::user()->role != 'mahasiswa')
        <!-- Global Dynamic QR Modal -->
        <div class="modal fade" id="dynamicQrModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title w-100 fw-bold mt-2" id="dynamicQrTitle" style="color: #012970;">QR Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Session Selector Toggle -->
                        <div class="mb-4 d-flex justify-content-center gap-2">
                            <input type="radio" class="btn-check" name="qr_session" id="qr_pagi" value="PAGI" checked autocomplete="off">
                            <label class="btn btn-outline-success px-4 fw-bold" for="qr_pagi">
                                <i class="bi bi-sun me-1"></i> Sesi Pagi
                            </label>

                            <input type="radio" class="btn-check" name="qr_session" id="qr_sore" value="SORE" autocomplete="off">
                            <label class="btn btn-outline-info px-4 fw-bold" for="qr_sore">
                                <i class="bi bi-moon-stars me-1"></i> Sesi Sore
                            </label>
                        </div>

                        <!-- QR Code Container -->
                        <div id="dynamicQrcode" class="d-flex justify-content-center p-3 bg-white rounded border shadow-sm mx-auto position-relative" style="width: fit-content;">
                        </div>

                        <!-- Countdown & Refresh Status -->
                        <div class="mt-3">
                            <div class="progress mx-auto mb-2" style="width: 256px; height: 6px;">
                                <div id="qrProgressBar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%;"></div>
                            </div>
                            <p class="text-muted small mb-0" id="qrCountdownText">Menghubungkan...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load QRCode Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

        <!-- Dynamic QR Rotation Script -->
        <script>
            let dynamicQrcodeObj = null;
            let qrCountdownInterval = null;
            let activeDay = 1; // 1, 2, or 3
            let secondsRemaining = 60;

            function getActiveSessionName() {
                const checkedSession = document.querySelector('input[name="qr_session"]:checked');
                const sessionType = checkedSession ? checkedSession.value : 'PAGI';
                return `ABSEN_${activeDay}_${sessionType}`;
            }

            function initQrCodeObj() {
                if (!dynamicQrcodeObj) {
                    dynamicQrcodeObj = new QRCode(document.getElementById("dynamicQrcode"), {
                        width: 256,
                        height: 256,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                }
            }

            function showAttendanceQR(day) {
                activeDay = day;
                initQrCodeObj();

                // Set default session based on current hour
                const now = new Date();
                const hour = now.getHours();
                if (hour >= 12) {
                    document.getElementById('qr_sore').checked = true;
                } else {
                    document.getElementById('qr_pagi').checked = true;
                }

                updateModalTitle();
                fetchAndRenderQR();

                // Show modal
                const myModalEl = document.getElementById('dynamicQrModal');
                const modal = bootstrap.Modal.getInstance(myModalEl) || new bootstrap.Modal(myModalEl);
                modal.show();

                // Handle session change
                const sessionRadios = document.querySelectorAll('input[name="qr_session"]');
                sessionRadios.forEach(radio => {
                    radio.onchange = () => {
                        fetchAndRenderQR();
                    };
                });
            }

            function updateModalTitle() {
                const checkedSession = document.querySelector('input[name="qr_session"]:checked');
                const sessionName = checkedSession && checkedSession.value === 'SORE' ? 'Sore' : 'Pagi';
                document.getElementById('dynamicQrTitle').innerText = `QR Absensi Hari ${activeDay} (${sessionName})`;
            }

            function fetchAndRenderQR() {
                const session = getActiveSessionName();
                updateModalTitle();

                // Fetch current dynamic token from server
                fetch(`/absen-scan/get-token/${session}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.token) {
                            dynamicQrcodeObj.clear();
                            dynamicQrcodeObj.makeCode(data.token);

                            secondsRemaining = data.seconds_left;

                            startCountdown();
                        }
                    })
                    .catch(err => {
                        console.error('Error fetching dynamic QR token:', err);
                        document.getElementById('qrCountdownText').innerText = "Gagal mengambil token. Mencoba lagi...";
                    });
            }

            function startCountdown() {
                clearInterval(qrCountdownInterval);

                // Update UI immediately
                updateCountdownUI();

                qrCountdownInterval = setInterval(() => {
                    secondsRemaining--;
                    if (secondsRemaining <= 0) {
                        clearInterval(qrCountdownInterval);
                        fetchAndRenderQR(); // Get a new token when current one expires
                    } else {
                        updateCountdownUI();
                    }
                }, 1000);
            }

            function updateCountdownUI() {
                document.getElementById('qrCountdownText').innerText = `Berganti dalam ${secondsRemaining} detik`;
                const percentage = (secondsRemaining / 60) * 100;
                const progressBar = document.getElementById('qrProgressBar');
                progressBar.style.width = `${percentage}%`;

                if (secondsRemaining <= 10) {
                    progressBar.classList.remove('bg-success');
                    progressBar.classList.add('bg-danger');
                } else {
                    progressBar.classList.remove('bg-danger');
                    progressBar.classList.add('bg-success');
                }
            }

            function stopQrRotation() {
                clearInterval(qrCountdownInterval);
            }

            document.addEventListener('DOMContentLoaded', () => {
                const modalEl = document.getElementById('dynamicQrModal');
                if (modalEl) {
                    modalEl.addEventListener('hidden.bs.modal', function () {
                        stopQrRotation();
                    });
                }
            });
        </script>
    @endif

    @stack('scripts')

</body>

</html>
