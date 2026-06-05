<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk UMKM</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #111827;
            background: #eef2f7;
        }

        .container {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 40px;
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 18px;
        }

        .page-head h1 {
            margin: 0 0 8px;
            font-size: 28px;
            line-height: 1.2;
        }

        .page-head p {
            margin: 0;
            color: #64748b;
            line-height: 1.5;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 330px;
            gap: 18px;
            align-items: start;
        }

        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .08);
        }

        .form-card {
            padding: 24px;
        }

        .side-card {
            padding: 20px;
            position: sticky;
            top: 18px;
        }

        .section-title {
            margin: 0 0 16px;
            color: #334155;
            font-size: 17px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field {
            display: grid;
            gap: 7px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            color: #334155;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .35px;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px;
            color: #111827;
            background: white;
            font: inherit;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, .12);
        }

        textarea {
            min-height: 126px;
            resize: vertical;
        }

        .hint {
            color: #64748b;
            font-size: 12px;
            line-height: 1.45;
        }

        .error,
        .field-error {
            color: #991b1b;
            background: #fee2e2;
            border-radius: 8px;
        }

        .error {
            padding: 12px;
            margin-bottom: 16px;
        }

        .field-error {
            width: fit-content;
            padding: 5px 8px;
            font-size: 12px;
            font-weight: 700;
        }

        .upload-box {
            border: 1px dashed #94a3b8;
            border-radius: 8px;
            padding: 14px;
            display: grid;
            grid-template-columns: 112px minmax(0, 1fr);
            gap: 14px;
            align-items: center;
            background: #f8fafc;
        }

        .preview {
            width: 112px;
            aspect-ratio: 1;
            border-radius: 8px;
            background: linear-gradient(135deg, #e2e8f0, #f8fafc);
            border: 1px solid #e2e8f0;
            display: grid;
            place-items: center;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            overflow: hidden;
            text-align: center;
            padding: 8px;
        }

        .preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 22px;
        }

        button,
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 11px 15px;
            border: 0;
            border-radius: 8px;
            font: inherit;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            color: white;
            background: #0f766e;
        }

        .btn {
            color: #334155;
            background: #e2e8f0;
        }

        .check-list {
            display: grid;
            gap: 10px;
            margin-top: 14px;
        }

        .check-item {
            display: grid;
            grid-template-columns: 28px minmax(0, 1fr);
            gap: 10px;
            align-items: start;
            color: #475569;
            font-size: 14px;
            line-height: 1.45;
        }

        .check-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            color: #065f46;
            background: #d1fae5;
            font-weight: 900;
        }

        .summary {
            margin-top: 18px;
            padding: 14px;
            border-radius: 8px;
            background: #f8fafc;
            color: #475569;
            line-height: 1.5;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .layout,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .side-card {
                position: static;
            }
        }

        @media (max-width: 560px) {
            .container {
                width: min(100% - 20px, 1120px);
                padding-top: 18px;
            }

            .page-head {
                flex-direction: column;
            }

            .form-card,
            .side-card {
                padding: 18px;
            }

            .upload-box {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@include('partials.navbar', ['title' => 'Tambah Produk UMKM'])

<main class="container">
    <div class="page-head">
        <div>
            <h1>Tambah Produk</h1>
            <p>Lengkapi informasi produk agar katalog terlihat rapi dan stok lebih mudah dipantau.</p>
        </div>
        <a class="btn" href="/umkm/produk">Kembali ke Produk</a>
    </div>

    <div class="layout">
        <section class="card form-card">
            <h2 class="section-title">Informasi Produk</h2>

            @if($errors->any())
                <div class="error">Periksa kembali data yang ditandai pada form.</div>
            @endif

            <form method="POST" action="/umkm/produk/store" enctype="multipart/form-data">
                @csrf

                <div class="form-grid">
                    <div class="field">
                        <label for="umkm_id">UMKM</label>
                        <select id="umkm_id" name="umkm_id" required>
                            <option value="">Pilih UMKM</option>
                            @foreach($umkms as $umkm)
                                <option value="{{ $umkm->id }}" @selected(old('umkm_id') == $umkm->id)>{{ $umkm->nama_umkm }}</option>
                            @endforeach
                        </select>
                        @error('umkm_id') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="category_id">Kategori</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field full">
                        <label for="nama_produk">Nama Produk</label>
                        <input id="nama_produk" type="text" name="nama_produk" value="{{ old('nama_produk') }}" placeholder="Contoh: Sambal Roa Palu 250gr" required>
                        <span class="hint">Gunakan nama yang jelas, termasuk varian atau ukuran produk.</span>
                        @error('nama_produk') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="harga">Harga</label>
                        <input id="harga" type="number" name="harga" value="{{ old('harga') }}" min="0" step="100" placeholder="25000" required>
                        <span class="hint">Masukkan angka tanpa titik atau Rp.</span>
                        @error('harga') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="stok_manual">Stok Manual</label>
                        <input id="stok_manual" type="number" name="stok_manual" value="{{ old('stok_manual') }}" min="0" step="1" placeholder="20" required>
                        <span class="hint">Stok 10 atau kurang akan ditandai menipis.</span>
                        @error('stok_manual') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field full">
                        <label for="gambar">Foto Produk</label>
                        <div class="upload-box">
                            <div class="preview" id="imagePreview">Preview Foto</div>
                            <div>
                                <input id="gambar" type="file" name="gambar" accept="image/png,image/jpeg,image/webp">
                                <span class="hint">Format JPG, PNG, atau WebP. Maksimal 2 MB.</span>
                            </div>
                        </div>
                        @error('gambar') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field full">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" placeholder="Tulis bahan, ukuran, manfaat, cara simpan, atau keunggulan produk.">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="actions">
                    <button type="submit">Simpan Produk</button>
                    <a class="btn" href="/umkm/produk">Batal</a>
                </div>
            </form>
        </section>

        <aside class="card side-card">
            <h2 class="section-title">Cek Kesiapan</h2>
            <div class="check-list">
                <div class="check-item">
                    <div class="check-icon">1</div>
                    <div>Nama produk mudah dicari dan tidak terlalu umum.</div>
                </div>
                <div class="check-item">
                    <div class="check-icon">2</div>
                    <div>Harga sudah sesuai satuan jual yang tampil di marketplace.</div>
                </div>
                <div class="check-item">
                    <div class="check-icon">3</div>
                    <div>Stok awal sudah dihitung agar status stok tidak keliru.</div>
                </div>
                <div class="check-item">
                    <div class="check-icon">4</div>
                    <div>Foto produk terang, jelas, dan memperlihatkan barang utama.</div>
                </div>
            </div>

            <div class="summary">
                Produk yang lengkap akan lebih siap tampil di marketplace dan lebih mudah dipantau dari dashboard UMKM.
            </div>
        </aside>
    </div>
</main>

<script>
    const imageInput = document.getElementById('gambar');
    const imagePreview = document.getElementById('imagePreview');

    imageInput?.addEventListener('change', function () {
        const file = this.files?.[0];
        if (!file) {
            imagePreview.textContent = 'Preview Foto';
            return;
        }

        const reader = new FileReader();
        reader.onload = event => {
            imagePreview.innerHTML = '<img src="' + event.target.result + '" alt="Preview foto produk">';
        };
        reader.readAsDataURL(file);
    });
</script>

</body>
</html>
