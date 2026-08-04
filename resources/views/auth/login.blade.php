<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>PKKMB UIS - Portal Masuk Mahasiswa Baru</title>
    <meta content="Portal Resmi PKKMB Universitas Ibnu Sina" name="description">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo_ibsi.png') }}" rel="icon" type="image/png">
    <link href="{{ asset('assets/img/logo_ibsi.png') }}" rel="shortcut icon" type="image/png">
    <link href="{{ asset('assets/img/logo_ibsi.png') }}" rel="apple-touch-icon">

    <!-- Open Graph / Meta Tags for Link Sharing (WhatsApp, Facebook, Telegram, etc.) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="PKKMB UIS - Portal Masuk Mahasiswa Baru">
    <meta property="og:description" content="Selamat Datang CAMABA UIS 2026. Siapkan diri Anda menjadi bagian dari Civitas Akademika Universitas Ibnu Sina yang berprestasi dan berakhlak mulia.">
    <meta property="og:image" content="{{ asset('assets/img/og_share_thumbnail.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('assets/img/og_share_thumbnail.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="PKKMB UIS - Portal Masuk Mahasiswa Baru">
    <meta name="twitter:description" content="Selamat Datang CAMABA UIS 2026. Siapkan diri Anda menjadi bagian dari Civitas Akademika Universitas Ibnu Sina yang berprestasi dan berakhlak mulia.">
    <meta name="twitter:image" content="{{ asset('assets/img/og_share_thumbnail.png') }}">

    <!-- Google Fonts: Lora (Academic Serif) & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <style>
        :root {
            --uis-green: #046B26;
            --uis-green-dark: #024B1A;
            --uis-yellow: #FED802;
            --bg-light: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        html, body {
            height: 100vh !important;
            width: 100vw !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important; /* Prevents any scrolling */
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Subtle Background Radial Pattern */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 50%, rgba(4, 107, 38, 0.06) 0%, rgba(248, 250, 252, 1) 80%);
            z-index: 0;
        }

        /* Main Container Card (Fits 14-Inch Display Perfectly) */
        .login-card {
            position: relative;
            z-index: 10;
            width: 1040px;
            max-width: 95%;
            height: 560px; /* Fixed height for 14" laptop viewport */
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        /* Left Side Banner (Campus Visual + Academic Identity) */
        .login-visual {
            flex: 1.25;
            position: relative;
            background: url("{{ asset('assets/img/gedunguis.JPG') }}") center center no-repeat;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.2rem 2.4rem;
            color: #ffffff;
        }

        .login-visual::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(155deg, rgba(4, 107, 38, 0.94) 0%, rgba(2, 70, 26, 0.90) 100%);
            z-index: 1;
        }

        .visual-header {
            position: relative;
            z-index: 2;
        }

        .uis-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .uis-badge-pill img {
            width: 18px;
            height: 18px;
        }

        .visual-body {
            position: relative;
            z-index: 2;
            margin-bottom: auto;
            margin-top: 1.5rem;
        }

        .visual-title {
            font-family: 'Lora', serif;
            font-size: 2.1rem;
            font-weight: 700;
            line-height: 1.2;
            color: #ffffff;
            margin-bottom: 0;
        }

        .visual-accent-line {
            width: 55px;
            height: 4px;
            background: var(--uis-yellow);
            border-radius: 4px;
            margin: 12px 0 14px 0;
        }

        .visual-subtitle {
            font-size: 0.84rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
            font-weight: 400;
            max-width: 95%;
        }

        /* 3 Bottom Academic Cards */
        .visual-cards-row {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 10px;
            margin-top: 1rem;
        }

        .feature-box {
            flex: 1;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            padding: 14px 10px;
            text-align: center;
            backdrop-filter: blur(6px);
        }

        .feature-box i {
            font-size: 1.4rem;
            color: var(--uis-yellow);
            margin-bottom: 6px;
            display: block;
        }

        .feature-box h6 {
            font-size: 0.75rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .feature-box p {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            line-height: 1.35;
        }

        /* Right Side Form (Clean Academic Form) */
        .login-form-box {
            flex: 1;
            padding: 2.2rem 2.8rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 0.8rem;
        }

        .logo-img {
            width: 72px;
            height: auto;
        }

        .form-heading {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-heading h2 {
            font-family: 'Lora', serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--uis-green);
            margin-bottom: 4px;
        }

        .form-heading p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin: 0;
        }

        .form-group-custom {
            margin-bottom: 1.1rem;
        }

        .form-label-custom {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--uis-green);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper-custom {
            position: relative;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .input-custom {
            width: 100%;
            height: 44px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 14px 0 40px;
            font-size: 0.85rem;
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .input-custom:focus {
            outline: none;
            background: #ffffff;
            border-color: var(--uis-green);
            box-shadow: 0 0 0 3px rgba(4, 107, 38, 0.15);
        }

        .toggle-pass-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            font-size: 1.05rem;
        }

        .toggle-pass-btn:hover {
            color: var(--uis-green);
        }

        .forgot-pass-link {
            color: var(--uis-green);
            font-weight: 700;
            font-size: 0.78rem;
            text-decoration: none;
        }

        .forgot-pass-link:hover {
            text-decoration: underline;
        }

        .btn-login-forest {
            width: 100%;
            height: 46px;
            background: var(--uis-green);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login-forest:hover {
            background: var(--uis-green-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(4, 107, 38, 0.35);
        }

        .divider-line {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.2rem 0 0.8rem 0;
            color: #cbd5e1;
        }

        .divider-line::before,
        .divider-line::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider-line i {
            padding: 0 10px;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .login-footer-text {
            text-align: center;
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
        }

        @media (max-width: 900px) {
            html, body {
                overflow: auto !important;
            }
            .login-visual {
                display: none;
            }
            .login-card {
                max-width: 420px;
                height: auto;
                border-radius: 20px;
            }
            .login-form-box {
                padding: 2.2rem 1.8rem;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        {{-- Left Side: Academic Banner Visual --}}
        <div class="login-visual">
            <div class="visual-header">
                <div class="uis-badge-pill">
                    <img src="{{ asset('assets/img/logo_ibsi.png') }}" alt="UIS Badge">
                    <span>PKKMB UIS {{ date('Y') }}</span>
                </div>
            </div>

            <div class="visual-body">
                <h1 class="visual-title">Selamat Datang<br>CAMABA UIS {{ date('Y') }}</h1>
                <div class="visual-accent-line"></div>
                <p class="visual-subtitle">Siapkan diri Anda menjadi bagian dari Civitas Akademika Universitas Ibnu Sina yang berprestasi dan berkarakter.</p>
            </div>

            <div class="visual-cards-row">
                <div class="feature-box">
                    <i class="bi bi-mortarboard-fill"></i>
                    <h6>Generasi Rabbani</h6>
                    <p>Unggul, berintegritas, profesional & berakhlak mulia.</p>
                </div>
                <div class="feature-box">
                    <i class="bi bi-people-fill"></i>
                    <h6>Bersama Membangun Masa Depan</h6>
                    <p>Berintelektual untuk inovasi dan kemajuan umat.</p>
                </div>
                <div class="feature-box">
                    <i class="bi bi-shield-check"></i>
                    <h6>Keamanan Data</h6>
                    <p>Keamanan data Anda terjamin dengan sistem autentikasi resmi UIS.</p>
                </div>
            </div>
        </div>

        {{-- Right Side: Clean Form --}}
        <div class="login-form-box">
            <div class="logo-container">
                <img src="{{ asset('assets/img/logo_ibsi.png') }}" alt="Logo UIS" class="logo-img">
            </div>

            <div class="form-heading">
                <h2>Portal Masuk PKKMB</h2>
                <p>Silakan masukkan kredensial akun Anda</p>
            </div>

            @error('login')
                <div class="alert border-0 rounded-3 p-3 mb-3 d-flex align-items-center bg-danger bg-opacity-10 text-danger" style="border-left: 4px solid #ef4444 !important;">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div style="font-size: 0.8rem; font-weight: 600;">
                        {{ $message }}
                    </div>
                </div>
            @enderror

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group-custom">
                    <label class="form-label-custom">ID Pendaftar / Email / No. WhatsApp (628...)</label>
                    <div class="input-wrapper-custom">
                        <i class="bi bi-person-fill input-icon-left"></i>
                        <input type="text" name="login" class="input-custom" value="{{ old('login') }}" placeholder="Contoh: 628123456789 atau 20240101" required autofocus>
                    </div>
                </div>

                <div class="form-group-custom mb-4">
                    <label class="form-label-custom">Password</label>
                    <div class="input-wrapper-custom">
                        <i class="bi bi-lock-fill input-icon-left"></i>
                        <input type="password" name="password" id="passInput" class="input-custom" style="padding-right: 40px;" placeholder="Masukkan password" required>
                        <button type="button" id="togglePassBtn" class="toggle-pass-btn" title="Tampilkan/Sembunyikan Password">
                            <i class="bi bi-eye-slash" id="togglePassIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login-forest">
                    <span>MASUK SEKARANG</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="divider-line">
                <i class="bi bi-shield-check"></i>
            </div>

            <div class="login-footer-text">
                &copy; {{ date('Y') }} Panitia PKKMB Universitas Ibnu Sina Batam
            </div>
        </div>
    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        document.getElementById('togglePassBtn').addEventListener('click', function () {
            const passInput = document.getElementById('passInput');
            const icon = document.getElementById('togglePassIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    </script>
</body>

</html>