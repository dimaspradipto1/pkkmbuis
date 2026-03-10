<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>PKKMB UIS - Login</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/logo_ibsi.png" rel="icon">
    <link href="assets/img/logo_ibsi.png" rel="apple-touch-icon">

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

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        :root {
            --uis-green: #00A551;
            --uis-yellow: #FFF742;
            --matrix-dark: #000000;
            --matrix-glow: rgba(0, 165, 81, 0.5);
            --glass-bg: rgba(0, 0, 0, 0.85);
            --glass-border: rgba(0, 165, 81, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--matrix-dark) !important;
            color: #fff;
            overflow: hidden;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #canvas-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        #matrixCanvas {
            display: block;
            opacity: 0.6;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.4));
            pointer-events: none;
            z-index: 0;
        }

        .card {
            width: 950px;
            height: 550px;
            background: var(--glass-bg) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border) !important;
            border-radius: 20px !important;
            box-shadow: 0 0 40px rgba(0, 165, 81, 0.3) !important;
            padding: 0;
            overflow: hidden;
            animation: fadeIn 1.2s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            z-index: 10;
        }

        .card-inner {
            display: flex;
            height: 100%;
        }

        .card-image {
            flex: 1.2;
            background: url("{{ asset('assets/img/gedunguis.JPG') }}") center center no-repeat;
            background-size: cover;
            position: relative;
            display: none;
        }

        .card-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.4), transparent);
        }

        .sidebar-text {
            position: absolute;
            bottom: 30px;
            left: 30px;
            z-index: 2;
        }

        .sidebar-text span {
            color: var(--uis-yellow);
            font-weight: 700;
            border-bottom: 2px solid var(--uis-yellow);
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .sidebar-text h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-top: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .card-form-side {
            flex: 1;
            padding: 2rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
        }

        .uis-logo {
            width: 65px;
            margin: 0 auto 0.5rem;
            display: block;
        }

        .form-label {
            color: var(--uis-green) !important;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: none !important;
            border-bottom: 1px solid rgba(0, 165, 81, 0.5) !important;
            color: #fff !important;
            border-radius: 0 !important;
            padding: 0.5rem 0 !important;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--uis-yellow) !important;
            box-shadow: none !important;
            background-color: transparent !important;
        }

        .btn-primary {
            background: var(--uis-green) !important;
            border: none !important;
            color: #fff !important;
            font-weight: 700 !important;
            padding: 0.7rem !important;
            border-radius: 8px !important;
            margin-top: 1rem;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #008f45 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 165, 81, 0.4);
        }

        .forgot-password {
            color: var(--uis-yellow);
            font-size: 0.8rem;
            text-decoration: none;
            display: block;
            text-align: right;
            margin-top: 10px;
        }

        .footer {
            margin-top: 1.5rem;
            font-size: 0.7rem;
            text-align: center;
            color: #888;
        }

        .footer img {
            height: 12px;
            vertical-align: middle;
            margin-left: 5px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (min-width: 768px) {
            .card-image {
                display: block;
            }
        }

        @media (max-width: 767px) {
            .card {
                width: 90%;
                height: auto;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #000;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--uis-green);
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div id="canvas-container">
        <canvas id="matrixCanvas"></canvas>
    </div>
    <div class="background-overlay"></div>

    <div class="card">
        <div class="card-inner">
            <div class="card-image">
                <div class="sidebar-text">
                    <span>PKKMB {{ date('Y') }}</span>
                    <h2>Universitas Ibnu Sina</h2>
                </div>
            </div>
            <div class="card-form-side">
                <img src="{{ asset('assets/img/logo_ibsi.png') }}" alt="UIS Logo" class="uis-logo">

                <div class="text-center mb-3">
                    <h5 style="color: var(--uis-yellow); font-weight: 700; margin-bottom: 5px;">MASUK AKUN PKKMB</h5>
                    <p style="font-size: 0.75rem; color: #ccc; margin-bottom: 0;">Silakan masuk menggunakan nomor
                        registrasi pendaftaran Anda.</p>
                </div>

                <form class="row g-2 needs-validation" novalidate>
                    <div class="col-12">
                        <label class="form-label">Nomor Registrasi</label>
                        <input type="text" name="nomor_registrasi" class="form-control" placeholder="Contoh: 2024001"
                            required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" class="form-control" id="yourPassword"
                                placeholder="Masukkan Password" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="showPassword">
                            <label class="form-check-label" for="showPassword"
                                style="font-size:0.75rem; color:#ccc;">Tampilkan Password</label>
                        </div>
                    </div>

                    <a href="#" class="forgot-password" style="margin-top: 5px;">Lupa kata sandi?</a>

                    <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">Masuk Sekarang</button>
                    </div>
                </form>

                <div class="footer">
                    <p>Copyright © {{ date('Y') }} Panitia PKKMB UIS</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        // Matrix Effect
        const canvas = document.getElementById("matrixCanvas");
        const ctx = canvas.getContext("2d");

        const characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        const charArray = characters.split("");
        let fontSize = 16;
        let columns = 0;
        let drops = [];

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            columns = Math.floor(canvas.width / fontSize);
            drops = Array(columns).fill(1).map(() => Math.random() * -100);
        }

        window.addEventListener("resize", resize);
        resize();

        function draw() {
            ctx.fillStyle = "rgba(0, 0, 0, 0.05)";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.font = `bold ${fontSize}px monospace`;

            for (let i = 0; i < drops.length; i++) {
                const text = charArray[Math.floor(Math.random() * charArray.length)];
                ctx.fillStyle = Math.random() > 0.9 ? "#FFF742" : "#00A551";
                ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) drops[i] = 0;
                drops[i]++;
            }
        }
        setInterval(draw, 33);

        // Show password
        document.getElementById('showPassword').addEventListener('change', function() {
            document.getElementById('yourPassword').type = this.checked ? 'text' : 'password';
        });
    </script>
</body>

</html>
