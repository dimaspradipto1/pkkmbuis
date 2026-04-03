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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        :root {
            --uis-green: #00A551;
            --uis-green-dark: #087C39;
            --uis-yellow: #FFF742;
            --uis-glow: rgba(0, 165, 81, 0.4);
            --void-navy: #020617;
            --glass-core: rgba(15, 23, 42, 0.9);
            --glass-border: rgba(255, 255, 255, 0.08);
            --synergy-gradient: linear-gradient(45deg, #087C39, #FFF742);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--void-navy) !important;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            overflow-y: auto;
            color: #f8fafc;
        }

        #synergy-canvas {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 50% 50%, #064e3b 0%, #020617 100%);
        }

        .synergy-card {
            width: 1050px;
            max-width: 100%;
            min-height: 680px;
            height: auto;
            display: flex;
            background: var(--glass-core);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border-radius: 40px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 40px 100px -24px rgba(0, 0, 0, 0.8), 0 0 40px rgba(0, 165, 81, 0.1);
            overflow: hidden;
            animation: cardSync 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            position: relative;
            margin: auto;
        }

        @keyframes cardSync {
            from {
                opacity: 0;
                transform: scale(0.97) translateY(20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .visual-synergy {
            flex: 1.1;
            background: url("{{ asset('assets/img/gedunguis.JPG') }}") center center no-repeat;
            background-size: cover;
            position: relative;
            display: none;
        }

        .visual-synergy::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(8, 124, 57, 0.6), rgba(2, 6, 23, 0.4));
        }

        .synergy-overlay {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            z-index: 5;
        }

        .synergy-overlay h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .synergy-overlay .tagline {
            display: inline-block;
            background: var(--uis-yellow);
            color: #000;
            padding: 4px 12px;
            font-weight: 800;
            font-size: 0.75rem;
            border-radius: 4px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .form-synergy {
            flex: 1;
            padding: 3rem 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .synergy-logo-box {
            margin-bottom: 2.5rem;
            display: flex;
            justify-content: center;
        }

        .synergy-logo {
            width: 75px;
            filter: drop-shadow(0 0 10px var(--uis-glow));
            transition: transform 0.3s;
        }

        .synergy-logo:hover {
            transform: scale(1.05);
        }

        .title-synergy {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle-box {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-bottom: 3rem;
            opacity: 0.8;
        }

        .accent-line {
            width: 3px;
            height: 18px;
            background: var(--uis-yellow);
            border-radius: 10px;
        }

        .subtitle-text {
            color: #e2e8f0;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .synergy-group {
            margin-bottom: 1.5rem;
        }

        .synergy-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--uis-yellow);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
            padding-left: 4px;
        }

        .synergy-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.9rem 1.2rem;
            color: white;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .synergy-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.07);
            border-color: var(--uis-green);
            box-shadow: 0 0 20px rgba(0, 165, 81, 0.2);
            transform: translateY(-1px);
        }

        .btn-synergy {
            background: var(--synergy-gradient);
            color: #000;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1rem;
            text-transform: uppercase;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s;
            cursor: pointer;
            box-shadow: 0 10px 20px -10px rgba(0, 165, 81, 0.5);
        }

        .btn-synergy:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -10px rgba(0, 165, 81, 0.7);
        }

        .action-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.25rem;
            font-size: 0.8rem;
        }

        .custom-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: #94a3b8;
        }

        .custom-check input {
            accent-color: var(--uis-green);
        }

        .forgot-pass {
            color: var(--uis-yellow);
            text-decoration: none;
            font-weight: 700;
        }

        .footer-synergy {
            margin-top: 3.5rem;
            text-align: center;
            font-size: 0.7rem;
            color: #475569;
            font-weight: 700;
            letter-spacing: 1px;
        }

        @media (min-width: 992px) {
            .visual-synergy {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .synergy-card {
                border-radius: 0;
                min-height: 100vh;
                width: 100%;
                max-width: 100%;
                height: auto;
            }

            .form-synergy {
                padding: 3rem 2rem;
            }
        }
    </style>
</head>

<body>
    <canvas id="synergy-canvas"></canvas>

    <div class="synergy-card">
        <div class="visual-synergy">
            <div class="synergy-overlay">
                <span class="tagline">Success Starts Here</span>
                <h1>PORTAL<br>PKKMB UIS</h1>
                <p class="opacity-75 uppercase tracking-widest small">Universitas Ibnu Sina {{ date('Y') }}</p>
            </div>
        </div>

        <div class="form-synergy">
            <div class="synergy-logo-box">
                <img src="{{ asset('assets/img/logo_ibsi.png') }}" alt="UIS" class="synergy-logo">
            </div>

            <h2 class="title-synergy">Halo, Selamat Datang!</h2>
            <div class="subtitle-box">
                <div class="accent-line"></div>
                <span class="subtitle-text">Silahkan masuk ke akun anda</span>
                <div class="accent-line"></div>
            </div>

            @error('login')
            <div class="alert border-0 small rounded-4 p-4 mb-5 d-flex align-items-center" 
                 style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3) !important; border-left: 5px solid #ef4444 !important;">
                <div class="flex-shrink-0 me-3">
                    <div class="d-flex align-items-center justify-content-center bg-danger rounded-circle" style="width: 32px; height: 32px;">
                        <i class="bi bi-shield-fill-x text-white"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-white fw-bold mb-1" style="font-size: 0.85rem">Akses Ditolak</h6>
                    <p class="text-white opacity-90 mb-0" style="font-size: 0.8rem">{{ $message }}</p>
                </div>
            </div>
            @enderror

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="synergy-group">
                    <label class="synergy-label">Registration ID / Email</label>
                    <input type="text" name="login" class="synergy-input" value="{{ old('login') }}"
                        placeholder="Contoh: 20240101" required>
                </div>

                <div class="synergy-group">
                    <label class="synergy-label">Password</label>
                    <input type="password" name="password" id="passInput" class="synergy-input" placeholder="••••••••"
                        required>
                </div>

                <div class="action-flex">
                    <label class="custom-check">
                        <input type="checkbox" id="showPass">
                        <span>Lihat Password</span>
                    </label>
                    <a href="#" class="forgot-pass">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-synergy">Masuk Sekarang</button>
            </form>

            <div class="footer-synergy">
                &copy; {{ date('Y') }} Panitia PKKMB Universitas Ibnu Sina
            </div>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('synergy-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        const particleCount = 60;

        class Particle {
            constructor() {
                this.init();
            }
            init() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.vx = (Math.random() - 0.5) * 0.4;
                this.vy = (Math.random() - 0.5) * 0.4;
                this.radius = Math.random() * 2 + 1;
            }
            update() {
                this.x += this.vx;
                this.y += this.vy;
                if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
            }
        }

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            particles = [];
            for (let i = 0; i < particleCount; i++) particles.push(new Particle());
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.update();
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = '#00A551';
                ctx.fill();
                particles.forEach(p2 => {
                    const dx = p.x - p2.x;
                    const dy = p.y - p2.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 150) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(255, 247, 66, ${0.1 * (1 - dist / 150)})`;
                        ctx.lineWidth = 0.5;
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.stroke();
                    }
                });
            });
            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', resize);
        resize();
        animate();

        document.getElementById('showPass').addEventListener('change', function() {
            document.getElementById('passInput').type = this.checked ? 'text' : 'password';
        });
    </script>
</body>

</html>