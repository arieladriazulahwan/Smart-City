<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - UMKM Digital Palu</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        * { box-sizing: border-box; }
        
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f766e 0%, #055b54 100%);
            color: #111827;
            padding: 30px 20px;
        }

        .register-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #0f766e 0%, #055b54 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .register-header h1 {
            margin: 0 0 8px;
            font-size: 24px;
        }

        .register-header p {
            margin: 0;
            opacity: 0.95;
            font-size: 14px;
        }

        .register-form {
            padding: 40px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-top: 18px;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 0;
            font-size: 14px;
            transition: border-color .2s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button {
            flex: 1;
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

        .register-form a {
            display: inline-block;
            flex: 1;
            padding: 13px;
            text-align: center;
            background: #f0fdf4;
            color: #0f766e;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid #dcfce7;
            transition: all .2s ease;
        }

        .register-form a:hover {
            background: #dcfce7;
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

        .umkm-fields {
            display: none;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .umkm-fields.active {
            display: block;
        }

        #map {
            height: 300px;
            border-radius: 10px;
            margin-top: 8px;
            border: 1px solid #ddd;
        }

        .map-note {
            margin-top: 8px;
            font-size: 13px;
            color: #666;
            text-align: left;
        }

        .coords {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        #locateBtn {
            margin-top: 12px;
            background: #64748b;
            font-size: 14px;
            padding: 12px;
        }

        #locateBtn:hover {
            background: #475569;
        }

        @media (max-width: 768px) {
            .register-header {
                padding: 25px;
            }

            .register-form {
                padding: 25px;
            }

            .button-group {
                flex-direction: column;
            }

            .button-group a {
                flex: unset;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-header">
        <h1>🚀 Daftar Akun</h1>
        <p>Bergabunglah dengan UMKM Digital Palu</p>
    </div>

    <div class="register-form">
        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/register">
            @csrf
            
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>

            <label for="role">Saya daftar sebagai</label>
            <select name="role" id="role" required>
                <option value="pembeli" {{ old('role') === 'pembeli' ? 'selected' : '' }}>👤 Pembeli</option>
                <option value="umkm" {{ old('role') === 'umkm' ? 'selected' : '' }}>🏪 Pemilik UMKM</option>
            </select>

            <div class="umkm-fields" id="umkmFields">
                <label for="nama_umkm">Nama UMKM</label>
                <input type="text" id="nama_umkm" name="nama_umkm" value="{{ old('nama_umkm') }}" placeholder="Nama toko/usaha Anda">

                <label for="alamat">Alamat UMKM</label>
                <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap usaha Anda">{{ old('alamat') }}</textarea>

                <label for="kategori_usaha">Kategori Usaha</label>
                <input type="text" id="kategori_usaha" name="kategori_usaha" value="{{ old('kategori_usaha') }}" placeholder="Contoh: Kuliner, Fashion, Jasa">

                <label>📍 Pin Lokasi UMKM</label>
                <div id="map"></div>
                <div class="map-note">Klik pada peta untuk menentukan lokasi UMKM Anda</div>
                <div class="coords">
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" placeholder="Latitude" readonly>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" placeholder="Longitude" readonly>
                </div>
                <button type="button" id="locateBtn">📍 Gunakan Lokasi Saya</button>
            </div>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>

            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password Anda" required>

            <div class="button-group">
                <button type="submit">Daftar Sekarang</button>
                <a href="/login">Sudah punya akun?</a>
            </div>
        </form>
    </div>
</div>

<script>
    const role = document.getElementById('role');
    const fields = document.getElementById('umkmFields');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const locateBtn = document.getElementById('locateBtn');
    const defaultLat = Number(latInput.value || -0.9000);
    const defaultLng = Number(lngInput.value || 119.8700);
    let map;
    let marker;

    function setMarker(lat, lng) {
        latInput.value = Number(lat).toFixed(7);
        lngInput.value = Number(lng).toFixed(7);

        if (!marker) {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function(event) {
                const position = event.target.getLatLng();
                setMarker(position.lat, position.lng);
            });
        } else {
            marker.setLatLng([lat, lng]);
        }

        map.setView([lat, lng], 15);
    }

    function initMap() {
        if (map) {
            setTimeout(() => map.invalidateSize(), 100);
            return;
        }

        map = L.map('map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        map.on('click', function(event) {
            setMarker(event.latlng.lat, event.latlng.lng);
        });

        if (latInput.value && lngInput.value) {
            setMarker(latInput.value, lngInput.value);
        }
    }

    function syncFields() {
        const isUmkm = role.value === 'umkm';
        fields.classList.toggle('active', isUmkm);
        
        if (isUmkm) {
            setTimeout(initMap, 100);
        }
    }

    locateBtn.addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('Browser tidak mendukung geolocation.');
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            setMarker(position.coords.latitude, position.coords.longitude);
        }, function() {
            alert('Lokasi tidak bisa diambil. Silakan klik titik lokasi di peta.');
        });
    });

    role.addEventListener('change', syncFields);
    syncFields();
</script>

</body>
</html>
    syncFields();
</script>
</body>
</html>
