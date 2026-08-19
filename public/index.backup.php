<?php
http_response_code(503);
header('Content-Type: text/html; charset=UTF-8');
header('Retry-After: 3600');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maintenance - PKKMB UIS Batam</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #0f172a, #1e3a8a, #2563eb);
      color: white;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .card {
      max-width: 700px;
      width: 100%;
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
      border-radius: 24px;
      padding: 40px 30px;
      text-align: center;
      box-shadow: 0 20px 50px rgba(0,0,0,0.25);
    }

    .icon {
      font-size: 64px;
      margin-bottom: 20px;
    }

    h1 {
      font-size: 36px;
      margin-bottom: 15px;
    }

    p {
      font-size: 18px;
      line-height: 1.6;
      opacity: 0.95;
      margin-bottom: 14px;
    }

    .badge {
      display: inline-block;
      background: #facc15;
      color: #111827;
      font-weight: bold;
      padding: 10px 18px;
      border-radius: 999px;
      margin: 20px 0;
      font-size: 14px;
    }

    .footer {
      margin-top: 25px;
      font-size: 14px;
      opacity: 0.85;
    }

    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 22px;
      background: white;
      color: #1e3a8a;
      text-decoration: none;
      border-radius: 10px;
      font-weight: bold;
      transition: 0.2s;
    }

    .btn:hover {
      transform: translateY(-2px);
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 28px;
      }

      p {
        font-size: 16px;
      }

      .card {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon">🛠️</div>
    <div class="badge">Website Sedang Maintenance</div>
    <h1>PKKMB UIS Batam</h1>
    <p>
      Mohon maaf, website sedang dalam proses pemeliharaan dan peningkatan sistem.
    </p>
    <p>
      Kami sedang berupaya agar layanan dapat kembali normal secepatnya.
    </p>
    <p>
      Silakan cek kembali beberapa saat lagi.
    </p>

    <a href="/" class="btn">Coba Lagi</a>

    <div class="footer">
      Terima kasih atas pengertiannya.
    </div>
  </div>
</body>
</html>