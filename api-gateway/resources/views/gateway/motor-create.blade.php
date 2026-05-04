@extends('layouts.app')

@section('title', 'Tambah Motor - Admin')

@section('content')
    <a href="{{ route('gateway.motors') }}" class="back-link">← Kembali ke Katalog</a>

    <div class="page-header" style="margin-top:1rem">
        <h1>Tambah Motor Baru</h1>
        <p>Isi data motor yang akan ditambahkan ke inventaris</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="form-card" style="max-width:720px">
        <form method="POST" action="{{ route('gateway.motor.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="nama">Nama Motor</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" placeholder="contoh: Honda Beat" required>
                </div>
                <div class="form-group">
                    <label for="merk">Merk</label>
                    <select name="merk" id="merk" class="form-control" required>
                        <option value="">-- Pilih Merk --</option>
                        <option value="Honda" {{ old('merk')=='Honda'?'selected':'' }}>Honda</option>
                        <option value="Yamaha" {{ old('merk')=='Yamaha'?'selected':'' }}>Yamaha</option>
                        <option value="Suzuki" {{ old('merk')=='Suzuki'?'selected':'' }}>Suzuki</option>
                        <option value="Kawasaki" {{ old('merk')=='Kawasaki'?'selected':'' }}>Kawasaki</option>
                    </select>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="tipe">Tipe</label>
                    <select name="tipe" id="tipe" class="form-control" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="Matic" {{ old('tipe')=='Matic'?'selected':'' }}>Matic</option>
                        <option value="Sport" {{ old('tipe')=='Sport'?'selected':'' }}>Sport</option>
                        <option value="Bebek" {{ old('tipe')=='Bebek'?'selected':'' }}>Bebek</option>
                        <option value="Trail" {{ old('tipe')=='Trail'?'selected':'' }}>Trail</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <input type="number" name="tahun" id="tahun" class="form-control" value="{{ old('tahun', date('Y')) }}" min="2000" max="2030" required>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="warna">Warna</label>
                    <input type="text" name="warna" id="warna" class="form-control" value="{{ old('warna') }}" placeholder="contoh: Merah" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga (Rp)</label>
                    <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga') }}" min="0" step="100000" placeholder="contoh: 17500000" required>
                </div>
            </div>
            <div class="form-group">
                <label for="stok">Stok Awal</label>
                <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok', 0) }}" min="0" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi (opsional)</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Deskripsi motor...">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="form-group">
                <label for="gambar">URL Gambar (opsional)</label>
                <input type="text" name="gambar" id="gambar" class="form-control" value="{{ old('gambar') }}" placeholder="http://localhost:8001/images/motors/nama_file.png">
            </div>
            <div style="display:flex;gap:0.75rem;margin-top:1.25rem">
                <button type="submit" class="btn btn-primary">Simpan Motor</button>
                <a href="{{ route('gateway.motors') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
