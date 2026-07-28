<style>
    .sertifikat-preview-wrapper {
        display: flex;
        justify-content: center;
        padding: 4px;
    }

    /* Reserves the correct A4-landscape box in normal layout flow; the fixed-size
       .sertifikat-canvas inside is scaled (via JS) to fit whatever width this gets,
       so the certificate's internal layout never reflows/clips at small widths. */
    .sertifikat-scale-outer {
        position: relative;
        width: 100%;
        max-width: 1000px;
        aspect-ratio: 1.4142;
    }

    .sertifikat-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 1000px;
        height: 707px;
        transform-origin: top left;
        background-image: url('{{ asset('assets/img/background_sertifikat.jpeg') }}');
        background-size: 100% 100%;
        background-repeat: no-repeat;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        cursor: zoom-in;
    }

    .sertifikat-logos {
        position: absolute;
        top: 62px;
        left: 62px;
        z-index: 3;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .sertifikat-logos img { height: 44px; width: auto; object-fit: contain; }

    /* Safe content zone: inset from the image's border line and clear of the
       ornamental corner artwork (heavier at top-right/bottom-left), and clear
       of the logo row above. */
    .sertifikat-inner {
        position: absolute;
        top: 116px;
        left: 150px;
        right: 150px;
        bottom: 195px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        font-family: 'Poppins', sans-serif;
        color: #1e293b;
    }

    /* Text elements must never flex-shrink below their natural size — otherwise
       the browser compresses their box below the rendered text height when
       total content is tight, which visually overlaps neighboring lines. */
    .sertifikat-inner > * { flex-shrink: 0; }

    .sertifikat-title { font-size: 2.3rem; font-weight: 900; letter-spacing: 3px; color: #0f8a3f; line-height: 1.15; }
    .sertifikat-nomor { font-size: 0.85rem; font-weight: 600; color: #475569; margin-top: 6px; margin-bottom: 4px; line-height: 1.4; }
    .sertifikat-label { font-size: 0.8rem; color: #64748b; margin-top: 6px; line-height: 1.4; }

    .sertifikat-namebox {
        font-family: 'Great Vibes', cursive;
        font-size: 2.4rem;
        color: #166534;
        padding: 2px 34px;
        margin: 4px 0 2px;
        line-height: 1.25;
    }

    .sertifikat-npm { font-size: 0.8rem; color: #475569; margin-bottom: 6px; line-height: 1.4; }
    .sertifikat-prodi, .sertifikat-fakultas { font-size: 0.82rem; font-weight: 700; color: #166534; letter-spacing: 0.5px; line-height: 1.4; }
    .sertifikat-status { font-size: 2.3rem; font-weight: 900; letter-spacing: 3px; margin: 4px 0; line-height: 1.2; }
    .sertifikat-status.is-lulus { color: #0f8a3f; }
    .sertifikat-status.is-tidak-lulus { color: #b91c1c; }
    .sertifikat-peserta { font-size: 0.82rem; color: #334155; margin-top: 2px; line-height: 1.3; }
    .sertifikat-kegiatan { font-size: 0.86rem; font-weight: 800; color: #166534; max-width: 600px; line-height: 1.3; margin: 3px 0; }
    .sertifikat-tempat { font-size: 0.8rem; color: #475569; margin-bottom: auto; line-height: 1.3; }
    .sertifikat-ttd-row { display: flex; justify-content: space-between; width: 100%; max-width: 540px; margin-top: 20px; gap: 40px; }
    .sertifikat-ttd { flex: 0 0 auto; display: flex; flex-direction: column; align-items: center; text-align: center; }
    .sertifikat-ttd-label { font-size: 0.78rem; color: #334155; margin-bottom: 4px; line-height: 1.4; white-space: nowrap; }
    /* Fixed-height slot shared by both columns (QR or signature image) so the
       two names always land on the same baseline, regardless of which visual
       (and whatever its native size) each column happens to use. */
    .sertifikat-ttd-visual { height: 44px; display: flex; align-items: flex-end; justify-content: center; margin-bottom: 4px; }
    .sertifikat-ttd-sign { max-height: 44px; width: auto; object-fit: contain; position: relative; z-index: 1; }
    .sertifikat-ttd-ketua { position: relative; }
    .sertifikat-ttd-stamp {
        position: absolute;
        top: 42px;
        left: 22%;
        width: 105px;
        height: auto;
        transform: translateX(-50%);
        opacity: 0.85;
        z-index: 0;
        pointer-events: none;
    }
    .sertifikat-ttd-qr-wrap { position: relative; width: 40px; height: 40px; }
    .sertifikat-ttd-qr { width: 40px; height: 40px; }
    .sertifikat-ttd-qr img, .sertifikat-ttd-qr canvas { width: 100%; height: 100%; }
    .sertifikat-ttd-qr-logo {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 14px;
        height: 14px;
        background: #ffffff;
        border-radius: 3px;
        padding: 1px;
        object-fit: contain;
    }
    .sertifikat-ttd-name { font-size: 0.84rem; font-weight: 800; color: #1e293b; line-height: 1.3; white-space: nowrap; display: inline-block; border-bottom: 1px solid #94a3b8; padding-bottom: 3px; }
    .sertifikat-ttd-nip { font-size: 0.7rem; color: #475569; margin-top: 7px; line-height: 1.3; white-space: nowrap; }

    .sertifikat-zoom-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.82);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: 24px;
        box-sizing: border-box;
    }

    .sertifikat-zoom-modal.is-open { display: flex; }

    .sertifikat-zoom-modal-inner {
        position: relative;
    }

    .sertifikat-zoom-modal-inner .sertifikat-canvas {
        cursor: default;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
    }

    .sertifikat-zoom-close {
        position: absolute;
        top: -18px;
        right: -18px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: #ffffff;
        color: #1e293b;
        font-size: 1.4rem;
        line-height: 1;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(0,0,0,0.3);
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

<div class="sertifikat-preview-wrapper">
    <div class="sertifikat-scale-outer" data-sertifikat-scale-outer>
    <div id="{{ $canvasId }}" class="sertifikat-canvas" onclick="openSertifikatZoom('{{ $canvasId }}')" title="Klik untuk memperbesar">
        <div class="sertifikat-logos">
            <img data-logo-field="logo_dikti" src="{{ $logoDikti }}" alt="DIKTI" style="{{ $logoDikti ? '' : 'display:none' }}">
            <img data-logo-field="logo_belmawa" src="{{ $logoBelmawa }}" alt="Belmawa" style="{{ $logoBelmawa ? '' : 'display:none' }}">
            <img data-logo-field="logo_pkkmb" src="{{ $logoPkkmb }}" alt="PKKMB" style="{{ $logoPkkmb ? '' : 'display:none' }}">
            <img data-logo-field="logo_kampus" src="{{ $logoKampus }}" alt="UIS" style="{{ $logoKampus ? '' : 'display:none' }}">
            <img data-logo-field="logo_lima" src="{{ $logoLima ?? '' }}" alt="Logo Tambahan" style="{{ !empty($logoLima) ? '' : 'display:none' }}">
        </div>

        <div class="sertifikat-inner">
            <div class="sertifikat-title">SERTIFIKAT</div>
            <div class="sertifikat-nomor">NO : {{ $nomorUrut }}/<span data-field="kode_surat">{{ $kodeSurat }}</span></div>
            <div class="sertifikat-label">Diberikan Kepada:</div>

            <div class="sertifikat-namebox">{{ $namaMahasiswa }}</div>
            <div class="sertifikat-npm">NIM.{{ $npm }}</div>

            <div class="sertifikat-prodi">PROGRAM STUDI {{ strtoupper($prodi) }}</div>
            <div class="sertifikat-fakultas">{{ strtoupper($fakultas) }} DINYATAKAN</div>

            <div class="sertifikat-status {{ $statusLulus ? 'is-lulus' : 'is-tidak-lulus' }}">{{ $statusLulus ? 'LULUS' : 'TIDAK LULUS' }}</div>

            <div class="sertifikat-peserta">Sebagai Peserta dalam Kegiatan:</div>
            <div class="sertifikat-kegiatan" data-field="nama_kegiatan">{!! str_replace('(PKKMB)', '(PKKMB)<br>', e($namaKegiatan)) !!}</div>
            <div class="sertifikat-tempat">diselenggarakan di <span data-field="lokasi">{{ $lokasi }}</span>, tanggal : <span data-field="tanggal_pelaksanaan">{{ $tanggal }}</span></div>

            <div class="sertifikat-ttd-row">
                <div class="sertifikat-ttd">
                    <div class="sertifikat-ttd-label">Mengetahui,<br><span data-field="jabatan_mengetahui">{{ $jabatanMengetahui }}</span></div>
                    <div class="sertifikat-ttd-visual">
                        @if (!empty($verifikasiUrl))
                            <div class="sertifikat-ttd-qr-wrap">
                                <div id="sertifikatQr-{{ $canvasId }}" class="sertifikat-ttd-qr"></div>
                                <img src="{{ asset('assets/img/logo_ibsi.png') }}" alt="UIS" class="sertifikat-ttd-qr-logo">
                            </div>
                        @endif
                    </div>
                    <div class="sertifikat-ttd-name" data-field="nama_mengetahui">{{ $namaMengetahui }}</div>
                    @if (!empty($nipMengetahui))
                        <div class="sertifikat-ttd-nip" data-field="nip_mengetahui">{{ $nipMengetahui }}</div>
                    @endif
                </div>
                <div class="sertifikat-ttd sertifikat-ttd-ketua">
                    <div class="sertifikat-ttd-label">Ketua Panitia,<br><span data-field="jabatan_ketua_panitia">{{ $jabatanKetuaPanitia }}</span></div>
                    <img src="{{ asset('assets/img/cap_panitia.png') }}" alt="Cap Panitia" class="sertifikat-ttd-stamp">
                    <div class="sertifikat-ttd-visual">
                        <img src="{{ asset('assets/img/ttd_ketua_bg_babang.png') }}" alt="Tanda Tangan Ketua Panitia" class="sertifikat-ttd-sign">
                    </div>
                    <div class="sertifikat-ttd-name" data-field="nama_ketua_panitia">{{ $namaKetuaPanitia }}</div>
                    @if (!empty($nupKetuaPanitia))
                        <div class="sertifikat-ttd-nip" data-field="nup_ketua_panitia">{{ $nupKetuaPanitia }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@if (!empty($verifikasiUrl))
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        (function () {
            var qrEl = document.getElementById('sertifikatQr-{{ $canvasId }}');
            if (qrEl && typeof QRCode === 'function') {
                new QRCode(qrEl, {
                    text: {!! json_encode($verifikasiUrl) !!},
                    width: 40,
                    height: 40,
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        })();
    </script>
@endif

<script>
    (function () {
        var canvas = document.getElementById('{{ $canvasId }}');
        if (!canvas) return;
        var outer = canvas.parentElement;

        function fitSertifikatScale() {
            if (canvas.dataset.zoomed === '1') return;
            var scale = outer.clientWidth / 1000;
            canvas.style.transform = 'scale(' + scale + ')';
        }

        window.__sertifikatFit = window.__sertifikatFit || {};
        window.__sertifikatFit['{{ $canvasId }}'] = fitSertifikatScale;

        fitSertifikatScale();

        if (typeof ResizeObserver !== 'undefined') {
            new ResizeObserver(fitSertifikatScale).observe(outer);
        } else {
            window.addEventListener('resize', fitSertifikatScale);
        }
    })();

    if (typeof window.openSertifikatZoom !== 'function') {
        window.__sertifikatZoomState = { canvas: null, parent: null, next: null };

        window.openSertifikatZoom = function (canvasId) {
            var canvas = document.getElementById(canvasId);
            if (!canvas) return;

            var modal = document.getElementById('sertifikatZoomModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'sertifikatZoomModal';
                modal.className = 'sertifikat-zoom-modal';
                modal.innerHTML = '<div class="sertifikat-zoom-modal-inner"><button type="button" class="sertifikat-zoom-close" aria-label="Tutup">&times;</button></div>';
                document.body.appendChild(modal);
                modal.addEventListener('click', function (e) {
                    if (e.target === modal || e.target.classList.contains('sertifikat-zoom-close')) {
                        window.closeSertifikatZoom();
                    }
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') window.closeSertifikatZoom();
                });
            }

            window.__sertifikatZoomState = {
                canvas: canvas,
                parent: canvas.parentElement,
                next: canvas.nextSibling
            };

            var inner = modal.querySelector('.sertifikat-zoom-modal-inner');
            inner.appendChild(canvas);
            canvas.dataset.zoomed = '1';

            var scale = Math.min((window.innerWidth * 0.9) / 1000, (window.innerHeight * 0.85) / 707, 1.6);
            canvas.style.transform = 'scale(' + scale + ')';

            inner.style.width = (1000 * scale) + 'px';
            inner.style.height = (707 * scale) + 'px';

            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            
        };

        window.closeSertifikatZoom = function () {
            var modal = document.getElementById('sertifikatZoomModal');
            var state = window.__sertifikatZoomState;
            if (modal) modal.classList.remove('is-open');
            document.body.style.overflow = '';

            if (state && state.canvas && state.parent) {
                if (state.next) {
                    state.parent.insertBefore(state.canvas, state.next);
                } else {
                    state.parent.appendChild(state.canvas);
                }
                state.canvas.dataset.zoomed = '0';
                if (window.__sertifikatFit && window.__sertifikatFit[state.canvas.id]) {
                    window.__sertifikatFit[state.canvas.id]();
                }
            }
        };
    }
</script>
