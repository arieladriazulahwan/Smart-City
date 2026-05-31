<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - UMKM Digital Palu</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f766e;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            width: 380px;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            color: #0f766e;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #0f766e;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #0d5f59;
        }

        a {
            color: #0f766e;
            font-weight: bold;
            text-decoration: none;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .akun {
            margin-top: 20px;
            font-size: 13px;
            background: #f1f5f9;
            padding: 12px;
            border-radius: 8px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>UMKM Digital Palu</h2>
    <p>Login Multi Role</p>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p style="margin-top:18px;">Belum punya akun? <a href="/register">Daftar</a></p>

    <div class="akun">
        <strong>Akun Demo:</strong><br>
        Admin: admin@umkm.com<br>
        Pemerintah: pemerintah@umkm.com<br>
        UMKM: kopi@umkm.com<br>
        Pembeli: pembeli@umkm.com<br>
        Password: password
    </div>
</div>

</body>
</html>
