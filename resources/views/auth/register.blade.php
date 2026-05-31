<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - UMKM Digital Palu</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; color:#111827; }
        .wrap { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:30px; }
        .box { width:620px; background:white; padding:28px; border-radius:12px; box-shadow:0 8px 22px rgba(0,0,0,.12); }
        h1 { margin:0 0 6px; color:#0f766e; text-align:center; }
        p { text-align:center; color:#64748b; margin:0 0 22px; }
        label { display:block; font-weight:bold; margin-top:13px; }
        input, select, textarea { width:100%; box-sizing:border-box; padding:11px; border:1px solid #cbd5e1; border-radius:8px; margin-top:6px; }
        button, a { display:inline-block; padding:11px 15px; border-radius:8px; border:0; text-decoration:none; font-weight:bold; margin-top:18px; }
        button { background:#0f766e; color:white; cursor:pointer; }
        a { background:#e2e8f0; color:#0f172a; }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:8px; margin-bottom:12px; }
        .umkm-fields { display:none; }
        #map { height:300px; border-radius:10px; margin-top:8px; border:1px solid #cbd5e1; }
        .map-note { margin-top:8px; font-size:13px; color:#475569; text-align:left; }
        .coords { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .ghost { background:#64748b; color:white; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="box">
        <h1>Registrasi Akun</h1>
        <p>Daftar sebagai pembeli atau pelaku UMKM.</p>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required>

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>

            <label>Role</label>
            <select name="role" id="role" required>
                <option value="pembeli" {{ old('role') === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                <option value="umkm" {{ old('role') === 'umkm' ? 'selected' : '' }}>UMKM</option>
            </select>

            <div class="umkm-fields" id="umkmFields">
                <label>Nama UMKM</label>
                <input type="text" name="nama_umkm" value="{{ old('nama_umkm') }}">

                <label>Alamat UMKM</label>
                <textarea name="alamat" rows="3">{{ old('alamat') }}</textarea>

                <label>Pin Lokasi UMKM</label>
                <div id="map"></div>
                <div class="map-note" id="mapNote">Klik lokasi UMKM di peta. Marker akan menjadi titik alamat pada dashboard dan peta persebaran UMKM.</div>
                <div class="coords">
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" placeholder="Latitude" readonly>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" placeholder="Longitude" readonly>
                </div>
                <button type="button" class="ghost" id="locateBtn">Gunakan Lokasi Saya</button>

                <label>Kategori Usaha</label>
                <input type="text" name="kategori_usaha" value="{{ old('kategori_usaha') }}" placeholder="Kuliner, Fashion, Jasa">
            </div>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>

            <button type="submit">Daftar</button>
            <a href="/login">Sudah punya akun</a>
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
        fields.style.display = role.value === 'umkm' ? 'block' : 'none';
        if (role.value === 'umkm') {
            initMap();
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
