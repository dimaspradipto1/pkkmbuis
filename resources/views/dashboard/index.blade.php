@extends('dashboard.template')

@section('content')
    <style>
        /* Celebratory Lulus Card */
        .celebration-lulus-card {
            background: linear-gradient(135deg, #059669 0%, #10B981 45%, #047857 100%);
            border: 2px solid rgba(251, 191, 36, 0.5);
            box-shadow: 0 15px 35px -5px rgba(16, 185, 129, 0.45), 0 0 25px rgba(245, 158, 11, 0.35);
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.35s ease;
        }

        .celebration-lulus-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 45px -5px rgba(16, 185, 129, 0.55), 0 0 35px rgba(245, 158, 11, 0.5);
        }

        /* Shimmer Light Effect */
        .shimmer-sweep {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                rgba(255,255,255,0) 0%,
                rgba(255,255,255,0.03) 45%,
                rgba(255,255,255,0.3) 50%,
                rgba(255,255,255,0.03) 55%,
                rgba(255,255,255,0) 100%
            );
            transform: rotate(30deg);
            animation: shimmerSweep 4s infinite;
            pointer-events: none;
        }

        @keyframes shimmerSweep {
            0% { transform: translateX(-100%) rotate(30deg); }
            30%, 100% { transform: translateX(100%) rotate(30deg); }
        }

        /* Ribbon Dust & Confetti Particles */
        .ribbon-dust-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .ribbon-particle {
            position: absolute;
            border-radius: 4px;
            opacity: 0.85;
            animation: floatRibbon 4s infinite ease-in-out;
        }

        .ribbon-1 { width: 14px; height: 6px; background: #FDE047; top: 12%; left: 8%; transform: rotate(25deg); animation-delay: 0s; }
        .ribbon-2 { width: 10px; height: 18px; background: #F43F5E; top: 72%; left: 6%; transform: rotate(-40deg); animation-delay: 0.7s; }
        .ribbon-3 { width: 16px; height: 6px; background: #60A5FA; top: 15%; right: 10%; transform: rotate(15deg); animation-delay: 1.2s; }
        .ribbon-4 { width: 8px; height: 14px; background: #A78BFA; top: 75%; right: 8%; transform: rotate(-20deg); animation-delay: 1.8s; }
        .ribbon-5 { width: 12px; height: 5px; background: #F472B6; top: 48%; left: 4%; transform: rotate(50deg); animation-delay: 2.3s; }
        .ribbon-6 { width: 14px; height: 6px; background: #34D399; top: 52%; right: 4%; transform: rotate(-35deg); animation-delay: 2.8s; }

        .sparkle-particle {
            position: absolute;
            font-size: 1.1rem;
            animation: sparklePulse 2.5s infinite ease-in-out;
            pointer-events: none;
        }
        .sparkle-1 { top: 10%; left: 20%; animation-delay: 0.2s; }
        .sparkle-2 { top: 65%; left: 18%; animation-delay: 1.1s; }
        .sparkle-3 { top: 12%; right: 18%; animation-delay: 0.6s; }
        .sparkle-4 { top: 68%; right: 20%; animation-delay: 1.7s; }

        @keyframes floatRibbon {
            0%, 100% { transform: translateY(0) rotate(0deg) scale(1); opacity: 0.75; }
            50% { transform: translateY(-9px) rotate(22deg) scale(1.15); opacity: 1; }
        }

        @keyframes sparklePulse {
            0%, 100% { transform: scale(0.8); opacity: 0.4; }
            50% { transform: scale(1.35); opacity: 1; filter: drop-shadow(0 0 8px #FDE047); }
        }

        /* Trumpet Animations */
        .trumpet-wrapper {
            transition: transform 0.3s ease;
        }
        .trumpet-left {
            animation: trumpetTiltLeft 3s infinite ease-in-out;
        }
        .trumpet-right {
            animation: trumpetTiltRight 3s infinite ease-in-out;
        }

        @keyframes trumpetTiltLeft {
            0%, 100% { transform: rotate(-8deg) scale(1); }
            50% { transform: rotate(-18deg) scale(1.12); }
        }
        @keyframes trumpetTiltRight {
            0%, 100% { transform: rotate(8deg) scale(1); }
            50% { transform: rotate(18deg) scale(1.12); }
        }

        /* Trophy Emblem */
        .trophy-emblem-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .trophy-glow-ring {
            position: absolute;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(253, 224, 71, 0.75) 0%, rgba(245, 158, 11, 0) 70%);
            animation: pulseRing 2.2s infinite ease-in-out;
        }

        @keyframes pulseRing {
            0%, 100% { transform: scale(0.88); opacity: 0.5; }
            50% { transform: scale(1.3); opacity: 0.95; }
        }

        .trophy-circle {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FEF08A 0%, #F59E0B 65%, #B45309 100%);
            border: 3px solid #FFF;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3), inset 0 2px 4px rgba(255,255,255,0.9);
            position: relative;
            z-index: 2;
        }

        .trophy-icon {
            font-size: 2.3rem;
            color: #78350F;
            filter: drop-shadow(0 2px 5px rgba(0,0,0,0.25));
        }

        /* 3D Ribbon Banner LULUS */
        .lulus-ribbon-banner {
            background: linear-gradient(180deg, #15803D 0%, #166534 100%);
            border: 2px solid #FCD34D;
            border-radius: 16px;
            padding: 8px 32px;
            display: inline-block;
            box-shadow: 0 8px 22px rgba(0,0,0,0.35), inset 0 2px 0 rgba(255,255,255,0.35);
            position: relative;
        }

        .lulus-text {
            font-family: 'Poppins', sans-serif;
            font-size: 1.9rem;
            font-weight: 900;
            letter-spacing: 5px;
            color: #FFFFFF;
            text-shadow: 0 3px 6px rgba(0,0,0,0.4), 0 0 15px rgba(252, 211, 77, 0.7);
        }

        .bg-amber-gradient {
            background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%);
        }
    </style>


    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">

        {{-- Top Banner: Portal Absensi --}}
        @php
            $absenSettings = \App\Models\AbsenSetting::all()->keyBy('session_code');
            
            // Sesi Hari 1
            $s1Pagi = $absenSettings->get('ABSEN_1_PAGI');
            $s1Sore = $absenSettings->get('ABSEN_1_SORE');
            $h1Visible = ($s1Pagi->is_visible ?? true) || ($s1Sore->is_visible ?? true);
            $h1Active = ($s1Pagi ? $s1Pagi->checkIsActive() : false) || ($s1Sore ? $s1Sore->checkIsActive() : false);

            // Sesi Hari 2
            $s2Pagi = $absenSettings->get('ABSEN_2_PAGI');
            $s2Sore = $absenSettings->get('ABSEN_2_SORE');
            $h2Visible = ($s2Pagi->is_visible ?? true) || ($s2Sore->is_visible ?? true);
            $h2Active = ($s2Pagi ? $s2Pagi->checkIsActive() : false) || ($s2Sore ? $s2Sore->checkIsActive() : false);

            // Sesi Hari 3
            $s3Pagi = $absenSettings->get('ABSEN_3_PAGI');
            $s3Sore = $absenSettings->get('ABSEN_3_SORE');
            $h3Visible = ($s3Pagi->is_visible ?? true) || ($s3Sore->is_visible ?? true);
            $h3Active = ($s3Pagi ? $s3Pagi->checkIsActive() : false) || ($s3Sore ? $s3Sore->checkIsActive() : false);

            $anyActive = $h1Active || $h2Active || $h3Active;
        @endphp

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(45deg, #087C39, #FFF742) !important;">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 text-center text-md-start mb-3">
                            <div>
                                <h4 class="fw-bold mb-1 text-white fs-5 fs-md-4">
                                    <i class="bi {{ Auth::user()->role == 'mahasiswa' ? 'bi-qr-code-scan' : 'bi-qr-code' }} me-2"></i>
                                    {{ Auth::user()->role == 'mahasiswa' ? 'Scan Absensi Mandiri' : 'Portal QR Kehadiran' }}
                                </h4>
                                <p class="mb-0 text-white opacity-75 small d-none d-md-block">
                                    {{ Auth::user()->role == 'mahasiswa' ? 'Silahkan scan QR Panitia untuk mencatat kehadiran Anda.' : 'Pilih sesi berikut untuk menampilkan QR Code kepada mahasiswa.' }}
                                </p>
                            </div>

                            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
                                <button class="btn btn-warning fw-bold px-3 py-2 rounded-pill shadow-sm d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#pengaturanBarcodeCollapse" style="font-size: 0.85rem; background: linear-gradient(135deg, #f59e0b, #d97706); border: none; color: #1a1a1a !important; white-space: nowrap;">
                                    <i class="bi bi-gear-fill fs-6"></i>
                                    <span class="d-none d-sm-inline">Pengaturan Barcode Absensi</span>
                                    <span class="d-inline d-sm-none">Setting</span>
                                    <i class="bi bi-chevron-down extra-small opacity-75"></i>
                                </button>
                            @endif
                        </div>

                        {{-- Panel Pengaturan Barcode Absensi (Admin / Staf BAAK) --}}
                        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
                            <div class="collapse mb-3" id="pengaturanBarcodeCollapse">
                                <div class="bg-white rounded-3 shadow-sm text-dark p-3 mt-2">
                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                        <i class="bi bi-sliders2 text-success fs-5"></i>
                                        <span class="fw-bold text-dark">Form Pengaturan Barcode & Waktu Absensi</span>
                                    </div>
                                    <div class="row g-3">
                                        @foreach ([1 => 'Hari Pertama', 2 => 'Hari Kedua', 3 => 'Hari Ketiga'] as $dayNum => $dayName)
                                            @php
                                                $codePagi = "ABSEN_{$dayNum}_PAGI";
                                                $setKey = $absenSettings->get($codePagi);
                                                $isAlwaysActive = $setKey->is_always_active ?? false;
                                                $isActive = $setKey->is_active ?? false;
                                                $isVisible = $setKey->is_visible ?? true;
                                                $aktifNow = $setKey ? $setKey->checkIsActive() : false;
                                            @endphp
                                            <div class="col-12 col-md-4">
                                                {{-- Card per hari --}}
                                                <div class="border rounded-3 overflow-hidden h-100" style="border-color: {{ $aktifNow ? '#22c55e' : '#e2e8f0' }} !important;">
                                                    {{-- Card Header --}}
                                                    <div class="px-3 py-2 d-flex justify-content-between align-items-center"
                                                         style="background: {{ $isAlwaysActive ? 'linear-gradient(135deg,#22c55e,#16a34a)' : ($aktifNow ? 'linear-gradient(135deg,#3b82f6,#2563eb)' : '#f8fafc') }};">
                                                        <span class="fw-bold small {{ ($isAlwaysActive || $aktifNow) ? 'text-white' : 'text-dark' }}">
                                                            <i class="bi bi-calendar-check me-1"></i>{{ $dayName }}
                                                        </span>
                                                        <span class="badge rounded-pill px-2 py-1 fw-bold"
                                                              style="font-size:0.68rem; background:{{ $isAlwaysActive ? 'rgba(255,255,255,0.25)' : ($aktifNow ? 'rgba(255,255,255,0.25)' : '#e2e8f0') }}; color:{{ ($isAlwaysActive || $aktifNow) ? '#fff' : '#64748b' }};">
                                                            @if($isAlwaysActive) ⚡ Selalu Aktif
                                                            @elseif($aktifNow) ● Aktif
                                                            @else ○ Non-aktif
                                                            @endif
                                                        </span>
                                                    </div>

                                                    {{-- Card Body --}}
                                                    <div class="p-3 bg-white">

                                                        {{-- Toggle Row: Tampil & Aktif --}}
                                                        <div class="d-flex gap-2 mb-3">
                                                            {{-- Tampil --}}
                                                            <div class="flex-fill p-2 rounded-2 border d-flex align-items-center gap-2" style="background:#f8fafc;">
                                                                <div class="form-check form-switch mb-0">
                                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                                           id="visDay{{ $dayNum }}" style="width:2rem;height:1rem;"
                                                                           {{ $isVisible ? 'checked' : '' }}>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-semibold" style="font-size:0.72rem;line-height:1.2;">Tampil</div>
                                                                    <div class="text-muted" style="font-size:0.65rem;">di halaman</div>
                                                                </div>
                                                            </div>
                                                            {{-- Aktif --}}
                                                            <div class="flex-fill p-2 rounded-2 border d-flex align-items-center gap-2" style="background:#f8fafc;">
                                                                <div class="form-check form-switch mb-0">
                                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                                           id="actDay{{ $dayNum }}" style="width:2rem;height:1rem;"
                                                                           {{ $isActive ? 'checked' : '' }}>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-semibold" style="font-size:0.72rem;line-height:1.2;">Aktif</div>
                                                                    <div class="text-muted" style="font-size:0.65rem;">bisa scan</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Toggle: Selalu Aktif (highlighted) --}}
                                                        <div class="rounded-2 p-2 mb-3 d-flex align-items-center gap-2 cursor-pointer"
                                                             style="background: {{ $isAlwaysActive ? '#dcfce7' : '#fefce8' }}; border: 1.5px solid {{ $isAlwaysActive ? '#86efac' : '#fde68a' }};">
                                                            <div class="form-check form-switch mb-0">
                                                                <input class="form-check-input" type="checkbox" role="switch"
                                                                       id="alwaysDay{{ $dayNum }}" style="width:2.2rem;height:1.1rem;"
                                                                       onchange="toggleAlwaysActive({{ $dayNum }})"
                                                                       {{ $isAlwaysActive ? 'checked' : '' }}>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold" style="font-size:0.75rem; color:{{ $isAlwaysActive ? '#15803d' : '#92400e' }};">
                                                                    ⚡ Selalu Aktif
                                                                </div>
                                                                <div style="font-size:0.65rem; color:{{ $isAlwaysActive ? '#16a34a' : '#a16207' }};">
                                                                    Tanpa batas waktu, tidak perlu set jam
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Time settings (hidden when always active) --}}
                                                        <div id="timeWrapDay{{ $dayNum }}" class="{{ $isAlwaysActive ? 'd-none' : '' }}">
                                                            <div class="mb-2">
                                                                <label class="fw-semibold text-muted mb-1 d-block" style="font-size:0.7rem;">
                                                                    <i class="bi bi-clock me-1"></i>Waktu Mulai:
                                                                </label>
                                                                <input type="datetime-local" class="form-control form-control-sm"
                                                                       style="font-size:0.75rem;"
                                                                       id="timeDay{{ $dayNum }}"
                                                                       value="{{ $setKey && $setKey->start_time ? $setKey->start_time->format('Y-m-d\TH:i') : '' }}">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="fw-semibold text-muted mb-1 d-block" style="font-size:0.7rem;">
                                                                    <i class="bi bi-hourglass-split me-1"></i>Durasi (menit):
                                                                </label>
                                                                <input type="number" class="form-control form-control-sm"
                                                                       style="font-size:0.75rem;"
                                                                       id="durDay{{ $dayNum }}"
                                                                       value="{{ $setKey->duration_minutes ?? 30 }}" min="1">
                                                            </div>
                                                        </div>

                                                        <input type="hidden" id="durDayHidden{{ $dayNum }}" value="{{ $setKey->duration_minutes ?? 30 }}">

                                                        {{-- Save Button --}}
                                                        <button type="button"
                                                                class="btn btn-sm w-100 fw-bold mt-1"
                                                                style="background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;font-size:0.78rem;border:none;border-radius:8px;"
                                                                onclick="saveDayBarcodeSetting({{ $dayNum }})">
                                                            <i class="bi bi-floppy me-1"></i> Simpan Hari {{ $dayNum }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <script>
                            // Run on every toggle change
                            function toggleAlwaysActive(dayNum) {
                                const checkbox = document.getElementById('alwaysDay' + dayNum);
                                const timeWrap = document.getElementById('timeWrapDay' + dayNum);
                                const actToggle = document.getElementById('actDay' + dayNum);

                                if (!checkbox || !timeWrap) return;

                                if (checkbox.checked) {
                                    // Selalu Aktif ON → sembunyikan time/dur fields
                                    timeWrap.style.display = 'none';
                                    if (actToggle) actToggle.checked = true;
                                } else {
                                    // Selalu Aktif OFF → tampilkan time/dur fields
                                    timeWrap.style.display = '';
                                    timeWrap.classList.remove('d-none');
                                }
                            }

                            // Init on page load: apply correct show/hide state for each day
                            document.addEventListener('DOMContentLoaded', function () {
                                [1, 2, 3].forEach(function(dayNum) {
                                    const checkbox = document.getElementById('alwaysDay' + dayNum);
                                    const timeWrap = document.getElementById('timeWrapDay' + dayNum);
                                    if (!checkbox || !timeWrap) return;
                                    if (checkbox.checked) {
                                        timeWrap.style.display = 'none';
                                    } else {
                                        timeWrap.classList.remove('d-none');
                                        timeWrap.style.display = '';
                                    }
                                });
                            });

                            async function saveDayBarcodeSetting(dayNum) {
                                const vis = document.getElementById('visDay' + dayNum).checked;
                                const act = document.getElementById('actDay' + dayNum).checked;
                                const alwaysActive = document.getElementById('alwaysDay' + dayNum).checked;
                                const timeEl = document.getElementById('timeDay' + dayNum);
                                const durEl = document.getElementById('durDay' + dayNum);
                                const hiddenDur = document.getElementById('durDayHidden' + dayNum);
                                const timeVal = (!alwaysActive && timeEl) ? timeEl.value : null;
                                const durVal = durEl ? (parseInt(durEl.value) || 30) : parseInt(hiddenDur?.value || 30);

                                const codePagi = 'ABSEN_' + dayNum + '_PAGI';
                                const codeSore = 'ABSEN_' + dayNum + '_SORE';
                                const payload = (code) => ({
                                    session_code: code,
                                    is_visible: vis,
                                    is_active: act,
                                    is_always_active: alwaysActive,
                                    start_time: timeVal || null,
                                    duration_minutes: durVal
                                });

                                try {
                                    const [resPagi, resSore] = await Promise.all([
                                        fetch('/absen-scan/update-setting', {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                            body: JSON.stringify(payload(codePagi))
                                        }),
                                        fetch('/absen-scan/update-setting', {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                            body: JSON.stringify(payload(codeSore))
                                        })
                                    ]);
                                    const dataPagi = await resPagi.json();
                                    if (resPagi.ok && resSore.ok) {
                                        if (typeof Swal !== 'undefined') {
                                            Swal.fire({
                                                icon: 'success', title: 'Berhasil Disimpan!',
                                                html: `<div class="text-start small"><b>Hari ${dayNum}</b><br>
                                                    &bull; Tampil: <b>${vis ? 'ON ✅' : 'OFF ❌'}</b><br>
                                                    &bull; Aktif: <b>${act ? 'Aktif ✅' : 'Non-aktif ❌'}</b><br>
                                                    &bull; Selalu Aktif: <b>${alwaysActive ? '⚡ ON' : 'OFF'}</b>
                                                    ${!alwaysActive ? `<br>&bull; Waktu: <b>${timeVal || '-'}</b> | Durasi: <b>${durVal}m</b>` : ''}</div>`,
                                                timer: 2500, showConfirmButton: true, confirmButtonText: 'OK'
                                            }).then(() => window.location.reload());
                                        } else { window.location.reload(); }
                                    } else {
                                        const errMsg = dataPagi.message || 'Terjadi kesalahan.';
                                        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal', text: errMsg });
                                        else alert('Gagal: ' + errMsg);
                                    }
                                } catch (err) {
                                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Error Koneksi', text: err.message });
                                    else alert('Error: ' + err.message);
                                }
                            }
                        </script>

                        @if (Auth::user()->role != 'mahasiswa')
                            <div class="row g-2 g-md-3">
                                {{-- Hari Pertama --}}
                                @if ($h1Visible)
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="p-2 p-md-3 bg-white bg-opacity-25 rounded-3 border border-white border-opacity-10 h-100 text-center">
                                            <p class="text-white fw-bold mb-2 small text-uppercase" style="font-size: 0.7rem;">Hari Pertama</p>
                                            @if ($h1Active)
                                                <button class="btn btn-light btn-sm w-100 fw-bold shadow-sm extra-small py-2" style="color: #087C39;" onclick="showAttendanceQR(1)">
                                                    <i class="bi bi-qr-code me-1"></i> QR Absensi (Aktif)
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm w-100 fw-bold shadow-sm extra-small py-2 opacity-75" disabled>
                                                    <i class="bi bi-clock me-1"></i> Non-aktif (Lewat 30m)
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Hari Kedua --}}
                                @if ($h2Visible)
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="p-2 p-md-3 bg-white bg-opacity-25 rounded-3 border border-white border-opacity-10 h-100 text-center">
                                            <p class="text-white fw-bold mb-2 small text-uppercase" style="font-size: 0.7rem;">Hari Kedua</p>
                                            @if ($h2Active)
                                                <button class="btn btn-light btn-sm w-100 fw-bold shadow-sm extra-small py-2" style="color: #087C39;" onclick="showAttendanceQR(2)">
                                                    <i class="bi bi-qr-code me-1"></i> QR Absensi (Aktif)
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm w-100 fw-bold shadow-sm extra-small py-2 opacity-75" disabled>
                                                    <i class="bi bi-clock me-1"></i> Non-aktif (Lewat 30m)
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Hari Ketiga --}}
                                @if ($h3Visible)
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="p-2 p-md-3 bg-white bg-opacity-25 rounded-3 border border-white border-opacity-10 h-100 text-center">
                                            <p class="text-white fw-bold mb-2 small text-uppercase" style="font-size: 0.7rem;">Hari Ketiga</p>
                                            @if ($h3Active)
                                                <button class="btn btn-light btn-sm w-100 fw-bold shadow-sm extra-small py-2" style="color: #087C39;" onclick="showAttendanceQR(3)">
                                                    <i class="bi bi-qr-code me-1"></i> QR Absensi (Aktif)
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm w-100 fw-bold shadow-sm extra-small py-2 opacity-75" disabled>
                                                    <i class="bi bi-clock me-1"></i> Non-aktif (Lewat 30m)
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start mt-2">
                                @if ($anyActive)
                                    <a href="{{ route('absen-scan.index') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm btn-sm" style="color: #087C39;">
                                        <i class="bi bi-qr-code-scan me-1"></i> Scan QR Kehadiran (Sesi Aktif)
                                    </a>
                                @else
                                    <button class="btn btn-light rounded-pill px-4 fw-bold shadow-sm btn-sm opacity-75 text-muted" disabled>
                                        <i class="bi bi-dash-circle me-1"></i> Sesi Absensi Belum Aktif / Kadaluwarsa
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script>
            function toggleAlwaysActive(dayNum) {
                const isAlways = document.getElementById('alwaysDay' + dayNum).checked;
                const timeInput = document.getElementById('timeDay' + dayNum);
                const durInput = document.getElementById('durDay' + dayNum);
                const timeWrap = document.getElementById('timeWrapDay' + dayNum);
                const durWrap = document.getElementById('durWrapDay' + dayNum);

                timeInput.disabled = isAlways;
                durInput.disabled = isAlways;

                // Auto check/uncheck is_active toggle accordingly
                if (isAlways) {
                    document.getElementById('actDay' + dayNum).checked = true;
                    timeWrap.style.opacity = '0.4';
                    durWrap.style.opacity = '0.4';
                } else {
                    timeWrap.style.opacity = '1';
                    durWrap.style.opacity = '1';
                }
            }

            async function saveDayBarcodeSetting(dayNum) {
                const vis = document.getElementById('visDay' + dayNum).checked;
                const act = document.getElementById('actDay' + dayNum).checked;
                const alwaysActive = document.getElementById('alwaysDay' + dayNum).checked;
                const timeVal = document.getElementById('timeDay' + dayNum).value;
                const durVal = parseInt(document.getElementById('durDay' + dayNum).value) || 30;

                const codePagi = 'ABSEN_' + dayNum + '_PAGI';
                const codeSore = 'ABSEN_' + dayNum + '_SORE';

                const payload = (code) => ({
                    session_code: code,
                    is_visible: vis,
                    is_active: act,
                    is_always_active: alwaysActive,
                    start_time: (!alwaysActive && timeVal) ? timeVal : null,
                    duration_minutes: durVal
                });

                try {
                    const [resPagi, resSore] = await Promise.all([
                        fetch('/absen-scan/update-setting', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload(codePagi))
                        }),
                        fetch('/absen-scan/update-setting', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload(codeSore))
                        })
                    ]);

                    const dataPagi = await resPagi.json();
                    const dataSore = await resSore.json();

                    if (resPagi.ok && resSore.ok) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Disimpan!',
                                html: `<div class="text-start small">
                                    <b>Hari ${dayNum}</b><br>
                                    &bull; Tombol Tampil: <b>${vis ? 'ON ✅' : 'OFF ❌'}</b><br>
                                    &bull; Status Aktif: <b>${act ? 'Aktif ✅' : 'Non-aktif ❌'}</b><br>
                                    &bull; Selalu Aktif: <b>${alwaysActive ? '⚡ ON (Tanpa Batas Waktu)' : 'OFF'}</b><br>
                                    ${!alwaysActive ? `&bull; Waktu Mulai: <b>${timeVal ? timeVal : '-'}</b><br>&bull; Durasi: <b>${durVal} menit</b>` : ''}
                                </div>`,
                                timer: 2500,
                                showConfirmButton: true,
                                confirmButtonText: 'OK'
                            }).then(() => window.location.reload());
                        } else {
                            window.location.reload();
                        }
                    } else {
                        const errMsg = dataPagi.message || dataSore.message || 'Terjadi kesalahan.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: errMsg });
                        } else {
                            alert('Gagal: ' + errMsg);
                        }
                    }
                } catch (err) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi gagal: ' + err.message });
                    } else {
                        alert('Koneksi error: ' + err.message);
                    }
                }
            }
        </script>

        {{-- Top Banner: Modul Post Test --}}
        @if (Auth::user()->role == 'mahasiswa')
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm overflow-hidden" style="background: #fff; border-radius: 12px;">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px; background: linear-gradient(135deg, #00A551 0%, #007A3B 100%);">
                                        <i class="bi bi-book-half text-white fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">Modul, Pritest dan Postes Mahasiswa</h5>
                                        <p class="text-muted small mb-1">Akses materi pembelajaran dan kerjakan evaluasi modul Anda di sini.</p>
                                        <p class="text-danger small fw-semibold mb-0">
                                            <i class="bi bi-info-circle-fill me-1 text-danger"></i> <strong>Catatan:</strong> Pretest sudah dapat dikerjakan sehari sebelum pelaksanaan acara (H-1) dan diharapkan selesai sebelum acara dimulai.
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('modulposttest.index') }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm w-100 w-lg-auto mt-2 mt-lg-0">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Buka Modul & Ujian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (Auth::user()->role == 'mahasiswa')
            {{-- Top Banner: Dokumen Pendukung --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm overflow-hidden" style="background: #fff; border-radius: 12px;">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px; background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">
                                        <i class="bi bi-file-earmark-text-fill text-white fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1 mb-md-0">Dokumen Pendukung</h5>
                                        <p class="text-muted small mb-0 d-none d-md-block">Akses Buku Saku, Daftar Kelompok, dan Rundown acara di sini.</p>
                                    </div>
                                </div>
                                @php
                                    $dokumen = \App\Models\Dokumen::latest()->first();
                                @endphp
                                <div class="d-grid gap-2 d-lg-flex mt-3 mt-lg-0 w-100 w-lg-auto">
                                    @if($dokumen && $dokumen->link_buku_saku)
                                        <a href="{{ $dokumen->link_buku_saku }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="bi bi-book me-1"></i> Buku Saku
                                        </a>
                                    @endif
                                    @if($dokumen && $dokumen->link_daftar_kelompok)
                                        <a href="{{ $dokumen->link_daftar_kelompok }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="bi bi-people me-1"></i> Daftar Kelompok
                                        </a>
                                    @endif
                                    @if($dokumen && $dokumen->link_rundown)
                                        <a href="{{ $dokumen->link_rundown }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="bi bi-calendar-event me-1"></i> Rundown
                                        </a>
                                    @endif
                                    @if($dokumen && $dokumen->link_tata_tertib_kehidupan_mahasiswa)
                                        <a href="{{ $dokumen->link_tata_tertib_kehidupan_mahasiswa }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="bi bi-shield-exclamation me-1"></i> Tata Tertib
                                        </a>
                                    @endif
                                    @if($dokumen && $dokumen->link_video_tutorial_penggunaan_sistem_PKKMB)
                                        <a href="{{ $dokumen->link_video_tutorial_penggunaan_sistem_PKKMB }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="bi bi-play-btn me-1"></i> Video Tutorial
                                        </a>
                                    @endif
                                    @if(!$dokumen || (!$dokumen->link_buku_saku && !$dokumen->link_daftar_kelompok && !$dokumen->link_rundown && !$dokumen->link_tata_tertib_kehidupan_mahasiswa && !$dokumen->link_video_tutorial_penggunaan_sistem_PKKMB))
                                        <span class="text-muted small">Belum ada dokumen yang diunggah.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        @if (Auth::user()->role != 'mahasiswa')
            {{-- KPI Summary Stats Row --}}
            <div class="row g-3 mb-4">
                {{-- Total Mahasiswa --}}
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: linear-gradient(135deg, #ffffff, #f0fdf4); border-left: 4px solid #00A551 !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">
                                    {{ $myKelompokNama ? 'Anggota Kelompok' : 'Total Mahasiswa' }}
                                </span>
                                <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalMahasiswa) }}</h3>
                                <span class="badge bg-success text-white extra-small mt-1 text-truncate shadow-sm" style="max-width: 170px; background-color: #00A551 !important;" title="{{ $myKelompokNama ?: 'Terdaftar Aktif' }}">
                                    <i class="bi bi-people me-1"></i> {{ $myKelompokNama ?: 'Terdaftar Aktif' }}
                                </span>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #00A551, #007A3B); color: #fff;">
                                <i class="bi bi-people-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Kehadiran --}}
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: linear-gradient(135deg, #ffffff, #f0f9ff); border-left: 4px solid #0284c7 !important;">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="w-100 pe-2">
                                <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Presensi Kehadiran</span>
                                <div class="d-flex align-items-baseline gap-2 mt-1">
                                    <h3 class="fw-bold text-dark mb-0">{{ number_format($totalPresensi ?? ($absenDatang + $absenPulang)) }}</h3>
                                    <span class="badge bg-primary bg-opacity-10 text-primary extra-small fw-semibold py-1">6 Sesi</span>
                                </div>
                                <div class="mt-2 pt-1 border-top border-light extra-small text-muted" style="font-size: 0.72rem; line-height: 1.4;">
                                    <div>H1: <b>{{ $absenDatang1 ?? 0 }}</b> Datang &bull; <b>{{ $absenPulang1 ?? 0 }}</b> Pulang</div>
                                    <div>H2: <b>{{ $absenDatang2 ?? 0 }}</b> Datang &bull; <b>{{ $absenPulang2 ?? 0 }}</b> Pulang</div>
                                    <div>H3: <b>{{ $absenDatang3 ?? 0 }}</b> Datang &bull; <b>{{ $absenPulang3 ?? 0 }}</b> Pulang</div>
                                </div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0 mt-1" style="width: 44px; height: 44px; background: linear-gradient(135deg, #0284c7, #0369a1); color: #fff;">
                                <i class="bi bi-calendar-check-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Penilaian Kedisiplinan --}}
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: linear-gradient(135deg, #ffffff, #eef2ff); border-left: 4px solid #6366f1 !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Kedisiplinan</span>
                                <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalKedisiplinan ?? ($dis1Count + $dis2Count + $dis3Count)) }}</h3>
                                <span class="text-muted extra-small mt-1 d-block">
                                    H1: <b>{{ $dis1Count ?? 0 }}</b> | H2: <b>{{ $dis2Count ?? 0 }}</b> | H3: <b>{{ $dis3Count ?? 0 }}</b>
                                </span>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tugas Kelompok --}}
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: linear-gradient(135deg, #ffffff, #fffbeb); border-left: 4px solid #f59e0b !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Tugas Kelompok</span>
                                <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($tugasCount) }}</h3>
                                <span class="badge bg-warning bg-opacity-10 text-warning extra-small mt-1">
                                    <i class="bi bi-journal-text me-1"></i> Terkumpul
                                </span>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff;">
                                <i class="bi bi-journal-bookmark-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            {{-- Main Content Area --}}
            <div class="col-lg-8">
                <div class="row">
                    @if (Auth::user()->role != 'mahasiswa')
                        {{-- SECTION 1: PRESENSI & KEDISIPLINAN --}}
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3"><i class="bi bi-person-check fs-6"></i></span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Presensi & Kedisiplinan Peserta</h6>
                                        <span class="text-muted extra-small">Monitoring absensi harian dan rekap kedisiplinan atribut & perilaku</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 1: Main Attendance Hub: Multi-Day --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                                <div class="card-body p-3 p-xl-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                        <div>
                                            <h5 class="card-title mb-0 fs-5">Hub Absensi <span>| All Days</span></h5>
                                            <p class="text-muted extra-small mb-0 opacity-75">Monitoring kehadiran mahasiswa
                                                real-time.</p>
                                        </div>

                                        <div class="d-flex align-items-center flex-wrap gap-2 ms-xl-auto w-100 w-xl-auto">
                                            {{-- Detail Link --}}
                                            <div class="dropdown me-md-2 mb-2 mb-md-0">
                                                <button
                                                    class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-bold extra-small dropdown-toggle shadow-none"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Manage
                                                </button>
                                                <ul
                                                    class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 extra-small">
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('absenpertama.index') }}"><i
                                                                class="bi bi-calendar-event me-2"></i> Hari Pertama</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('absenkedua.index') }}"><i
                                                                class="bi bi-calendar-event me-2"></i> Hari Kedua</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('absenketiga.index') }}"><i
                                                                class="bi bi-calendar-event me-2"></i> Hari Ketiga</a></li>
                                                </ul>
                                            </div>

                                            {{-- Search Input --}}
                                            <div class="position-relative w-100 w-md-auto mb-2 mb-md-0">
                                                <input type="text" id="attendanceSearch"
                                                    class="form-control form-control-sm rounded-pill px-4 ps-5 w-100 shadow-none border-light"
                                                    placeholder="Cari..."
                                                    style="background: #f8fafc; min-width: 200px; height: 38px;">
                                                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted opacity-50"
                                                    style="left: 0;"></i>
                                            </div>

                                            {{-- Tabs --}}
                                            <ul class="nav nav-pills nav-pills-custom gap-1 flex-nowrap overflow-auto pb-1"
                                                id="attendanceTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="day1-tab" data-bs-toggle="tab" data-bs-target="#day1"
                                                        type="button" role="tab">H-1</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="day2-tab" data-bs-toggle="tab" data-bs-target="#day2"
                                                        type="button" role="tab">H-2</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="day3-tab" data-bs-toggle="tab" data-bs-target="#day3"
                                                        type="button" role="tab">H-3</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content pt-2" id="attendanceTabContent">
                                        {{-- Day 1 Pane --}}
                                        <div class="tab-pane fade show active" id="day1" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-sm table-hover align-middle mb-0 attendance-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2">NO</th>
                                                            <th>NAMA</th>
                                                            <th>DATANG</th>
                                                            <th class="text-end pe-3">PULANG</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allAbsen1 as $index => $abs)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $abs->user->name }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $statusPagi = $abs->hadir_pagi;
                                                                        $initialPagi = substr($statusPagi, 0, 1);
                                                                        $colorPagi = match ($statusPagi) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorPagi }}">{{ $initialPagi }}</span>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    @php
                                                                        $statusSore = $abs->hadir_sore;
                                                                        $initialSore = substr($statusSore, 0, 1);
                                                                        $colorSore = match ($statusSore) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorSore }}">{{ $initialSore }}</span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr class="empty-row">
                                                                <td colspan="4" class="text-center py-3 text-muted">No
                                                                    data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-pagination-wrapper"></div>
                                        </div>

                                        {{-- Day 2 Pane --}}
                                        <div class="tab-pane fade" id="day2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-sm table-hover align-middle mb-0 attendance-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2">NO</th>
                                                            <th>NAMA</th>
                                                            <th>DATANG</th>
                                                            <th class="text-end pe-3">PULANG</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allAbsen2 as $index => $abs)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $abs->user->name }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $statusPagi = $abs->hadir_pagi;
                                                                        $initialPagi = substr($statusPagi, 0, 1);
                                                                        $colorPagi = match ($statusPagi) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorPagi }}">{{ $initialPagi }}</span>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    @php
                                                                        $statusSore = $abs->hadir_sore;
                                                                        $initialSore = substr($statusSore, 0, 1);
                                                                        $colorSore = match ($statusSore) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorSore }}">{{ $initialSore }}</span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr class="empty-row">
                                                                <td colspan="4" class="text-center py-3 text-muted">No
                                                                    data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-pagination-wrapper"></div>
                                        </div>

                                        {{-- Day 3 Pane --}}
                                        <div class="tab-pane fade" id="day3" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-sm table-hover align-middle mb-0 attendance-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2">NO</th>
                                                            <th>NAMA</th>
                                                            <th>DATANG</th>
                                                            <th class="text-end pe-3">PULANG</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allAbsen3 as $index => $abs)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $abs->user->name }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $statusPagi = $abs->hadir_pagi;
                                                                        $initialPagi = substr($statusPagi, 0, 1);
                                                                        $colorPagi = match ($statusPagi) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorPagi }}">{{ $initialPagi }}</span>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    @php
                                                                        $statusSore = $abs->hadir_sore;
                                                                        $initialSore = substr($statusSore, 0, 1);
                                                                        $colorSore = match ($statusSore) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Sakit' => 'bg-info',
                                                                            'Alpa' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $colorSore }}">{{ $initialSore }}</span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr class="empty-row">
                                                                <td colspan="4" class="text-center py-3 text-muted">No
                                                                    data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-pagination-wrapper"></div>
                                        </div>
                                    </div>

                                    <div
                                        class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-2">
                                        <div
                                            class="extra-small text-muted fw-semibold me-2 opacity-75 d-flex align-items-center text-uppercase">
                                            KETERANGAN:</div>
                                        <div class="d-flex align-items-center gap-1 extra-small">
                                            <span class="badge bg-success py-1 px-2">H: Hadir</span>
                                            <span class="badge bg-warning py-1 px-2">I: Izin</span>
                                            <span class="badge bg-info text-white py-1 px-2">S: Sakit</span>
                                            <span class="badge bg-danger py-1 px-2">A: Alpa</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Main Discipline Hub: Multi-Day --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                                <div class="card-body p-3 p-xl-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                        <div>
                                            <h5 class="card-title mb-0 fs-5">Hub Kedisiplinan <span>| All Days</span></h5>
                                            <p class="text-muted extra-small mb-0 opacity-75">Monitoring kedisiplinan
                                                (Atribut, Waktu, Perilaku) mahasiswa.</p>
                                        </div>

                                        <div class="d-flex align-items-center flex-wrap gap-2 ms-xl-auto w-100 w-xl-auto">
                                            {{-- Detail Link --}}
                                            <div class="dropdown me-md-2 mb-2 mb-md-0">
                                                <button
                                                    class="btn btn-outline-info btn-sm rounded-pill px-3 py-2 fw-bold extra-small dropdown-toggle shadow-none"
                                                    type="button" data-bs-toggle="dropdown"
                                                    style="border-color: rgba(13, 202, 240, 0.3);">
                                                    <i class="bi bi-shield-check me-1"></i> Manage
                                                </button>
                                                <ul
                                                    class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 extra-small">
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('kedisiplinanpertama.index') }}"><i
                                                                class="bi bi-calendar-check me-2"></i> Hari Pertama</a>
                                                    </li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('kedisiplinankedua.index') }}"><i
                                                                class="bi bi-calendar-check me-2"></i> Hari Kedua</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('kedisiplinanketiga.index') }}"><i
                                                                class="bi bi-calendar-check me-2"></i> Hari Ketiga</a></li>
                                                </ul>
                                            </div>

                                            {{-- Search Input --}}
                                            <div class="position-relative w-100 w-md-auto mb-2 mb-md-0">
                                                <input type="text" id="disciplineSearch"
                                                    class="form-control form-control-sm rounded-pill px-4 ps-5 w-100 shadow-none border-light"
                                                    placeholder="Cari..."
                                                    style="background: #f8fafc; min-width: 200px; height: 38px;">
                                                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted opacity-50"
                                                    style="left: 0;"></i>
                                            </div>

                                            {{-- Tabs --}}
                                            <ul class="nav nav-pills nav-pills-custom gap-1 flex-nowrap overflow-auto pb-1"
                                                id="disciplineTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="dis1-tab" data-bs-toggle="tab" data-bs-target="#dis1"
                                                        type="button" role="tab">H-1</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="dis2-tab" data-bs-toggle="tab" data-bs-target="#dis2"
                                                        type="button" role="tab">H-2</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="dis3-tab" data-bs-toggle="tab" data-bs-target="#dis3"
                                                        type="button" role="tab">H-3</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content pt-2" id="disciplineTabContent">
                                        @foreach ([['id' => 'dis1', 'data' => $allDis1], ['id' => 'dis2', 'data' => $allDis2], ['id' => 'dis3', 'data' => $allDis3]] as $pane)
                                            <div class="tab-pane fade {{ $pane['id'] == 'dis1' ? 'show active' : '' }}"
                                                id="{{ $pane['id'] }}" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-sm table-hover align-middle mb-0 discipline-table">
                                                        <thead>
                                                            <tr class="text-uppercase extra-small fw-bold"
                                                                style="background: #f8fafc; color: #012970;">
                                                                <th class="ps-3 py-2">NO</th>
                                                                <th>NAMA</th>
                                                                <th>ATRIBUT</th>
                                                                <th>WAKTU</th>
                                                                <th class="text-end pe-3">PERILAKU</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($pane['data'] as $index => $dis)
                                                                <tr class="extra-small border-bottom border-light">
                                                                    <td class="ps-3 py-2 text-muted">{{ $index + 1 }}
                                                                    </td>
                                                                    <td class="text-nowrap"><span
                                                                            class="fw-bold text-dark search-target">{{ $dis->user->name }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $stA = strtolower(
                                                                                $dis->kelengkapan_atribut ?? '',
                                                                            );
                                                                            $colA =
                                                                                $stA === 'lengkap'
                                                                                    ? 'bg-success'
                                                                                    : ($stA === 'tidak lengkap'
                                                                                        ? 'bg-danger'
                                                                                        : 'bg-light text-muted border');
                                                                            $iconA =
                                                                                $stA === 'lengkap'
                                                                                    ? 'A'
                                                                                    : ($stA === 'tidak lengkap'
                                                                                        ? 'TL'
                                                                                        : '-');
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $colA }}">{{ $iconA }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $stW = strtolower(
                                                                                $dis->ketepatan_waktu ?? '',
                                                                            );
                                                                            $colW =
                                                                                $stW === 'tepat waktu'
                                                                                    ? 'bg-success'
                                                                                    : ($stW === 'terlambat'
                                                                                        ? 'bg-danger'
                                                                                        : 'bg-light text-muted border');
                                                                            $iconW =
                                                                                $stW === 'tepat waktu'
                                                                                    ? 'W'
                                                                                    : ($stW === 'terlambat'
                                                                                        ? 'TL'
                                                                                        : '-');
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $colW }}">{{ $iconW }}</span>
                                                                    </td>
                                                                    <td class="text-end pe-3">
                                                                        @php
                                                                            $stP = strtolower($dis->perilaku ?? '');
                                                                            $colP = match ($stP) {
                                                                                'sangat baik' => 'bg-success',
                                                                                'baik' => 'bg-primary',
                                                                                'cukup' => 'bg-warning',
                                                                                'kurang' => 'bg-danger',
                                                                                default
                                                                                    => 'bg-light text-muted border',
                                                                            };
                                                                            $iconP = match ($stP) {
                                                                                'sangat baik' => 'SB',
                                                                                'baik' => 'B',
                                                                                'cukup' => 'C',
                                                                                'kurang' => 'K',
                                                                                default => '-',
                                                                            };
                                                                        @endphp
                                                                        <span
                                                                            class="badge {{ $colP }}">{{ $iconP }}</span>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr class="empty-row">
                                                                    <td colspan="5"
                                                                        class="text-center py-3 text-muted">No data</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="table-pagination-wrapper"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div
                                        class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-2">
                                        <div
                                            class="extra-small text-muted fw-semibold me-2 opacity-75 d-flex align-items-center">
                                            KETERANGAN:</div>
                                        <div
                                            class="d-flex align-items-center gap-1 extra-small flex-wrap justify-content-center">
                                            <span class="badge bg-success py-1 px-2">A: Lengkap</span>
                                            <span class="badge bg-danger py-1 px-2">TL: Tidak Lengkap/Telat</span>
                                            <span class="badge bg-success py-1 px-2">W: Tepat</span>
                                            <span class="badge bg-success py-1 px-2">SB: Sangat Baik</span>
                                            <span class="badge bg-primary py-1 px-2">B: Baik</span>
                                            <span class="badge bg-warning py-1 px-2">C: Cukup</span>
                                            <span class="badge bg-danger py-1 px-2">K: Kurang</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 2: EVALUASI AKADEMIK & PENUGASAN --}}
                        <div class="col-12 mb-3 mt-2">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3"><i class="bi bi-award fs-6"></i></span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Evaluasi Nilai & Capaian Akademik</h6>
                                        <span class="text-muted extra-small">Snapshot nilai pre-test, post-test, penugasan kelompok, dan modul</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 3: Main Snapshot Hub: Pre-Test, Post-Test & Tugas Kelompok --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                                <div class="card-body p-3 p-xl-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                        <div>
                                            <h5 class="card-title mb-0 fs-5">Hub Snapshot Nilai & Tugas <span>| Pre, Post & Tugas</span></h5>
                                            <p class="text-muted extra-small mb-0 opacity-75">Monitoring capaian nilai pre-test, post-test, dan link pengumpulan tugas mahasiswa.</p>
                                        </div>

                                        <div class="d-flex align-items-center flex-wrap gap-2 ms-xl-auto w-100 w-xl-auto">
                                            {{-- Detail Link --}}
                                            <div class="dropdown me-md-2 mb-2 mb-md-0">
                                                <button
                                                    class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-bold extra-small dropdown-toggle shadow-none"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Manage
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 extra-small">
                                                    <li><a class="dropdown-item py-2" href="{{ route('hasiltest.index') }}"><i class="bi bi-clipboard-data me-2"></i> Hasil Test (Pre / Post)</a></li>
                                                    <li><a class="dropdown-item py-2" href="{{ route('soaltugaskelompok.index') }}"><i class="bi bi-book-half me-2"></i> Tugas Kelompok</a></li>
                                                </ul>
                                            </div>

                                            {{-- Search Input --}}
                                            <div class="position-relative w-100 w-md-auto mb-2 mb-md-0">
                                                <input type="text" id="snapshotSearch"
                                                    class="form-control form-control-sm rounded-pill px-4 ps-5 w-100 shadow-none border-light"
                                                    placeholder="Cari nama..."
                                                    style="background: #f8fafc; min-width: 200px; height: 38px;">
                                                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted opacity-50"
                                                    style="left: 0;"></i>
                                            </div>

                                            {{-- Tabs --}}
                                            <ul class="nav nav-pills nav-pills-custom gap-1 flex-nowrap overflow-auto pb-1"
                                                id="snapshotTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="snap-pre-tab" data-bs-toggle="tab" data-bs-target="#snap-pre"
                                                        type="button" role="tab">Pre-Test</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="snap-post-tab" data-bs-toggle="tab" data-bs-target="#snap-post"
                                                        type="button" role="tab">Post-Test</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="snap-tugas-tab" data-bs-toggle="tab" data-bs-target="#snap-tugas"
                                                        type="button" role="tab">Kelompok / Tugas</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content pt-2" id="snapshotTabContent">
                                        {{-- Pre-Test Tab Pane --}}
                                        <div class="tab-pane fade show active" id="snap-pre" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover align-middle mb-0 snapshot-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2" style="width: 60px;">NO</th>
                                                            <th>NAMA MAHASISWA</th>
                                                            <th class="text-end pe-3" style="width: 140px;">SKOR PRE-TEST</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allPretest as $index => $pre)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $pre->user->name ?? '-' }}</span>
                                                                </td>
                                                                <td class="text-end pe-3"><span
                                                                        class="badge bg-info text-white fs-6 px-3 py-1">{{ $pre->skor }}</span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr class="empty-row">
                                                                <td colspan="3" class="text-center py-3 text-muted small">Belum ada data Pre-Test</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-pagination-wrapper"></div>
                                        </div>

                                        {{-- Post-Test Tab Pane --}}
                                        <div class="tab-pane fade" id="snap-post" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover align-middle mb-0 snapshot-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2" style="width: 60px;">NO</th>
                                                            <th>NAMA MAHASISWA</th>
                                                            <th class="text-end pe-3" style="width: 140px;">SKOR POST-TEST</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allPosttest as $index => $post)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $post->user->name ?? '-' }}</span>
                                                                </td>
                                                                <td class="text-end pe-3"><span
                                                                        class="badge bg-primary fs-6 px-3 py-1">{{ $post->skor }}</span></td>
                                                            </tr>
                                                        @empty
                                                            <tr class="empty-row">
                                                                <td colspan="3" class="text-center py-3 text-muted small">Belum ada data Post-Test</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-pagination-wrapper"></div>
                                        </div>

                                        {{-- Kelompok / Tugas Tab Pane --}}
                                        <div class="tab-pane fade" id="snap-tugas" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover align-middle mb-0 snapshot-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2" style="width: 60px;">NO</th>
                                                            <th>NAMA MAHASISWA</th>
                                                            <th class="text-end pe-3" style="width: 140px;">LINK TUGAS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allTugas as $index => $tug)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $tug->user->name ?? '-' }}</span>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    <a href="{{ $tug->link_tugas }}" target="_blank"
                                                                        class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 extra-small fw-bold"><i
                                                                            class="bi bi-link-45deg me-1"></i> Buka Link</a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr class="empty-row">
                                                                <td colspan="3" class="text-center py-3 text-muted small">Belum ada data Tugas Kelompok</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-pagination-wrapper"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 4: Main Test Hub: Multi-Module --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                                <div class="card-body p-3 p-xl-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                        <div>
                                            <h5 class="card-title mb-0 fs-5">Hub Hasil Test Modul <span>| M-1 s/d M-5</span></h5>
                                            <p class="text-muted extra-small mb-0 opacity-75">Detail perolehan nilai pretest & posttest per materi modul.</p>
                                        </div>

                                        <div class="d-flex align-items-center flex-wrap gap-2 ms-xl-auto w-100 w-xl-auto">
                                            {{-- Detail Link --}}
                                            <div class="dropdown me-md-2 mb-2 mb-md-0">
                                                <button
                                                    class="btn btn-outline-success btn-sm rounded-pill px-3 py-2 fw-bold extra-small dropdown-toggle shadow-none"
                                                    type="button" data-bs-toggle="dropdown"
                                                    style="border-color: rgba(34, 197, 94, 0.3);">
                                                    <i class="bi bi-journal-check me-1"></i> Manage
                                                </button>
                                                <ul
                                                    class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 extra-small">
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('hasiltest.index') }}"><i
                                                                class="bi bi-list-check me-2"></i> Kelola Hasil Test</a>
                                                    </li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('soaltugaskelompok.index') }}"><i
                                                                class="bi bi-people me-2"></i> Tugas Kelompok</a></li>
                                                </ul>
                                            </div>

                                            {{-- Search Input --}}
                                            <div class="position-relative w-100 w-md-auto mb-2 mb-md-0">
                                                <input type="text" id="testSearch"
                                                    class="form-control form-control-sm rounded-pill px-4 ps-5 w-100 shadow-none border-light"
                                                    placeholder="Cari..."
                                                    style="background: #f8fafc; min-width: 200px; height: 38px;">
                                                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted opacity-50"
                                                    style="left: 0;"></i>
                                            </div>

                                            {{-- Tabs --}}
                                            <ul class="nav nav-pills nav-pills-custom gap-1 flex-nowrap overflow-auto pb-1"
                                                id="testTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m1-tab" data-bs-toggle="tab" data-bs-target="#m1"
                                                        type="button" role="tab">M-1</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m2-tab" data-bs-toggle="tab" data-bs-target="#m2"
                                                        type="button" role="tab">M-2</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m3-tab" data-bs-toggle="tab" data-bs-target="#m3"
                                                        type="button" role="tab">M-3</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m4-tab" data-bs-toggle="tab" data-bs-target="#m4"
                                                        type="button" role="tab">M-4</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link btn-sm extra-small px-3 py-2 fw-bold"
                                                        id="m5-tab" data-bs-toggle="tab" data-bs-target="#m5"
                                                        type="button" role="tab">M-5</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content pt-2" id="testTabContent">
                                        {{-- Modul Panes --}}
                                        @foreach ([['id' => 'm1', 'data' => $allM1], ['id' => 'm2', 'data' => $allM2], ['id' => 'm3', 'data' => $allM3], ['id' => 'm4', 'data' => $allM4]] as $index => $pane)
                                            <div class="tab-pane fade {{ $pane['id'] == 'm1' ? 'show active' : '' }}"
                                                id="{{ $pane['id'] }}" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover align-middle mb-0 test-table">
                                                        <thead>
                                                            <tr class="text-uppercase extra-small fw-bold"
                                                                style="background: #f8fafc; color: #012970;">
                                                                <th class="ps-3 py-2">NO</th>
                                                                <th>NAMA</th>
                                                                <th>PRE-TEST</th>
                                                                <th class="text-end pe-3">POST-TEST</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($pane['data'] as $idx => $user)
                                                                <tr class="extra-small border-bottom border-light">
                                                                    <td class="ps-3 py-2 text-muted">{{ $idx + 1 }}</td>
                                                                    <td class="text-nowrap"><span
                                                                            class="fw-bold text-dark search-target">{{ $user->name }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $pre = $user->hasilTests
                                                                                ->where('type', 'pretest')
                                                                                ->first();
                                                                            $skorPre = $pre ? $pre->skor : '-';
                                                                            $colPre = $pre ? ($pre->skor >= 65 ? 'text-success' : 'text-danger') : 'text-muted opacity-50';
                                                                        @endphp
                                                                        <span
                                                                            class="fw-bold {{ $colPre }}">{{ $skorPre }}</span>
                                                                    </td>
                                                                    <td class="text-end pe-3">
                                                                        @php
                                                                            $post = $user->hasilTests
                                                                                ->where('type', 'posttest')
                                                                                ->first();
                                                                            $skorPost = $post ? $post->skor : '-';
                                                                            $colPost = $post ? ($post->skor >= 65 ? 'text-success' : 'text-danger') : 'text-muted opacity-50';
                                                                        @endphp
                                                                        <span
                                                                            class="fw-bold {{ $colPost }}">{{ $skorPost }}</span>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr class="empty-row">
                                                                    <td colspan="4" class="text-center py-3 text-muted">
                                                                        No data</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="table-pagination-wrapper"></div>
                                            </div>
                                        @endforeach

                                        {{-- Group Task Pane (M5) --}}
                                        <div class="tab-pane fade" id="m5" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover align-middle mb-0 test-table">
                                                    <thead>
                                                        <tr class="text-uppercase extra-small fw-bold"
                                                            style="background: #f8fafc; color: #012970;">
                                                            <th class="ps-3 py-2">NO</th>
                                                            <th>NAMA</th>
                                                            <th class="text-end pe-3">NILAI TUGAS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allTugas as $idx => $tug)
                                                            <tr class="extra-small border-bottom border-light">
                                                                <td class="ps-3 py-2 text-muted">{{ $idx + 1 }}</td>
                                                                <td class="text-nowrap"><span
                                                                        class="fw-bold text-dark search-target">{{ $tug->user->name }}</span>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    <span class="fw-bold text-success">100</span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr class="empty-row">
                                                                <td colspan="3" class="text-center py-3 text-muted">No
                                                                    data</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="table-pagination-wrapper"></div>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-2">
                                        <div
                                            class="extra-small text-muted fw-semibold me-2 opacity-75 d-flex align-items-center">
                                            KETERANGAN:</div>
                                        <div
                                            class="d-flex align-items-center gap-1 extra-small flex-wrap justify-content-center">
                                            <span class="badge text-white py-1 px-2" style="background-color: #00A551;">Nilai >= 65: Tuntas</span>
                                            <span class="badge bg-danger text-white py-1 px-2">Nilai < 65: Belum Tuntas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (Auth::user()->role !== 'timevaluasi')
                            {{-- SECTION 3: SERTIFIKAT KELULUSAN --}}
                            <div class="col-12 mb-3 mt-2">
                                <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3"><i class="bi bi-award fs-6"></i></span>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0">Sertifikat Kelulusan Peserta</h6>
                                            <span class="text-muted extra-small">Daftar penerbitan nomor sertifikat dan link unduh PNG sertifikat per mahasiswa</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card: Hub Sertifikat Mahasiswa --}}
                            <div class="col-12">
                                <div class="card border-0 shadow-sm mb-4 overflow-hidden" id="sertifikatPane">
                                    <div class="card-body p-3 p-xl-4">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                            <div>
                                                <h5 class="card-title mb-0 fs-5">Hub Sertifikat Mahasiswa <span>| E-Sertifikat PNG</span></h5>
                                                <p class="text-muted extra-small mb-0 opacity-75">Status nomor sertifikat resmi dan link download sertifikat PNG masing-masing mahasiswa.</p>
                                            </div>

                                            <div class="d-flex align-items-center flex-wrap gap-2 ms-xl-auto w-100 w-xl-auto">
                                                {{-- Manage dropdown --}}
                                                @if (in_array(Auth::user()->role, ['admin', 'stafbaak']))
                                                    <div class="dropdown me-md-2 mb-2 mb-md-0">
                                                        <button class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3 py-2 fw-bold extra-small dropdown-toggle shadow-none"
                                                            type="button" data-bs-toggle="dropdown" style="border-color: rgba(245, 158, 11, 0.4);">
                                                            <i class="bi bi-gear-fill me-1"></i> Manage
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 extra-small">
                                                            <li><a class="dropdown-item py-2" href="{{ route('sertifikatsetting.index') }}"><i class="bi bi-sliders me-2"></i> Pengaturan & Template</a></li>
                                                            <li><a class="dropdown-item py-2" href="{{ route('rekapkeseluruhan.index') }}"><i class="bi bi-people me-2"></i> Rekap Kelulusan</a></li>
                                                        </ul>
                                                    </div>
                                                @endif

                                                {{-- Search Input --}}
                                                <div class="position-relative w-100 w-md-auto mb-2 mb-md-0">
                                                    <input type="text" id="sertifikatSearch"
                                                        class="form-control form-control-sm rounded-pill px-4 ps-5 w-100 shadow-none border-light"
                                                        placeholder="Cari nama mahasiswa..."
                                                        style="background: #f8fafc; min-width: 220px; height: 38px;">
                                                    <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted opacity-50"
                                                        style="left: 0;"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover align-middle mb-0 sertifikat-table">
                                                <thead>
                                                    <tr class="text-uppercase extra-small fw-bold"
                                                        style="background: #f8fafc; color: #012970;">
                                                        <th class="ps-3 py-2" style="width: 50px;">NO</th>
                                                        <th>NAMA MAHASISWA</th>
                                                        <th>KELOMPOK</th>
                                                        <th>NO. SERTIFIKAT</th>
                                                        <th class="text-end pe-3" style="width: 170px;">SERTIFIKAT PNG</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($allSertifikatMahasiswa as $index => $mhs)
                                                        <tr class="extra-small border-bottom border-light">
                                                            <td class="ps-3 py-2 text-muted">{{ $index + 1 }}</td>
                                                            <td class="text-nowrap">
                                                                <span class="fw-bold text-dark search-target d-block">{{ $mhs->name }}</span>
                                                                <span class="text-muted extra-small">{{ $mhs->id_pendaftar }} &bull; {{ $mhs->program_studi ?? '-' }}</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-light text-dark border extra-small">
                                                                    {{ $mhs->kelompok->nama_kelompok ?? 'Tanpa Kelompok' }}
                                                                </span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                @if ($mhs->nomor_sertifikat)
                                                                    <span class="badge text-white fw-bold px-2 py-1" style="background-color: #00A551;">
                                                                        #{{ str_pad($mhs->nomor_sertifikat, 4, '0', STR_PAD_LEFT) }}/{{ $sertifikatSetting->kode_surat ?? 'PKKMB/UIS' }}
                                                                    </span>
                                                                @elseif ($mhs->kelulusan_is_active)
                                                                    <span class="badge bg-info text-white fw-bold px-2 py-1">
                                                                        Lulus (Siap Terbit)
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted opacity-50">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-end pe-3 text-nowrap">
                                                                @if ($mhs->nomor_sertifikat)
                                                                    <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('sertifikat.verifikasi', ['user' => $mhs->id]) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-success rounded-pill px-3 py-1 extra-small fw-bold shadow-sm d-inline-flex align-items-center gap-1"
                                                                        style="background-color: #00A551; border-color: #00A551;">
                                                                        <i class="bi bi-download"></i> Unduh PNG
                                                                    </a>
                                                                @elseif ($mhs->kelulusan_is_active)
                                                                    <span class="badge bg-light text-warning border border-warning border-opacity-25 px-2 py-1 extra-small">
                                                                        <i class="bi bi-hourglass-split me-1"></i> Siap Diterbitkan
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-light text-muted border px-2 py-1 extra-small opacity-75">
                                                                        <i class="bi bi-lock me-1"></i> Belum Terbit
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr class="empty-row">
                                                            <td colspan="5" class="text-center py-4 text-muted small">Belum ada data mahasiswa</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-pagination-wrapper"></div>

                                        <div class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-2">
                                            <div class="extra-small text-muted fw-semibold me-2 opacity-75 d-flex align-items-center">
                                                KETERANGAN:</div>
                                            <div class="d-flex align-items-center gap-2 extra-small flex-wrap justify-content-center">
                                                <span class="badge bg-success text-white py-1 px-2">Hijau: Sertifikat Resmi Siap Diunduh</span>
                                                <span class="badge bg-secondary text-white py-1 px-2">Abu-abu: Belum Diterbitkan</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- SECTION 4: ANALISIS & GRAFIK PROGRESS --}}
                        <div class="col-12 mb-3 mt-2">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-3"><i class="bi bi-bar-chart-line fs-6"></i></span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Grafik Progress Penuntasan</h6>
                                        <span class="text-muted extra-small">Visualisasi komparasi penyelesaian pre-test, post-test, dan tugas kelompok</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 5: Academic Bar Chart Hub --}}
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow-sm overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                                        <div class="pe-md-3" style="max-width: 620px;">
                                            <h5 class="card-title mb-0 fs-5">Progress Penuntasan Akademik <span>| 6 Pilar Utama</span></h5>
                                            <p class="text-muted extra-small mb-0 opacity-75">Statistik komparasi penuntasan: Absensi, Kedisiplinan, Pre-Test, Post-Test, Tugas Kelompok, dan Ketuntasan Hasil Test.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
                                            @php
                                                $detailTargetUrl = route('rekapkeseluruhan.index');
                                                if (in_array(Auth::user()->role, ['kakakpendamping', 'dosenpendamping'])) {
                                                    $detailTargetUrl = $myKelompokSlug ? route('kelompok.show', $myKelompokSlug) : route('kelompok.index');
                                                }
                                            @endphp
                                            <a href="{{ $detailTargetUrl }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-bold extra-small shadow-none text-nowrap d-inline-flex align-items-center">
                                                <i class="bi bi-person-lines-fill me-1"></i> Detail
                                            </a>
                                            <div
                                                class="bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold extra-small text-nowrap">
                                                Total: {{ $totalMahasiswa }}
                                            </div>
                                        </div>
                                    </div>
                                    <div id="academicBarChart" style="min-height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- MAHASISWA VIEW: JOURNEY --}}
                        <div class="col-12">
                            <div class="card shadow-sm border-0 mb-4 h-auto">
                                <div class="card-body">
                                    <h5 class="card-title">Panduan Peserta <span>| PKKMB</span></h5>
                                    <div class="alert alert-info py-2 small border-0 bg-opacity-10 mb-2"
                                        style="background: rgba(0, 242, 255, 0.05);">
                                        <i class="bi bi-info-circle me-2"></i> Gunakan menu <strong>Modul</strong>
                                        untuk mengakses materi dan mengerjakan pretest/posttest.
                                    </div>
                                    <div class="alert alert-success py-2 small border-0 bg-opacity-10"
                                        style="background: rgba(0, 255, 65, 0.05);">
                                        <i class="bi bi-check-circle me-2"></i> Pastikan anda sudah melakukan scan
                                        absensi setiap datang dan pulang.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Timeline Kehadiran --}}
                                <div class="col-lg-6 col-12">
                                    <div class="card shadow-sm border-0 mb-4 h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Timeline Kehadiran <span>| My Attendance</span></h5>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless align-middle mb-0 text-center">
                                                    <thead>
                                                        <tr class="extra-small text-uppercase fw-bold text-muted border-bottom">
                                                            <th class="text-start py-2">HARI</th>
                                                            <th>DATANG</th>
                                                            <th>PULANG</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $abs1 = \App\Models\AbsenPertama::where('user_id', Auth::id())->first();
                                                            $abs2 = \App\Models\AbsenKedua::where('user_id', Auth::id())->first();
                                                            $abs3 = \App\Models\AbsenKetiga::where('user_id', Auth::id())->first();

                                                            $days = [
                                                                ['label' => 'Hari I', 'data' => $abs1],
                                                                ['label' => 'Hari II', 'data' => $abs2],
                                                                ['label' => 'Hari III', 'data' => $abs3],
                                                            ];
                                                        @endphp

                                                        @foreach ($days as $day)
                                                            <tr class="border-bottom border-light">
                                                                <td class="text-start fw-bold py-3 fs-6">{{ $day['label'] }}</td>
                                                                <td>
                                                                    @php
                                                                        $stPagi = $day['data']->hadir_pagi ?? 'Belum';
                                                                        $clPagi = match ($stPagi) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Alpa', 'Tidak Hadir' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                        $displayPagi = match ($stPagi) {
                                                                            'Hadir' => 'H',
                                                                            'Izin' => 'I',
                                                                            'Alpa', 'Tidak Hadir' => 'TH',
                                                                            default => '-',
                                                                        };
                                                                    @endphp
                                                                    <span class="badge {{ $clPagi }} fs-6 px-3 py-2" style="min-width: 45px;">{{ $displayPagi }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $stSore = $day['data']->hadir_sore ?? 'Belum';
                                                                        $clSore = match ($stSore) {
                                                                            'Hadir' => 'bg-success',
                                                                            'Izin' => 'bg-warning',
                                                                            'Alpa', 'Tidak Hadir' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                        $displaySore = match ($stSore) {
                                                                            'Hadir' => 'H',
                                                                            'Izin' => 'I',
                                                                            'Alpa', 'Tidak Hadir' => 'TH',
                                                                            default => '-',
                                                                        };
                                                                    @endphp
                                                                    <span class="badge {{ $clSore }} fs-6 px-3 py-2" style="min-width: 45px;">{{ $displaySore }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-3">
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-success py-1 px-2" style="min-width: 25px;">H</span>
                                                    Hadir
                                                </div>
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-danger py-1 px-2" style="min-width: 25px;">TH</span>
                                                    Tidak Hadir
                                                </div>
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-warning py-1 px-2" style="min-width: 25px;">I</span>
                                                    Izin
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Monitoring Kedisiplinan --}}
                                <div class="col-lg-6 col-12">
                                    <div class="card shadow-sm border-0 mb-4 h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Monitoring Kedisiplinan <span>| My Discipline</span></h5>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless align-middle mb-0 text-center">
                                                    <thead>
                                                        <tr class="extra-small text-uppercase fw-bold text-muted border-bottom">
                                                            <th class="text-start py-2">HARI</th>
                                                            <th>ATRIBUT</th>
                                                            <th>WAKTU</th>
                                                            <th>PERILAKU</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $dis1 = \App\Models\KedisiplinanPertama::where('user_id', Auth::id())->first();
                                                            $dis2 = \App\Models\KedisiplinanKedua::where('user_id', Auth::id())->first();
                                                            $dis3 = \App\Models\KedisiplinanKetiga::where('user_id', Auth::id())->first();

                                                            $disDays = [
                                                                ['label' => 'Hari I', 'data' => $dis1],
                                                                ['label' => 'Hari II', 'data' => $dis2],
                                                                ['label' => 'Hari III', 'data' => $dis3],
                                                            ];
                                                        @endphp

                                                        @foreach ($disDays as $day)
                                                            <tr class="border-bottom border-light">
                                                                <td class="text-start fw-bold py-3 fs-6">{{ $day['label'] }}</td>
                                                                <td>
                                                                    @php
                                                                        $stA = strtolower($day['data']->kelengkapan_atribut ?? '');
                                                                        $clA = match ($stA) {
                                                                            'lengkap' => 'bg-success',
                                                                            'tidak lengkap' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                        $displayA = match ($stA) {
                                                                            'lengkap' => 'A',
                                                                            'tidak lengkap' => 'TL',
                                                                            default => '-',
                                                                        };
                                                                    @endphp
                                                                    <span class="badge {{ $clA }} fs-6 px-3 py-2" style="min-width: 45px;">{{ $displayA }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $stW = strtolower($day['data']->ketepatan_waktu ?? '');
                                                                        $clW = match ($stW) {
                                                                            'tepat waktu' => 'bg-success',
                                                                            'terlambat' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                        $displayW = match ($stW) {
                                                                            'tepat waktu' => 'W',
                                                                            'terlambat' => 'TL',
                                                                            default => '-',
                                                                        };
                                                                    @endphp
                                                                    <span class="badge {{ $clW }} fs-6 px-3 py-2" style="min-width: 45px;">{{ $displayW }}</span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $stP = strtolower($day['data']->perilaku ?? '');
                                                                        $clP = match ($stP) {
                                                                            'sangat baik' => 'bg-success',
                                                                            'baik' => 'bg-primary',
                                                                            'cukup' => 'bg-warning text-dark',
                                                                            'kurang' => 'bg-danger',
                                                                            default => 'bg-light text-muted border',
                                                                        };
                                                                        $displayP = match ($stP) {
                                                                            'sangat baik' => 'SB',
                                                                            'baik' => 'B',
                                                                            'cukup' => 'C',
                                                                            'kurang' => 'K',
                                                                            default => '-',
                                                                        };
                                                                    @endphp
                                                                    <span class="badge {{ $clP }} fs-6 px-3 py-2" style="min-width: 45px;">{{ $displayP }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-3 pt-3 border-top border-light d-flex flex-wrap justify-content-center gap-2">
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-success py-1 px-2">A</span> Lengkap
                                                </div>
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-danger py-1 px-2">TL</span> Tidak Lengkap/Telat
                                                </div>
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-success py-1 px-2">W</span> Tepat Waktu
                                                </div>
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-success py-1 px-2">SB</span> Sangat Baik
                                                </div>
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-primary py-1 px-2">B</span> Baik
                                                </div>
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-warning text-dark py-1 px-2">C</span> Cukup
                                                </div>
                                                <div class="d-flex align-items-center gap-1 extra-small text-muted fw-semibold">
                                                    <span class="badge bg-danger py-1 px-2">K</span> Kurang
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5 class="card-title mb-0">Rapor & Progress Materi <span>| My Academic
                                                Ledger</span></h5>
                                        @php
                                            $myPretests = \App\Models\HasilTest::where('user_id', Auth::id())
                                                ->where('type', 'pretest')
                                                ->get();
                                            $myPosttests = \App\Models\HasilTest::where('user_id', Auth::id())
                                                ->where('type', 'posttest')
                                                ->get();
                                            $myTugas = \App\Models\SoalTugasKelompok::where(
                                                'user_id',
                                                Auth::id(),
                                            )->get();
                                            $isComplete =
                                                $myPretests->count() >= 4 &&
                                                $myPosttests->count() >= 4 &&
                                                $myTugas->count() >= 1;
                                            $statusText = $isComplete ? 'Selesai' : 'Dalam Proses';
                                            $statusBadge = $isComplete ? 'bg-success' : 'bg-warning';
                                        @endphp
                                        <div class="text-end">
                                            <span class="badge {{ $statusBadge }} rounded-pill px-3 py-2 fs-6">
                                                <i
                                                    class="bi {{ $isComplete ? 'bi-check-all' : 'bi-hourglass-split' }} me-1"></i>
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 bg-light rounded-4 border-light h-100">
                                                <h6
                                                    class="fw-bold mb-3 extra-small text-uppercase tracking-wider text-muted">
                                                    <i class="bi bi-pencil-square me-2 text-info"></i> Pre-Test Scores
                                                </h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @for ($i = 1; $i <= 4; $i++)
                                                        @php $pRec = $myPretests->skip($i-1)->first(); @endphp
                                                        <div
                                                            class="flex-fill bg-white p-2 rounded-3 text-center border shadow-sm">
                                                            <div class="extra-small opacity-50 mb-1">Tes
                                                                {{ $i }}</div>
                                                            <div
                                                                class="fw-bold fs-5 {{ $pRec ? 'text-info' : 'text-muted' }}">
                                                                {{ $pRec ? $pRec->skor : '-' }}</div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 bg-light rounded-4 border-light h-100">
                                                <h6
                                                    class="fw-bold mb-3 extra-small text-uppercase tracking-wider text-muted">
                                                    <i class="bi bi-file-earmark-check me-2 text-primary"></i> Post-Test
                                                    Scores
                                                </h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @for ($i = 1; $i <= 4; $i++)
                                                        @php $poRec = $myPosttests->skip($i-1)->first(); @endphp
                                                        <div
                                                            class="flex-fill bg-white p-2 rounded-3 text-center border shadow-sm">
                                                            <div class="extra-small opacity-50 mb-1">Tes
                                                                {{ $i }}</div>
                                                            <div
                                                                class="fw-bold fs-5 {{ $poRec ? 'text-primary' : 'text-muted' }}">
                                                                {{ $poRec ? $poRec->skor : '-' }}</div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 bg-light rounded-4 border-light h-100">
                                                <h6
                                                    class="fw-bold mb-3 extra-small text-uppercase tracking-wider text-muted">
                                                    <i class="bi bi-person-check me-2 text-success"></i> Tugas & Penuntasan
                                                </h6>
                                                <div class="d-grid gap-2">
                                                    <div
                                                        class="bg-white p-3 rounded-3 d-flex justify-content-between align-items-center border shadow-sm">
                                                        <span class="small fw-semibold">Tugas Kelompok</span>
                                                        @if ($myTugas->count() > 0)
                                                            <span class="badge bg-success rounded-circle p-2"><i
                                                                    class="bi bi-check-lg"></i></span>
                                                        @else
                                                            <span class="badge bg-warning rounded-circle p-2"><i
                                                                    class="bi bi-dash-lg"></i></span>
                                                        @endif
                                                    </div>
                                                    <div class="text-center mt-2">
                                                        <p class="extra-small text-muted italic mb-0">Terima kasih atas
                                                            partisipasi aktif Anda.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar Area --}}
            <div class="col-lg-4">
                @if (Auth::user()->role != 'mahasiswa')
                    {{-- ADMIN SIDEBAR --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Penuntasan Mahasiswa <span>| 6 Metrik</span></h5>
                            <div id="testStatusRadial"></div>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new ApexCharts(document.querySelector("#testStatusRadial"), {
                                        series: [
                                            Math.round(({{ $absenCount }} / ({{ $totalMahasiswa ?: 1 }})) * 100),
                                            Math.round(({{ $disiplinCount }} / ({{ $totalMahasiswa ?: 1 }})) * 100),
                                            Math.round(({{ $pretestCount }} / ({{ $totalMahasiswa ?: 1 }})) * 100),
                                            Math.round(({{ $posttestCount }} / ({{ $totalMahasiswa ?: 1 }})) * 100),
                                            Math.round(({{ $tugasCount }} / ({{ $totalMahasiswa ?: 1 }})) * 100),
                                            Math.round(({{ $hasilTestTuntasCount }} / ({{ $totalMahasiswa ?: 1 }})) * 100)
                                        ],
                                        chart: {
                                            height: 360,
                                            type: 'radialBar'
                                        },
                                        plotOptions: {
                                            radialBar: {
                                                dataLabels: {
                                                    total: {
                                                        show: true,
                                                        label: 'Rata-rata',
                                                        formatter: function (w) {
                                                            let sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                                            let avg = sum / w.globals.seriesTotals.length;
                                                            return avg.toFixed(1) + '%';
                                                        }
                                                    }
                                                }
                                            }
                                        },
                                        labels: ['Absensi %', 'Disiplin %', 'Pre-Test %', 'Post-Test %', 'Tugas %', 'Hasil Test %'],
                                        colors: ['#0284c7', '#06b6d4', '#6366f1', '#8b5cf6', '#f59e0b', '#00A551']
                                    }).render();
                                });
                            </script>
                        </div>
                    </div>

                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
                        <div class="card overflow-auto shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center my-3">
                                    <h5 class="card-title p-0 m-0">Pendaftaran Terbaru <span>| Feed</span></h5>
                                    <a href="{{ route('users.index') }}"
                                        class="btn btn-outline-primary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center shadow-none"
                                        style="width: 24px; height: 24px;" title="View All Users">
                                        <i class="bi bi-eye-fill small"></i>
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tbody>
                                            @forelse($recentUsers as $user)
                                                <tr class="align-middle border-bottom border-light">
                                                    <td class="py-3">
                                                        <div class="fw-bold" style="color: #012970;">{{ $user->name }}
                                                        </div>
                                                        <span class="text-muted extra-small">{{ $user->email }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <span
                                                            class="badge bg-light text-success border border-success border-opacity-10">{{ $user->id_pendaftar }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center py-4">No data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif



                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'stafbaak')
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title">Quick Actions <span>| Admin</span></h5>
                                <div class="d-grid gap-2 mt-2">
                                    <a href="{{ route('users.index') }}"
                                        class="btn btn-outline-success btn-sm rounded-pill text-start ps-3 py-2 border-opacity-25"
                                        style="border-color: rgba(34, 197, 94, 0.3);">
                                        <i class="bi bi-people-fill me-2"></i> User Directory
                                    </a>
                                    <a href="{{ route('rekapkeseluruhan.index') }}"
                                        class="btn btn-outline-info btn-sm rounded-pill text-start ps-3 py-2 border-opacity-25"
                                        style="border-color: rgba(56, 189, 248, 0.3);">
                                        <i class="bi bi-file-earmark-bar-graph me-2"></i> Export Full Recap
                                    </a>
                                    <a href="{{ route('sertifikatsetting.index') }}"
                                        class="btn btn-outline-warning btn-sm rounded-pill text-start ps-3 py-2 border-opacity-25"
                                        style="border-color: rgba(245, 158, 11, 0.4); color: #b45309;">
                                        <i class="bi bi-award-fill me-2"></i> Pengaturan Sertifikat
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    {{-- MAHASISWA SIDEBAR: STATUS KELULUSAN --}}
                    <div class="alert bg-dark text-white border-0 extra-small rounded-4 shadow-sm mb-4"
                        style="background: #0f172a !important;">
                        <i class="bi bi-info-circle text-warning me-2"></i> Nilai akhir bersifat final dan merupakan hasil
                        audit sistem otomatis.
                    </div>
                    <div class="card shadow-sm border-0 mb-4 text-center">
                        <div class="card-body px-4 py-5">
                            <div class="mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-patch-check-fill text-primary fs-1"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">Status Kelulusan</h5>
                                <p class="text-muted extra-small">Hasil Akumulasi Nilai PKKMB</p>
                            </div>

                            @php
                                $userId = Auth::id();
                                $user = Auth::user();

                                // 1. Absensi (6 Sesi: H1 Pagi/Sore, H2 Pagi/Sore, H3 Pagi/Sore)
                                $myAbs1 = \App\Models\AbsenPertama::where('user_id', $userId)->first();
                                $myAbs2 = \App\Models\AbsenKedua::where('user_id', $userId)->first();
                                $myAbs3 = \App\Models\AbsenKetiga::where('user_id', $userId)->first();

                                $absSessionCount = 0;
                                foreach ([$myAbs1, $myAbs2, $myAbs3] as $ab) {
                                    if ($ab) {
                                        if (!empty($ab->hadir_pagi) && $ab->hadir_pagi !== 'Belum Absen') $absSessionCount++;
                                        if (!empty($ab->hadir_sore) && $ab->hadir_sore !== 'Belum Absen') $absSessionCount++;
                                    }
                                }
                                $absComplete = ($absSessionCount >= 6);

                                // 2. Kedisiplinan (3 Hari)
                                $myDis1 = \App\Models\KedisiplinanPertama::where('user_id', $userId)->first();
                                $myDis2 = \App\Models\KedisiplinanKedua::where('user_id', $userId)->first();
                                $myDis3 = \App\Models\KedisiplinanKetiga::where('user_id', $userId)->first();

                                $disDayCount = 0;
                                foreach ([$myDis1, $myDis2, $myDis3] as $di) {
                                    if ($di && !empty($di->kelengkapan_atribut) && $di->kelengkapan_atribut !== '-'
                                            && !empty($di->ketepatan_waktu) && $di->ketepatan_waktu !== '-'
                                            && !empty($di->perilaku) && $di->perilaku !== '-') {
                                        $disDayCount++;
                                    }
                                }
                                $disComplete = ($disDayCount >= 3);

                                // 3. Pre-Test (4 Sesi)
                                $pretestCountUser = \App\Models\HasilTest::where('user_id', $userId)->where('type', 'pretest')->distinct('modul')->count();
                                $pretestComplete = ($pretestCountUser >= 4);

                                // 4. Post-Test (4 Sesi)
                                $posttestCountUser = \App\Models\HasilTest::where('user_id', $userId)->where('type', 'posttest')->distinct('modul')->count();
                                $posttestComplete = ($posttestCountUser >= 4);

                                // 5. Tugas Kelompok (1 Tugas)
                                $tugasCountUser = \App\Models\SoalTugasKelompok::where('user_id', $userId)->count();
                                $tugasComplete = ($tugasCountUser >= 1);

                                // 6. Evaluasi Penyampaian Materi
                                $activeEvaluasiMenus = \App\Models\EvaluasiMenu::available()->where('is_active', true)->get();
                                $userFakultas = strtoupper($user->fakultas ?? '');

                                $requiredEvaluasiTotal = 0;
                                $completedEvaluasiTotal = 0;

                                foreach ($activeEvaluasiMenus as $menu) {
                                    $modelClass = $menu->model_class;
                                    $requiredEvaluasiTotal++;
                                    if ($modelClass && $modelClass::where('user_id', $userId)->exists()) {
                                        $completedEvaluasiTotal++;
                                    }
                                }

                                $evaluasiComplete = ($requiredEvaluasiTotal == 0) || ($completedEvaluasiTotal >= $requiredEvaluasiTotal);

                                // Must be 100% completed across ALL components
                                $isAllComplete = $absComplete && $disComplete && $pretestComplete && $posttestComplete && $tugasComplete && $evaluasiComplete;

                                // Score Calculations (For Display if All Complete)
                                $allTests = \App\Models\HasilTest::where('user_id', $userId)->get();
                                $hasTugas = $tugasComplete;
                                $sumTests = $allTests->sum('skor') + ($hasTugas ? 100 : 0);
                                $scoreTestsRaw = $sumTests / 9;

                                $absPoints = 0;
                                foreach ([$myAbs1, $myAbs2, $myAbs3] as $myAb) {
                                    if ($myAb) {
                                        $p = strtolower($myAb->hadir_pagi ?? '');
                                        if ($p !== '' && str_contains($p, 'hadir') && !str_contains($p, 'tidak')) $absPoints++;
                                        $s = strtolower($myAb->hadir_sore ?? '');
                                        if ($s !== '' && str_contains($s, 'hadir') && !str_contains($s, 'tidak')) $absPoints++;
                                    }
                                }
                                $scoreAbsRaw = ($absPoints / 6) * 100;

                                $disPoints = 0;
                                foreach ([$myDis1, $myDis2, $myDis3] as $myDi) {
                                    if ($myDi) {
                                        if (strtolower($myDi->kelengkapan_atribut ?? '') === 'lengkap') $disPoints++;
                                        if (strtolower($myDi->ketepatan_waktu ?? '') === 'tepat waktu') $disPoints++;
                                        if (in_array(strtolower($myDi->perilaku ?? ''), ['baik', 'sangat baik'])) $disPoints++;
                                    }
                                }
                                $scoreDisRaw = ($disPoints / 9) * 100;

                                $finalScore = $scoreTestsRaw * 0.2 + $scoreAbsRaw * 0.5 + $scoreDisRaw * 0.3;
                                $isPassed = $finalScore >= 65 || (bool) $user->kelulusan_is_active;

                                // Admin can force-publish the result for a specific mahasiswa
                                // even before every component is complete.
                                $showFinalResult = $isAllComplete || $user->kelulusan_is_active;
                            @endphp

                            @if ($showFinalResult)
                                {{-- CARD KELULUSAN TAMPIL JIKA SEMUA TELAH LENGKAP, ATAU DIPAKSA TAMPIL OLEH ADMIN --}}
                                <div class="display-4 fw-bold text-dark mb-1">{{ (float) number_format($finalScore, 2) }}</div>
                                <div class="text-uppercase tracking-wider extra-small fw-bold text-muted mb-4">Total Nilai Terbobot</div>

                                @if ($isPassed)
                                    {{-- CELEBRATORY LULUS BADGE WITH TRUMPETS & RIBBON DUST --}}
                                    <div class="celebration-lulus-card position-relative overflow-hidden p-4 rounded-4 text-white text-center mb-4 shadow-lg" 
                                         id="lulusCelebrationBadge"
                                         onclick="fireConfettiBurst()"
                                         style="cursor: pointer;"
                                         title="Klik untuk merayakan dengan pita & confetti!">
                                        
                                        <canvas id="lulusConfettiCanvas" style="position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1;"></canvas>

                                        <div class="ribbon-dust-overlay">
                                            <span class="ribbon-particle ribbon-1"></span>
                                            <span class="ribbon-particle ribbon-2"></span>
                                            <span class="ribbon-particle ribbon-3"></span>
                                            <span class="ribbon-particle ribbon-4"></span>
                                            <span class="ribbon-particle ribbon-5"></span>
                                            <span class="ribbon-particle ribbon-6"></span>
                                            <span class="sparkle-particle sparkle-1">✨</span>
                                            <span class="sparkle-particle sparkle-2">✨</span>
                                            <span class="sparkle-particle sparkle-3">🌟</span>
                                            <span class="sparkle-particle sparkle-4">⭐</span>
                                        </div>

                                        <div class="shimmer-sweep"></div>

                                        <div class="position-relative z-index-2">
                                            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 mb-3 shadow-sm">
                                                <i class="bi bi-stars"></i>
                                                <span class="extra-small fw-bold text-uppercase tracking-wider text-white">Selamat! Anda Dinyatakan</span>
                                                <i class="bi bi-stars"></i>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-center gap-2 gap-sm-3 my-2">
                                                <div class="trumpet-wrapper trumpet-left">
                                                    <svg width="48" height="48" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" class="trumpet-svg">
                                                        <path d="M12 40L28 28L38 34L22 46L12 40Z" fill="url(#goldGrad1_l)"/>
                                                        <path d="M28 28L50 12L56 16L38 34L28 28Z" fill="url(#goldGrad2_l)"/>
                                                        <path d="M50 12C54 9 60 10 62 14C64 18 61 24 56 24C52 24 50 18 50 12Z" fill="url(#goldGrad1_l)"/>
                                                        <path d="M56 14C60 10 64 8 64 4" stroke="#FDE047" stroke-width="3" stroke-linecap="round"/>
                                                        <path d="M58 18C62 18 64 22 64 26" stroke="#F43F5E" stroke-width="3" stroke-linecap="round"/>
                                                        <path d="M54 22C56 26 58 30 56 34" stroke="#60A5FA" stroke-width="3" stroke-linecap="round"/>
                                                        <defs>
                                                            <linearGradient id="goldGrad1_l" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                                                                <stop stop-color="#FCD34D"/>
                                                                <stop offset="0.5" stop-color="#F59E0B"/>
                                                                <stop offset="1" stop-color="#B45309"/>
                                                            </linearGradient>
                                                            <linearGradient id="goldGrad2_l" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                                                                <stop stop-color="#FEF08A"/>
                                                                <stop offset="0.5" stop-color="#FBBF24"/>
                                                                <stop offset="1" stop-color="#D97706"/>
                                                            </linearGradient>
                                                        </defs>
                                                    </svg>
                                                </div>

                                                <div class="trophy-emblem-wrapper">
                                                    <div class="trophy-glow-ring"></div>
                                                    <div class="trophy-circle">
                                                        <i class="bi bi-trophy-fill trophy-icon"></i>
                                                    </div>
                                                </div>

                                                <div class="trumpet-wrapper trumpet-right">
                                                    <svg width="48" height="48" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" class="trumpet-svg">
                                                        <g transform="scale(-1, 1) translate(-64, 0)">
                                                            <path d="M12 40L28 28L38 34L22 46L12 40Z" fill="url(#goldGrad1_r)"/>
                                                            <path d="M28 28L50 12L56 16L38 34L28 28Z" fill="url(#goldGrad2_r)"/>
                                                            <path d="M50 12C54 9 60 10 62 14C64 18 61 24 56 24C52 24 50 18 50 12Z" fill="url(#goldGrad1_r)"/>
                                                            <path d="M56 14C60 10 64 8 64 4" stroke="#FDE047" stroke-width="3" stroke-linecap="round"/>
                                                            <path d="M58 18C62 18 64 22 64 26" stroke="#EC4899" stroke-width="3" stroke-linecap="round"/>
                                                            <path d="M54 22C56 26 58 30 56 34" stroke="#10B981" stroke-width="3" stroke-linecap="round"/>
                                                        </g>
                                                        <defs>
                                                            <linearGradient id="goldGrad1_r" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                                                                <stop stop-color="#FCD34D"/>
                                                                <stop offset="0.5" stop-color="#F59E0B"/>
                                                                <stop offset="1" stop-color="#B45309"/>
                                                            </linearGradient>
                                                            <linearGradient id="goldGrad2_r" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                                                                <stop stop-color="#FEF08A"/>
                                                                <stop offset="0.5" stop-color="#FBBF24"/>
                                                                <stop offset="1" stop-color="#D97706"/>
                                                            </linearGradient>
                                                        </defs>
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="lulus-ribbon-banner mt-3">
                                                <span class="lulus-text">LULUS</span>
                                            </div>

                                            <p class="mt-3 mb-0 text-white-50 extra-small fw-semibold">
                                                <i class="bi bi-patch-check-fill text-warning me-1"></i> Klik untuk merayakan dengan pita & confetti 🎉
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-4 rounded-4 bg-amber-gradient text-white text-center mb-4 shadow-sm position-relative overflow-hidden">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                                <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                                            </div>
                                        </div>
                                        <span class="fw-bold text-uppercase fs-4 d-block tracking-wider">TIDAK LULUS</span>
                                        <span class="extra-small text-white-50">Nilai akhir berada di bawah ambang batas kelulusan</span>
                                    </div>
                                @endif

                                <div class="text-start border-top pt-4">
                                    <h6 class="extra-small fw-bold text-uppercase text-muted mb-3">Rincian Komponen Nilai</h6>
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="text-secondary"><i class="bi bi-journals me-2 text-info"></i> Test & Tugas</span>
                                        <span class="fw-bold">{{ (float) number_format($scoreTestsRaw, 1) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 small">
                                        <span class="text-secondary"><i class="bi bi-person-check me-2 text-success"></i> Kehadiran </span>
                                        <span class="fw-bold">{{ (float) number_format($scoreAbsRaw, 1) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-0 small">
                                        <span class="text-secondary"><i class="bi bi-shield-check me-2 text-primary"></i> Kedisiplinan</span>
                                        <span class="fw-bold">{{ (float) number_format($scoreDisRaw, 1) }}</span>
                                    </div>
                                </div>

                                {{-- SERTIFIKAT KELULUSAN --}}
                                @php
                                    $sertifikatSetting = \App\Models\SertifikatSetting::current();

                                    if (!$user->nomor_sertifikat) {
                                        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
                                            $lockedSetting = \App\Models\SertifikatSetting::where('id', 1)->lockForUpdate()->first();
                                            $lockedUser = \App\Models\User::where('id', $user->id)->lockForUpdate()->first();
                                            if ($lockedUser && !$lockedUser->nomor_sertifikat) {
                                                $nextNumber = $lockedSetting->nomor_urut_terakhir + 1;
                                                $lockedSetting->update(['nomor_urut_terakhir' => $nextNumber]);
                                                $lockedUser->update(['nomor_sertifikat' => $nextNumber, 'sertifikat_issued_at' => now()]);
                                            }
                                        });
                                        $user->refresh();
                                    }

                                    $nomorSertifikatLengkap = str_pad($user->nomor_sertifikat, 4, '0', STR_PAD_LEFT) . '/' . $sertifikatSetting->kode_surat;

                                    $logoDikti = $sertifikatSetting->logo_dikti ? asset('storage/' . $sertifikatSetting->logo_dikti) : null;
                                    $logoBelmawa = $sertifikatSetting->logo_belmawa ? asset('storage/' . $sertifikatSetting->logo_belmawa) : null;
                                    $logoPkkmb = $sertifikatSetting->logo_pkkmb ? asset('storage/' . $sertifikatSetting->logo_pkkmb) : asset('assets/img/logopkkmb.png');
                                    $logoKampus = $sertifikatSetting->logo_kampus ? asset('storage/' . $sertifikatSetting->logo_kampus) : asset('assets/img/logo_ibsi.png');
                                    $logoLima = $sertifikatSetting->logo_lima ? asset('storage/' . $sertifikatSetting->logo_lima) : null;

                                    $verifikasiUrl = \Illuminate\Support\Facades\URL::signedRoute('sertifikat.verifikasi', ['user' => $user->id]);
                                @endphp

                                <div class="text-start border-top pt-4 mt-1">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="extra-small fw-bold text-uppercase text-muted mb-0"><i class="bi bi-award-fill me-1 text-warning"></i> Sertifikat Kelulusan</h6>
                                        <button type="button" class="btn btn-sm btn-success rounded-pill px-3" onclick="downloadSertifikat(this)">
                                            <i class="bi bi-download me-1"></i> Unduh PNG
                                        </button>
                                    </div>

                                    @include('partials.sertifikat-card', [
                                        'canvasId' => 'sertifikatCanvas',
                                        'nomorUrut' => str_pad($user->nomor_sertifikat, 4, '0', STR_PAD_LEFT),
                                        'kodeSurat' => $sertifikatSetting->kode_surat,
                                        'namaMahasiswa' => $user->name,
                                        'npm' => $user->id_pendaftar,
                                        'prodi' => $user->program_studi ?? '-',
                                        'fakultas' => $user->fakultas ?? '-',
                                        'statusLulus' => $isPassed,
                                        'namaKegiatan' => $sertifikatSetting->nama_kegiatan,
                                        'lokasi' => $sertifikatSetting->lokasi,
                                        'tanggal' => $sertifikatSetting->tanggal_pelaksanaan,
                                        'namaMengetahui' => $sertifikatSetting->nama_mengetahui,
                                        'jabatanMengetahui' => $sertifikatSetting->jabatan_mengetahui,
                                        'nipMengetahui' => $sertifikatSetting->nip_mengetahui,
                                        'namaKetuaPanitia' => $sertifikatSetting->nama_ketua_panitia,
                                        'jabatanKetuaPanitia' => $sertifikatSetting->jabatan_ketua_panitia,
                                        'nupKetuaPanitia' => $sertifikatSetting->nup_ketua_panitia,
                                        'logoDikti' => $logoDikti,
                                        'logoBelmawa' => $logoBelmawa,
                                        'logoPkkmb' => $logoPkkmb,
                                        'logoKampus' => $logoKampus,
                                        'logoLima' => $logoLima,
                                        'verifikasiUrl' => $verifikasiUrl,
                                    ])
                                </div>
                            @else
                                {{-- CARD KELENGKAPAN TAMPIL JIKA SALAH SATU BELUM LENGKAP --}}
                                <div class="p-3 mb-3 bg-light rounded-4 border text-start">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-info-circle-fill text-warning fs-5"></i>
                                        <h6 class="fw-bold mb-0 text-dark small">Status Kelulusan Belum Diterbitkan</h6>
                                    </div>
                                    <p class="extra-small text-muted mb-0">Status kelulusan Anda akan ditampilkan di sini secara otomatis setelah seluruh komponen penilaian & evaluasi diisi lengkap.</p>
                                </div>

                                <div class="text-start border-top pt-3">
                                    <h6 class="extra-small fw-bold text-uppercase text-muted mb-3"><i class="bi bi-list-check me-1 text-primary"></i> Checklist Kelengkapan Anda:</h6>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                                        <span><i class="bi bi-calendar-check me-2 {{ $absComplete ? 'text-success' : 'text-muted' }}"></i> Absensi Kehadiran</span>
                                        <span class="badge {{ $absComplete ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $absSessionCount }}/6 Sesi {!! $absComplete ? '<i class="bi bi-check-lg ms-1"></i>' : '' !!}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                                        <span><i class="bi bi-shield-check me-2 {{ $disComplete ? 'text-success' : 'text-muted' }}"></i> Penilaian Kedisiplinan</span>
                                        <span class="badge {{ $disComplete ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $disDayCount }}/3 Hari {!! $disComplete ? '<i class="bi bi-check-lg ms-1"></i>' : '' !!}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                                        <span><i class="bi bi-pencil-square me-2 {{ $pretestComplete ? 'text-success' : 'text-muted' }}"></i> Ujian Pre-Test</span>
                                        <span class="badge {{ $pretestComplete ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $pretestCountUser }}/4 Tes {!! $pretestComplete ? '<i class="bi bi-check-lg ms-1"></i>' : '' !!}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                                        <span><i class="bi bi-file-earmark-check me-2 {{ $posttestComplete ? 'text-success' : 'text-muted' }}"></i> Ujian Post-Test</span>
                                        <span class="badge {{ $posttestComplete ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $posttestCountUser }}/4 Tes {!! $posttestComplete ? '<i class="bi bi-check-lg ms-1"></i>' : '' !!}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                                        <span><i class="bi bi-journal-text me-2 {{ $tugasComplete ? 'text-success' : 'text-muted' }}"></i> Tugas Kelompok</span>
                                        <span class="badge {{ $tugasComplete ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $tugasCountUser }}/1 Tugas {!! $tugasComplete ? '<i class="bi bi-check-lg ms-1"></i>' : '' !!}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-0 small">
                                        <span><i class="bi bi-clipboard2-check me-2 {{ $evaluasiComplete ? 'text-success' : 'text-muted' }}"></i> Evaluasi Penyampaian Materi</span>
                                        <span class="badge {{ $evaluasiComplete ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $completedEvaluasiTotal }}/{{ $requiredEvaluasiTotal }} Evaluasi {!! $evaluasiComplete ? '<i class="bi bi-check-lg ms-1"></i>' : '' !!}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>



    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
        <script>
            function downloadSertifikat(btn) {
                const target = document.getElementById('sertifikatCanvas');
                if (!target || typeof html2canvas !== 'function') return;

                const originalLabel = btn ? btn.innerHTML : null;
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
                }

                // The preview is visually shrunk via CSS transform:scale() to fit its
                // container; reset it to full design size (1000x707) before capture so
                // the exported PNG is always full resolution, not the on-screen scale.
                const originalTransform = target.style.transform;
                target.style.transform = 'none';

                html2canvas(target, {
                    scale: 4,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    width: 1000,
                    height: 707
                }).then(function (canvas) {
                    const link = document.createElement('a');
                    link.download = 'Sertifikat-Kelulusan-{{ \Illuminate\Support\Str::slug($user->name ?? Auth::user()->name) }}.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                }).catch(function (err) {
                    console.error('Gagal membuat sertifikat:', err);
                    alert('Gagal mengunduh sertifikat. Silakan coba lagi.');
                }).finally(function () {
                    target.style.transform = originalTransform;
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = originalLabel;
                    }
                });
            }

            let scopedCardConfetti = null;

            function getCardConfettiInstance() {
                if (!scopedCardConfetti) {
                    const canvas = document.getElementById('lulusConfettiCanvas');
                    if (canvas && typeof confetti === 'function') {
                        scopedCardConfetti = confetti.create(canvas, {
                            resize: true,
                            useWorker: true
                        });
                    }
                }
                return scopedCardConfetti;
            }

            function fireConfettiBurst() {
                const cardConfetti = getCardConfettiInstance();
                if (cardConfetti) {
                    // Left trumpet confetti burst inside card
                    cardConfetti({
                        particleCount: 45,
                        angle: 60,
                        spread: 55,
                        origin: { x: 0.1, y: 0.6 },
                        colors: ['#FDE047', '#F43F5E', '#3B82F6', '#10B981', '#F59E0B', '#A78BFA']
                    });
                    // Right trumpet confetti burst inside card
                    cardConfetti({
                        particleCount: 45,
                        angle: 120,
                        spread: 55,
                        origin: { x: 0.9, y: 0.6 },
                        colors: ['#FDE047', '#EC4899', '#3B82F6', '#34D399', '#FBBF24', '#F472B6']
                    });
                }
            }

            function startContinuousConfetti() {
                const cardConfetti = getCardConfettiInstance();
                if (!cardConfetti) return;

                // Continuous gentle confetti shower INSIDE THE CARD ONLY
                function dropShower() {
                    if (document.hidden) return;

                    cardConfetti({
                        particleCount: 2,
                        startVelocity: 0,
                        ticks: 220,
                        origin: {
                            x: Math.random(),
                            y: -0.15
                        },
                        colors: ['#FDE047', '#F43F5E', '#3B82F6', '#10B981', '#F59E0B', '#A78BFA', '#EC4899', '#34D399'],
                        gravity: 0.7,
                        scalar: 1.1,
                        drift: (Math.random() - 0.5) * 0.6
                    });
                }

                // Drop continuous ribbon dust confetti inside card every 220ms non-stop
                setInterval(dropShower, 220);

                // Periodic celebratory trumpet bursts inside card every 3.2 seconds
                setInterval(() => {
                    if (!document.hidden) {
                        cardConfetti({
                            particleCount: 20,
                            angle: 60,
                            spread: 50,
                            origin: { x: 0.1, y: 0.65 },
                            colors: ['#FDE047', '#F43F5E', '#10B981', '#FBBF24']
                        });
                        cardConfetti({
                            particleCount: 20,
                            angle: 120,
                            spread: 50,
                            origin: { x: 0.9, y: 0.65 },
                            colors: ['#3B82F6', '#EC4899', '#34D399', '#FDE047']
                        });
                    }
                }, 3200);
            }

            document.addEventListener("DOMContentLoaded", () => {
                if (document.getElementById('lulusCelebrationBadge')) {
                    // Initial burst inside card on page load
                    setTimeout(() => {
                        fireConfettiBurst();
                    }, 400);

                    // Continuous confetti & ribbon dust shower inside card
                    setTimeout(() => {
                        startContinuousConfetti();
                    }, 800);
                }
            });
        </script>
        <script>


            // Academic Bar Chart: 6 Pillars Penuntasan Komparatif
            document.addEventListener("DOMContentLoaded", () => {
                new ApexCharts(document.querySelector("#academicBarChart"), {
                    series: [{
                        name: 'Sudah Tuntas / Mengikuti',
                        data: [
                            {{ $absenCount }},
                            {{ $disiplinCount }},
                            {{ $pretestCount }},
                            {{ $posttestCount }},
                            {{ $tugasCount }},
                            {{ $hasilTestTuntasCount }}
                        ]
                    }, {
                        name: 'Belum Tuntas / Belum Isi',
                        data: [
                            {{ max(0, $totalMahasiswa - $absenCount) }},
                            {{ max(0, $totalMahasiswa - $disiplinCount) }},
                            {{ max(0, $totalMahasiswa - $pretestCount) }},
                            {{ max(0, $totalMahasiswa - $posttestCount) }},
                            {{ max(0, $totalMahasiswa - $tugasCount) }},
                            {{ max(0, $totalMahasiswa - $hasilTestTuntasCount) }}
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                        stacked: false // Better comparison side-by-side
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            columnWidth: '55%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            colors: ["#304758"]
                        },
                        offsetY: -20
                    },
                    colors: ['#00A551', '#f59e0b'], // Emerald Green for Tuntas, Amber for Belum
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '11px',
                        markers: {
                            radius: 12
                        }
                    },
                    xaxis: {
                        categories: ['Absensi', 'Kedisiplinan', 'Pre-Test', 'Post-Test', 'Kelompok', 'Hasil Test'],
                    },
                    yaxis: {
                        labels: {
                            show: true
                        }
                    },
                    grid: {
                        borderColor: '#f1f1f1',
                        strokeDashArray: 4
                    },
                    tooltip: {
                        theme: 'light'
                    }
                }).render();
            });

            // Universal Table Client-side Pagination (10 items per page)
            function setupTablePagination(paneElement, pageSize = 10) {
                if (!paneElement) return;
                const table = paneElement.querySelector('table');
                const paginationWrapper = paneElement.querySelector('.table-pagination-wrapper');
                if (!table || !paginationWrapper) return;

                let currentPage = 1;

                function renderPagination() {
                    const allRows = Array.from(table.querySelectorAll('tbody tr:not(.empty-row)'));
                    const visibleRows = allRows.filter(row => row.dataset.searchHidden !== 'true');
                    const totalRows = visibleRows.length;
                    const totalPages = Math.ceil(totalRows / pageSize) || 1;

                    if (currentPage > totalPages) currentPage = totalPages;
                    if (currentPage < 1) currentPage = 1;

                    const startIndex = (currentPage - 1) * pageSize;
                    const endIndex = startIndex + pageSize;

                    let matchCount = 0;
                    allRows.forEach(row => {
                        if (row.dataset.searchHidden === 'true') {
                            row.style.display = 'none';
                        } else {
                            if (matchCount >= startIndex && matchCount < endIndex) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                            matchCount++;
                        }
                    });

                    // Show/hide empty row indicator if all rows filtered out or no data
                    const emptyRow = table.querySelector('tbody tr.empty-row');
                    if (emptyRow) {
                        emptyRow.style.display = (totalRows === 0) ? '' : 'none';
                    }

                    if (totalRows === 0) {
                        paginationWrapper.innerHTML = '';
                        return;
                    }

                    const showStart = startIndex + 1;
                    const showEnd = Math.min(endIndex, totalRows);

                    let html = `
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-2 border-top border-light w-100">
                            <div class="extra-small text-muted">
                                Menampilkan <span class="fw-bold text-dark">${showStart}-${showEnd}</span> dari <span class="fw-bold text-dark">${totalRows}</span> data
                            </div>
                    `;

                    if (totalPages > 1) {
                        html += `<nav aria-label="Pagination"><ul class="pagination pagination-sm mb-0 gap-1">`;
                        
                        // Prev
                        html += `
                            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                                <button class="page-link rounded px-2 py-1 extra-small btn-prev" type="button" ${currentPage === 1 ? 'disabled' : ''}>
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                            </li>
                        `;

                        let startP = Math.max(1, currentPage - 2);
                        let endP = Math.min(totalPages, startP + 4);
                        if (endP - startP < 4) {
                            startP = Math.max(1, endP - 4);
                        }

                        for (let p = startP; p <= endP; p++) {
                            html += `
                                <li class="page-item ${p === currentPage ? 'active' : ''}">
                                    <button class="page-link rounded px-2 py-1 extra-small btn-page" type="button" data-page="${p}">${p}</button>
                                </li>
                            `;
                        }

                        // Next
                        html += `
                            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                                <button class="page-link rounded px-2 py-1 extra-small btn-next" type="button" ${currentPage === totalPages ? 'disabled' : ''}>
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </li>
                        `;

                        html += `</ul></nav>`;
                    }

                    html += `</div>`;
                    paginationWrapper.innerHTML = html;

                    // Listeners
                    const prevBtn = paginationWrapper.querySelector('.btn-prev');
                    if (prevBtn) {
                        prevBtn.addEventListener('click', () => {
                            if (currentPage > 1) {
                                currentPage--;
                                renderPagination();
                            }
                        });
                    }

                    const nextBtn = paginationWrapper.querySelector('.btn-next');
                    if (nextBtn) {
                        nextBtn.addEventListener('click', () => {
                            if (currentPage < totalPages) {
                                currentPage++;
                                renderPagination();
                            }
                        });
                    }

                    paginationWrapper.querySelectorAll('.btn-page').forEach(btn => {
                        btn.addEventListener('click', () => {
                            currentPage = parseInt(btn.getAttribute('data-page'));
                            renderPagination();
                        });
                    });
                }

                table._renderPagination = renderPagination;
                table._resetPage = () => { currentPage = 1; };
                renderPagination();
            }

            function bindTabSearch(inputId, tabContentId) {
                const input = document.getElementById(inputId);
                const tabContent = document.getElementById(tabContentId);
                if (!input || !tabContent) return;

                input.addEventListener('input', function() {
                    const filter = this.value.toLowerCase().trim();
                    const activePane = tabContent.querySelector('.tab-pane.active');
                    if (!activePane) return;

                    const table = activePane.querySelector('table');
                    if (!table) return;

                    const rows = table.querySelectorAll('tbody tr:not(.empty-row)');
                    rows.forEach(row => {
                        const nameCell = row.querySelector('.search-target');
                        if (nameCell) {
                            const text = nameCell.textContent.toLowerCase();
                            if (text.includes(filter)) {
                                delete row.dataset.searchHidden;
                            } else {
                                row.dataset.searchHidden = 'true';
                            }
                        }
                    });

                    if (table._resetPage) table._resetPage();
                    if (table._renderPagination) table._renderPagination();
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                // Initialize pagination on all tab panes
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    setupTablePagination(pane, 10);
                });

                // Initialize Sertifikat table pagination
                const sertifikatPane = document.getElementById('sertifikatPane');
                if (sertifikatPane) {
                    setupTablePagination(sertifikatPane, 10);
                }

                // Bind Sertifikat Search
                const sertifikatInput = document.getElementById('sertifikatSearch');
                if (sertifikatInput && sertifikatPane) {
                    sertifikatInput.addEventListener('input', function() {
                        const filter = this.value.toLowerCase().trim();
                        const table = sertifikatPane.querySelector('table');
                        if (!table) return;

                        const rows = table.querySelectorAll('tbody tr:not(.empty-row)');
                        rows.forEach(row => {
                            const nameCell = row.querySelector('.search-target');
                            if (nameCell) {
                                const text = nameCell.textContent.toLowerCase();
                                if (text.includes(filter)) {
                                    delete row.dataset.searchHidden;
                                } else {
                                    row.dataset.searchHidden = 'true';
                                }
                            }
                        });

                        if (table._resetPage) table._resetPage();
                        if (table._renderPagination) table._renderPagination();
                    });
                }

                // Bind searches
                bindTabSearch('attendanceSearch', 'attendanceTabContent');
                bindTabSearch('disciplineSearch', 'disciplineTabContent');
                bindTabSearch('testSearch', 'testTabContent');
                bindTabSearch('snapshotSearch', 'snapshotTabContent');

                // On tab change, re-render pagination and apply any existing search
                document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tabBtn => {
                    tabBtn.addEventListener('shown.bs.tab', (e) => {
                        const targetSelector = e.target.getAttribute('data-bs-target');
                        if (targetSelector) {
                            const targetPane = document.querySelector(targetSelector);
                            if (targetPane) {
                                const table = targetPane.querySelector('table');
                                if (table && table._renderPagination) {
                                    const parentTabContent = targetPane.closest('.tab-content');
                                    if (parentTabContent) {
                                        let searchInput = null;
                                        if (parentTabContent.id === 'attendanceTabContent') searchInput = document.getElementById('attendanceSearch');
                                        else if (parentTabContent.id === 'disciplineTabContent') searchInput = document.getElementById('disciplineSearch');
                                        else if (parentTabContent.id === 'testTabContent') searchInput = document.getElementById('testSearch');
                                        else if (parentTabContent.id === 'snapshotTabContent') searchInput = document.getElementById('snapshotSearch');

                                        if (searchInput && searchInput.value.trim() !== '') {
                                            const filter = searchInput.value.toLowerCase().trim();
                                            const rows = table.querySelectorAll('tbody tr:not(.empty-row)');
                                            rows.forEach(row => {
                                                const nameCell = row.querySelector('.search-target');
                                                if (nameCell) {
                                                    const text = nameCell.textContent.toLowerCase();
                                                    if (text.includes(filter)) delete row.dataset.searchHidden;
                                                    else row.dataset.searchHidden = 'true';
                                                }
                                            });
                                        } else {
                                            const rows = table.querySelectorAll('tbody tr:not(.empty-row)');
                                            rows.forEach(row => delete row.dataset.searchHidden);
                                        }
                                    }
                                    if (table._resetPage) table._resetPage();
                                    table._renderPagination();
                                }
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
