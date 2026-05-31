<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah UMKM</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        * { box-sizing:border-box; }
        body { margin:0; font-family:Arial, sans-serif; background:#f4f6f9; color:#111827; }
        .navbar { background:#0f766e; color:white; padding:18px 30px; display:flex; justify-content:space-between; align-items:center; }
        .navbar h2 { margin:0; }
        .navbar a { color:white; text-decoration:none; margin-left:15px; font-weight:bold; }
        .container { padding:30px; display:flex; justify-content:center; }
        .card { width:100%; max-width:820px; background:white; padding:24px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        label { display:block; font-weight:bold; margin-top:14px; }
        input, select, textarea { width:100%; box-sizing:border-box; padding:12px; border:1px solid #cbd5e1; border-radius:8px; margin-top:7px; }
        button, a.btn { display:inline-block; margin-top:18px; padding:11px 15px; border-radius:8px; border:0; text-decoration:none; font-weight:bold; cursor:pointer; }
        button { background:#0f766e; color:white; }
        a.btn { background:#64748b; color:white; }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:8px; }
        #map { height:320px; border-radius:10px; border:1px solid #cbd5e1; margin-top:8px; }
        .map-note { color:#475569; font-size:13px; margin-top:8px; }
        .coords { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .secondary { background:#64748b; color:white; }
        @media (max-width: 640px) {
            .navbar { display:block; padding:16px 20px; }
            .navbar a { display:inline-block; margin:10px 12px 0 0; }
            .container { padding:18px; }
            .coords { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
@include('partials.navbar', ['title' => 'Tambah Data UMKM'])
<div class="container">
    <div class="card">
        @if($errors->any()) <div class="error">{{ $errors->first() }}</div> @endif
        <form method="POST" action="/umkm/store">
            @csrf
            @if(auth()->user()->role === 'admin')
                <label>Akun UMKM</label>
                <select name="user_id" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                    @endforeach
                </select>
            @endif
            <label>Nama UMKM</label>
            <input type="text" name="nama_umkm" value="{{ old('nama_umkm') }}" required>
            <label>Alamat</label>
            <textarea name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
            <label>Kategori Usaha</label>
            <input type="text" name="kategori_usaha" value="{{ old('kategori_usaha') }}">
            <label>Pin Lokasi UMKM</label>
            <div id="map"></div>
            <div class="map-note">Klik lokasi UMKM di peta atau geser marker untuk menentukan titik alamat.</div>
            <div class="coords">
                <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" placeholder="Latitude" readonly>
                <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" placeholder="Longitude" readonly>
            </div>
            <button type="button" class="secondary" id="locateBtn">Gunakan Lokasi Saya</button>
            <button type="submit">Simpan</button>
            <a class="btn" href="/umkm">Kembali</a>
        </form>
    </div>
</div>
<script>
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const defaultLat = Number(latInput.value || -0.9000);
    const defaultLng = Number(lngInput.value || 119.8700);
    const map = L.map('map').setView([defaultLat, defaultLng], 13);
    let marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

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
    }

    map.on('click', function(event) {
        setMarker(event.latlng.lat, event.latlng.lng);
    });

    if (latInput.value && lngInput.value) {
        setMarker(latInput.value, lngInput.value);
    }

    document.getElementById('locateBtn').addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('Browser tidak mendukung geolocation.');
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            map.setView([lat, lng], 15);
            setMarker(lat, lng);
        }, function() {
            alert('Lokasi tidak bisa diambil. Silakan klik titik lokasi di peta.');
        });
    });
</script>
</body>
</html>
