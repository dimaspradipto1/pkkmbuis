<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>PKKMB UIS - Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo_ibsi.png') }}" rel="icon" type="image/png">
    <link href="{{ asset('assets/img/logo_ibsi.png') }}" rel="shortcut icon" type="image/png">
    <link href="{{ asset('assets/img/logo_ibsi.png') }}" rel="apple-touch-icon">

    <!-- Open Graph / Meta Tags for Link Sharing (WhatsApp, Facebook, Telegram, etc.) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="PKKMB UIS - Portal Resmi Universitas Ibnu Sina">
    <meta property="og:description" content="Selamat Datang CAMABA UIS 2026. Siapkan diri Anda menjadi bagian dari Civitas Akademika Universitas Ibnu Sina yang berprestasi dan berakhlak mulia.">
    <meta property="og:image" content="{{ asset('assets/img/og_share_thumbnail.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('assets/img/og_share_thumbnail.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="PKKMB UIS - Portal Resmi Universitas Ibnu Sina">
    <meta name="twitter:description" content="Selamat Datang CAMABA UIS 2026. Siapkan diri Anda menjadi bagian dari Civitas Akademika Universitas Ibnu Sina yang berprestasi dan berakhlak mulia.">
    <meta name="twitter:image" content="{{ asset('assets/img/og_share_thumbnail.png') }}">
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
            --uis-green: #046B26;
            --uis-green-dark: #024B1A;
            --uis-yellow: #FED802;
            --bg-light: #f6f9ff;
            --card-edge: rgba(4, 107, 38, 0.1);
        }

        body {
            background-color: var(--bg-light) !important;
            color: #444444;
            font-family: 'Poppins', sans-serif;
        }

        /* Global DataTables & Standard Table Font Size Optimization */
        table.dataTable, 
        .table-responsive table, 
        .table {
            font-size: 0.8rem !important;
        }

        table.dataTable th, 
        .table-responsive table th, 
        .table th {
            font-size: 0.76rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.4px !important;
            vertical-align: middle !important;
            padding: 9px 10px !important;
        }

        table.dataTable td, 
        .table-responsive table td, 
        .table td {
            font-size: 0.8rem !important;
            vertical-align: middle !important;
            padding: 8px 10px !important;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            font-size: 0.8rem !important;
        }

        .table .btn,
        table.dataTable .btn {
            padding: 4px 8px !important;
            font-size: 0.75rem !important;
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
        /* Night / Evening Session Button Styles */
        .btn-outline-night {
            color: #1e1b4b !important;
            border-color: #312e81 !important;
            background-color: transparent !important;
            font-weight: 600;
        }
        .btn-outline-night:hover,
        .btn-outline-night:focus,
        .btn-check:checked + .btn-outline-night {
            color: #ffffff !important;
            background-color: #1e1b4b !important;
            border-color: #1e1b4b !important;
            box-shadow: 0 4px 12px rgba(30, 27, 75, 0.35) !important;
        }
        .btn-night {
            color: #ffffff !important;
            background-color: #1e1b4b !important;
            border-color: #1e1b4b !important;
        }
        .badge-night {
            background-color: #1e1b4b !important;
            color: #ffffff !important;
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
    @include('dashboard.chatbot')



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
    <script>
        $(document).ready(function() {
            if ($('#user_id, .select2').length) {
                $('#user_id, .select2').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Cari / Pilih Pengguna...',
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>

    <script src="https://kit.fontawesome.com/63b8672806.js" crossorigin="anonymous"></script>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global SweetAlert2 confirm handler for any element or form with confirm()
        (function () {
            function handleConfirm(title, text, onConfirm) {
                Swal.fire({
                    title: title || 'Konfirmasi Hapus',
                    text: text || 'Apakah Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        onConfirm();
                    }
                });
            }

            function extractConfirmMessage(attrVal) {
                if (!attrVal) return null;
                let match = attrVal.match(/confirm\(['"](.*?)['"]\)/);
                return match ? match[1] : null;
            }

            // Intercept click on elements with onclick attribute containing confirm() in CAPTURE phase
            document.addEventListener('click', function (e) {
                const target = e.target.closest('[onclick*="confirm("]');
                if (!target) return;

                const onclickAttr = target.getAttribute('onclick');
                const message = extractConfirmMessage(onclickAttr);
                if (message !== null) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    const form = target.closest('form');
                    handleConfirm('Konfirmasi Hapus', message, function () {
                        if (form) {
                            HTMLFormElement.prototype.submit.call(form);
                        } else if (target.tagName === 'A' && target.href) {
                            window.location.href = target.href;
                        }
                    });
                }
            }, true);

            // Intercept submit on forms with onsubmit attribute containing confirm() in CAPTURE phase
            document.addEventListener('submit', function (e) {
                const form = e.target;
                const onsubmitAttr = form.getAttribute('onsubmit');
                const message = extractConfirmMessage(onsubmitAttr);
                if (message !== null) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    handleConfirm('Konfirmasi Hapus', message, function () {
                        HTMLFormElement.prototype.submit.call(form);
                    });
                }
            }, true);
        })();
    </script>

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
                        <div class="mb-3 d-flex justify-content-center gap-2">
                            <input type="radio" class="btn-check" name="qr_session" id="qr_pagi" value="PAGI" checked autocomplete="off">
                            <label class="btn btn-outline-success px-4 fw-bold" for="qr_pagi">
                                <i class="bi bi-sun me-1"></i> Sesi Pagi
                            </label>

                            <input type="radio" class="btn-check" name="qr_session" id="qr_sore" value="SORE" autocomplete="off">
                            <label class="btn btn-outline-night px-4 fw-bold" for="qr_sore">
                                <i class="bi bi-moon-stars me-1"></i> Sesi Sore
                            </label>
                        </div>

                        <!-- Session Expiration & Status Alert -->
                        <div id="sessionStatusAlert" class="alert alert-success py-2 px-3 mb-3 small fw-bold d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-clock-history me-1"></i> <span id="sessionStatusText">Sesi Aktif</span></span>
                            <span id="sessionTimerBadge" class="badge bg-light text-dark border">30:00</span>
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
            let sessionTimerInterval = null;
            let activeDay = 1; // 1, 2, or 3
            let secondsRemaining = 60;
            let sessionSecondsLeft = 1800; // 30 mins
            let isSessionActive = true;

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

                // Set default session to Pagi
                document.getElementById('qr_pagi').checked = true;

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

                fetch(`/absen-scan/get-token/${session}`)
                    .then(res => res.json())
                    .then(data => {
                        isSessionActive = data.is_active;
                        const isAlwaysActive = data.is_always_active ?? false;
                        sessionSecondsLeft = data.remaining_seconds ?? 0;

                        // Fill settings inputs
                        if (data.start_time) {
                            const startInput = document.getElementById('sessionStartTimeInput');
                            if (startInput) startInput.value = data.start_time;
                        }
                        if (data.duration_minutes) {
                            const durInput = document.getElementById('sessionDurationInput');
                            if (durInput) durInput.value = data.duration_minutes;
                        }

                        const toggle = document.getElementById('sessionActiveToggle');
                        if (toggle) {
                            toggle.checked = isSessionActive;
                            const label = document.getElementById('sessionActiveToggleLabel');
                            if (label) label.innerText = isSessionActive ? 'Aktif' : 'Non-aktif';
                        }

                        // Update session timer UI
                        updateSessionTimerUI(isAlwaysActive);

                        // Show QR if: session is active AND has token
                        // For always active: skip sessionSecondsLeft check
                        const canShowQr = isSessionActive && data.token &&
                            (isAlwaysActive || sessionSecondsLeft > 0);

                        if (canShowQr) {
                            dynamicQrcodeObj.clear();
                            dynamicQrcodeObj.makeCode(data.token);
                            secondsRemaining = data.seconds_left || 60;
                            startCountdown(isAlwaysActive);

                            // Update progress bar label for always active
                            if (isAlwaysActive) {
                                const prog = document.getElementById('qrProgressBar');
                                if (prog) { prog.style.width = '100%'; prog.classList.add('bg-success'); }
                                document.getElementById('qrCountdownText').innerText = `⚡ Selalu Aktif — berganti dalam ${secondsRemaining} detik`;
                            }
                        } else {
                            stopQrRotation();
                            dynamicQrcodeObj.clear();
                            document.getElementById('qrCountdownText').innerText = isSessionActive
                                ? "Sesi absensi berakhir (waktu habis)"
                                : "Absensi tidak aktif. Aktifkan terlebih dahulu.";
                        }
                    })
                    .catch(err => {
                        console.error('Error fetching dynamic QR token:', err);
                        document.getElementById('qrCountdownText').innerText = "Gagal mengambil data. Mencoba lagi...";
                        setTimeout(fetchAndRenderQR, 3000);
                    });
            }

            function updateSessionTimerUI(isAlwaysActive) {
                const alertEl = document.getElementById('sessionStatusAlert');
                const textEl = document.getElementById('sessionStatusText');
                const badgeEl = document.getElementById('sessionTimerBadge');

                if (isAlwaysActive && isSessionActive) {
                    alertEl.className = "alert alert-success py-2 px-3 mb-3 small fw-bold d-flex align-items-center justify-content-between";
                    textEl.innerText = "⚡ Selalu Aktif (Tanpa Batas Waktu)";
                    badgeEl.innerText = "∞";
                    badgeEl.className = "badge bg-white text-success border";
                } else if (!isSessionActive || sessionSecondsLeft <= 0) {
                    alertEl.className = "alert alert-danger py-2 px-3 mb-3 small fw-bold d-flex align-items-center justify-content-between";
                    textEl.innerText = "SESI MATI / NON-AKTIF";
                    badgeEl.innerText = "00:00";
                    badgeEl.className = "badge bg-danger text-white border";
                } else {
                    alertEl.className = "alert alert-success py-2 px-3 mb-3 small fw-bold d-flex align-items-center justify-content-between";
                    textEl.innerText = "Sesi Absensi Aktif";
                    const m = Math.floor(sessionSecondsLeft / 60);
                    const s = sessionSecondsLeft % 60;
                    badgeEl.innerText = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                    badgeEl.className = "badge bg-white text-success border";
                }
            }

            function startCountdown(isAlwaysActive) {
                clearInterval(qrCountdownInterval);
                clearInterval(sessionTimerInterval);

                updateCountdownUI();

                // Rotate QR every ~60 seconds
                qrCountdownInterval = setInterval(() => {
                    secondsRemaining--;
                    if (secondsRemaining <= 0) {
                        clearInterval(qrCountdownInterval);
                        fetchAndRenderQR();
                    } else {
                        if (isAlwaysActive) {
                            document.getElementById('qrCountdownText').innerText = `⚡ Selalu Aktif — berganti dalam ${secondsRemaining} detik`;
                        } else {
                            updateCountdownUI();
                        }
                    }
                }, 1000);

                // Session countdown (skip for always active)
                if (!isAlwaysActive) {
                    sessionTimerInterval = setInterval(() => {
                        if (sessionSecondsLeft > 0 && isSessionActive) {
                            sessionSecondsLeft--;
                            updateSessionTimerUI(false);
                        } else {
                            isSessionActive = false;
                            updateSessionTimerUI(false);
                            clearInterval(sessionTimerInterval);
                            fetchAndRenderQR();
                        }
                    }, 1000);
                }
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
                clearInterval(sessionTimerInterval);
            }

            function toggleSessionActiveStatus() {
                const toggle = document.getElementById('sessionActiveToggle');
                const session = getActiveSessionName();

                fetch('/absen-scan/update-setting', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        session_code: session,
                        is_active: toggle.checked
                    })
                })
                .then(res => res.json())
                .then(data => {
                    fetchAndRenderQR();
                });
            }

            function saveSessionSettings() {
                const session = getActiveSessionName();
                const startTime = document.getElementById('sessionStartTimeInput').value;
                const duration = document.getElementById('sessionDurationInput').value || 30;
                const isActive = document.getElementById('sessionActiveToggle').checked;

                fetch('/absen-scan/update-setting', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        session_code: session,
                        start_time: startTime,
                        reset_start_time: !startTime,
                        duration_minutes: duration,
                        is_active: isActive
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    fetchAndRenderQR();
                });
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

    <script>
        function checkUnreadChatNotifications() {
            fetch('{{ route("chat.unread-details") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    const count = data.unread_count || 0;

                    // Update Header Badge
                    const headerBadge = document.getElementById('chatHeaderUnreadBadge');
                    if (headerBadge) {
                        if (count > 0) {
                            headerBadge.innerText = count > 99 ? '99+' : count;
                            headerBadge.style.display = 'inline-block';
                        } else {
                            headerBadge.style.display = 'none';
                        }
                    }

                    // Update Sidebar Badge
                    const sidebarBadge = document.getElementById('sidebarChatUnreadBadge');
                    if (sidebarBadge) {
                        if (count > 0) {
                            sidebarBadge.innerText = count > 99 ? '99+' : count;
                            sidebarBadge.style.display = 'inline-block';
                        } else {
                            sidebarBadge.style.display = 'none';
                        }
                    }

                    // Update Header Dropdown Items
                    const dropdownItemsContainer = document.getElementById('chatDropdownItems');
                    if (dropdownItemsContainer && data.unread_messages) {
                        if (data.unread_messages.length > 0) {
                            let html = '';
                            data.unread_messages.forEach(msg => {
                                html += `
                                    <li class="message-item p-2 border-bottom">
                                        <a href="/chat?user_id=${msg.sender_id}" class="d-flex align-items-center text-decoration-none">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width: 32px; height: 32px; font-weight: bold; font-size: 0.8rem;">
                                                ${msg.sender_name.charAt(0).toUpperCase()}
                                            </div>
                                            <div style="flex: 1; min-width: 0;">
                                                <div class="fw-bold text-dark extra-small text-truncate">${msg.sender_name}</div>
                                                <div class="text-muted extra-small text-truncate">${msg.message}</div>
                                                <div class="text-secondary" style="font-size: 0.65rem;">${msg.time}</div>
                                            </div>
                                        </a>
                                    </li>
                                `;
                            });
                            dropdownItemsContainer.innerHTML = html;
                        } else {
                            dropdownItemsContainer.innerHTML = '<li class="p-3 text-center text-muted extra-small">Tidak ada pesan belum dibaca</li>';
                        }
                    }
                })
                .catch(err => console.error('Error fetching unread chat status:', err));
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkUnreadChatNotifications();
            setInterval(checkUnreadChatNotifications, 3000);
        });
    </script>

    @stack('scripts')

</body>

</html>
