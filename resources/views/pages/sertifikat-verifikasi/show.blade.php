<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Sertifikat PKKMB - Universitas Ibnu Sina</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f6b32 0%, #4c9a2a 55%, #d7e07a 100%);
            min-height: 100vh;
            padding: 40px 16px;
        }

        .verifikasi-card {
            max-width: 620px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            padding: 36px 32px;
            text-align: center;
        }

        .verifikasi-logo { height: 90px; margin-bottom: 14px; }
        .verifikasi-title { font-size: 1.5rem; font-weight: 900; color: #14532d; margin: 0; }
        .verifikasi-subtitle { font-size: 0.95rem; font-weight: 700; color: #2f7a3f; margin: 4px 0 24px; }

        .verifikasi-info-box {
            background: #f8faf8;
            border-radius: 12px;
            padding: 22px 24px;
            text-align: left;
        }

        .verifikasi-info-heading {
            font-weight: 700;
            font-size: 0.95rem;
            color: #1e293b;
            margin: 0 0 8px;
        }

        .verifikasi-info-heading-underline {
            width: 100%;
            height: 2px;
            background: #2f7a3f;
            margin-bottom: 14px;
        }

        .verifikasi-row { display: flex; gap: 10px; padding: 4px 0; font-size: 0.9rem; }
        .verifikasi-row-label { width: 130px; flex-shrink: 0; font-weight: 700; color: #1e293b; }
        .verifikasi-row-sep { flex-shrink: 0; color: #1e293b; }
        .verifikasi-row-value { color: #b91c1c; font-weight: 600; }
        .verifikasi-row-value.is-lulus { color: #0f8a3f; }
        .verifikasi-row-value.is-tidak-lulus { color: #b91c1c; }
        .verifikasi-row-value.is-belum { color: #b45309; }

        .verifikasi-toggle-btn {
            margin-top: 26px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #14532d;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 12px 28px;
            font-weight: 800;
            font-size: 0.85rem;
            letter-spacing: 1px;
            cursor: pointer;
            text-transform: uppercase;
        }

        .verifikasi-toggle-btn:hover { background: #0f6b32; }

        .verifikasi-cert-wrapper {
            display: none;
            max-width: 900px;
            margin: 24px auto 0;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            background: #f1f5f9;
        }

        .verifikasi-cert-wrapper.is-open { display: block; }

        .verifikasi-footer {
            max-width: 620px;
            margin: 20px auto 0;
            text-align: center;
            color: #ffffff;
            font-size: 0.75rem;
            opacity: 0.85;
        }
    </style>
</head>
<body>
    <div class="verifikasi-card">
        <img src="{{ asset('assets/img/logo_ibsi.png') }}" alt="Universitas Ibnu Sina" class="verifikasi-logo">
        <h1 class="verifikasi-title">UNIVERSITAS IBNU SINA BATAM</h1>
        <p class="verifikasi-subtitle">VERIFIKASI SERTIFIKAT PKKMB</p>

        <div class="verifikasi-info-box">
            <p class="verifikasi-info-heading">Sertifikat telah diterbitkan untuk:</p>
            <div class="verifikasi-info-heading-underline"></div>

            <div class="verifikasi-row">
                <div class="verifikasi-row-label">Nama</div>
                <div class="verifikasi-row-sep">:</div>
                <div class="verifikasi-row-value" style="color:#1e293b;">{{ $user->name }}</div>
            </div>
            <div class="verifikasi-row">
                <div class="verifikasi-row-label">NPM</div>
                <div class="verifikasi-row-sep">:</div>
                <div class="verifikasi-row-value" style="color:#1e293b;">{{ $user->id_pendaftar }}</div>
            </div>
            <div class="verifikasi-row">
                <div class="verifikasi-row-label">Program Studi</div>
                <div class="verifikasi-row-sep">:</div>
                <div class="verifikasi-row-value" style="color:#1e293b;">{{ $user->program_studi ?? '-' }}</div>
            </div>
            <div class="verifikasi-row">
                <div class="verifikasi-row-label">Fakultas</div>
                <div class="verifikasi-row-sep">:</div>
                <div class="verifikasi-row-value" style="color:#1e293b;">{{ $user->fakultas ?? '-' }}</div>
            </div>
            <div class="verifikasi-row">
                <div class="verifikasi-row-label">Nomor Sertifikat</div>
                <div class="verifikasi-row-sep">:</div>
                <div class="verifikasi-row-value" style="color:#1e293b;">{{ str_pad($user->nomor_sertifikat, 4, '0', STR_PAD_LEFT) }}/{{ $setting->kode_surat }}</div>
            </div>
            <div class="verifikasi-row">
                <div class="verifikasi-row-label">Status</div>
                <div class="verifikasi-row-sep">:</div>
                <div class="verifikasi-row-value {{ !$isAllComplete ? 'is-belum' : ($isPassed ? 'is-lulus' : 'is-tidak-lulus') }}">
                    {{ !$isAllComplete ? 'Belum Lengkap' : ($isPassed ? 'LULUS (' . number_format($finalScore, 1) . ')' : 'TIDAK LULUS (' . number_format($finalScore, 1) . ')') }}
                </div>
            </div>
            <div class="verifikasi-row">
                <div class="verifikasi-row-label">Ditandatangani</div>
                <div class="verifikasi-row-sep">:</div>
                <div class="verifikasi-row-value" style="color:#1e293b;">{{ $setting->nama_mengetahui }} ({{ $setting->jabatan_mengetahui }})</div>
            </div>
            <div class="verifikasi-row">
                <div class="verifikasi-row-label">Tanggal Terbit</div>
                <div class="verifikasi-row-sep">:</div>
                <div class="verifikasi-row-value" style="color:#1e293b;">{{ $user->sertifikat_issued_at?->translatedFormat('d F Y') ?? '-' }}</div>
            </div>
        </div>

        <button type="button" class="verifikasi-toggle-btn" onclick="toggleSertifikatDigital()">
            <span id="verifikasiToggleLabel">Tampilkan Sertifikat Digital</span>
        </button>
    </div>

    <div class="verifikasi-cert-wrapper" id="verifikasiCertWrapper">
        @php
            $logoDikti = $setting->logo_dikti ? asset('storage/' . $setting->logo_dikti) : null;
            $logoBelmawa = $setting->logo_belmawa ? asset('storage/' . $setting->logo_belmawa) : null;
            $logoPkkmb = $setting->logo_pkkmb ? asset('storage/' . $setting->logo_pkkmb) : asset('assets/img/logopkkmb.png');
            $logoKampus = $setting->logo_kampus ? asset('storage/' . $setting->logo_kampus) : asset('assets/img/logo_ibsi.png');
            $logoLima = $setting->logo_lima ? asset('storage/' . $setting->logo_lima) : null;
        @endphp
        @include('partials.sertifikat-card', [
            'canvasId' => 'sertifikatVerifikasiCanvas',
            'nomorUrut' => str_pad($user->nomor_sertifikat, 4, '0', STR_PAD_LEFT),
            'kodeSurat' => $setting->kode_surat,
            'namaMahasiswa' => $user->name,
            'npm' => $user->id_pendaftar,
            'prodi' => $user->program_studi ?? '-',
            'fakultas' => $user->fakultas ?? '-',
            'statusLulus' => $isPassed,
            'namaKegiatan' => $setting->nama_kegiatan,
            'lokasi' => $setting->lokasi,
            'tanggal' => $setting->tanggal_pelaksanaan,
            'namaMengetahui' => $setting->nama_mengetahui,
            'jabatanMengetahui' => $setting->jabatan_mengetahui,
            'nipMengetahui' => $setting->nip_mengetahui,
            'namaKetuaPanitia' => $setting->nama_ketua_panitia,
            'jabatanKetuaPanitia' => $setting->jabatan_ketua_panitia,
            'nupKetuaPanitia' => $setting->nup_ketua_panitia,
            'logoDikti' => $logoDikti,
            'logoBelmawa' => $logoBelmawa,
            'logoPkkmb' => $logoPkkmb,
            'logoKampus' => $logoKampus,
            'logoLima' => $logoLima,
            'verifikasiUrl' => null,
        ])
    </div>

    <p class="verifikasi-footer">Halaman ini menampilkan status keaslian sertifikat secara otomatis berdasarkan data sistem PKKMB Universitas Ibnu Sina.</p>

    <script>
        function toggleSertifikatDigital() {
            var wrapper = document.getElementById('verifikasiCertWrapper');
            var label = document.getElementById('verifikasiToggleLabel');
            var isOpen = wrapper.classList.toggle('is-open');
            label.textContent = isOpen ? 'Tutup Sertifikat Digital' : 'Tampilkan Sertifikat Digital';
            if (isOpen) {
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    </script>
</body>
</html>
