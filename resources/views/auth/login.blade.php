<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - UMKM Digital Palu</title>

    <style>
        * { box-sizing: border-box; }
        
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f766e 0%, #055b54 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 900px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            background: white;
        }

        .login-hero {
            background: linear-gradient(135deg, #0f766e 0%, #055b54 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 20px;
        }

        .login-hero h1 {
            margin: 0;
            font-size: 28px;
            line-height: 1.3;
        }

        .login-hero p {
            margin: 0;
            opacity: 0.95;
            line-height: 1.6;
        }

        .hero-features {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .hero-feature {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .hero-feature::before {
            content: '✓';
            color: #10b981;
            font-weight: bold;
            font-size: 18px;
        }

        .login-form {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h2 {
            text-align: center;
            color: #0f766e;
            margin: 0 0 8px;
            font-size: 24px;
        }

        .login-form > p {
            text-align: center;
            color: #999;
            margin: 0 0 25px;
            font-size: 14px;
        }

        label {
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 18px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color .2s ease;
        }

        input:focus {
            outline: none;
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        button {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #0f766e 0%, #055b54 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(15, 118, 110, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        .links {
            margin-top: 18px;
            text-align: center;
            font-size: 13px;
        }

        a {
            color: #0f766e;
            font-weight: 600;
            text-decoration: none;
            margin: 0 4px;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            border-left: 4px solid #dc2626;
            font-size: 14px;
        }

        .demo-accounts {
            margin-top: 20px;
            padding: 16px;
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 8px;
            font-size: 12px;
        }

        .demo-accounts h4 {
            margin: 0 0 8px;
            color: #065f46;
            font-size: 12px;
        }

        .demo-account {
            margin-bottom: 6px;
            color: #333;
            padding: 4px 0;
        }

        .demo-account strong {
            color: #0f766e;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            
            .login-hero {
                padding: 30px;
                min-height: auto;
            }
            
            .login-form {
                padding: 30px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-hero">
        <div>
            <h1>🚀 UMKM Digital Palu</h1>
            <p>Platform marketplace yang menghubungkan pembeli dengan UMKM lokal Kota Palu untuk mendukung ekonomi digital.</p>
        </div>
        
        <div class="hero-features">
            <div class="hero-feature">Belanja dari UMKM lokal terpercaya</div>
            <div class="hero-feature">Sistem verifikasi keamanan berlapis</div>
            <div class="hero-feature">Gratis ongkir untuk semua transaksi</div>
            <div class="hero-feature">Dukungan pelanggan 24/7</div>
        </div>
    </div>

    <div class="login-form">
        <h2>Login</h2>
        <p>Masuk ke akun Anda untuk mulai berbelanja</p>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="contoh@email.com" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>

            <button type="submit">Masuk ke Akun →</button>
        </form>

        <div class="links">
            Belum punya akun? <a href="/register">Daftar Sekarang</a><br>
            Lupa password? <a href="/forgot-password">Reset Password</a>
        </div>

        <div class="demo-accounts">
            <h4>📌 Akun Demo (Gunakan untuk testing):</h4>
            <div class="demo-account"><strong>Admin:</strong> admin@umkm.com / password</div>
            <div class="demo-account"><strong>Pemerintah:</strong> pemerintah@umkm.com / password</div>
            <div class="demo-account"><strong>UMKM:</strong> kopi@umkm.com / password</div>
            <div class="demo-account"><strong>Pembeli:</strong> pembeli@umkm.com / password</div>
        </div>
    </div>
</div>

</body>
</html>
        Pembeli: pembeli@umkm.com<br>
        Password: password
    </div>
</div>

</body>
</html>
