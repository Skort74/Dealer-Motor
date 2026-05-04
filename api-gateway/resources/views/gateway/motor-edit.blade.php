@extends('layouts.app')

@section('title', 'Edit Motor - Admin')

@section('content')
    <a href="{{ route('gateway.motors') }}" class="back-link">← Kembali ke Katalog</a>

    <div class="page-header" style="margin-top:1rem">
        <h1>Edit Motor</h1>
        <p>Perbarui data <strong>{{ $motor['nama'] }}</strong></p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="form-card" style="max-width:720px">
        <form method="POST" action="{{ route('gateway.motor.update', $motor['id']) }}">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label for="nama">Nama Motor</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $motor['nama']) }}" required>
                </div>
                <div class="form-group">
                    <label for="merk">Merk</label>
                    <select name="merk" id="merk" class="form-control" required>
                        <option value="Honda" {{ old('merk', $motor['merk'])=='Honda'?'selected':'' }}>Honda</option>
                        <option value="Yamaha" {{ old('merk', $motor['merk'])=='Yamaha'?'selected':'' }}>Yamaha</option>
                        <option value="Suzuki" {{ old('merk', $motor['merk'])=='Suzuki'?'selected':'' }}>Suzuki</option>
                        <option value="Kawasaki" {{ old('merk', $motor['merk'])=='Kawasaki'?'selected':'' }}>Kawasaki</option>
                    </select>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="tipe">Tipe</label>
                    <select name="tipe" id="tipe" class="form-control" required>
                        <option value="Matic" {{ old('tipe', $motor['tipe'])=='Matic'?'selected':'' }}>Matic</option>
                        <option value="Sport" {{ old('tipe', $motor['tipe'])=='Sport'?'selected':'' }}>Sport</option>
                        <option value="Bebek" {{ old('tipe', $motor['tipe'])=='Bebek'?'selected':'' }}>Bebek</option>
                        <option value="Trail" {{ old('tipe', $motor['tipe'])=='Trail'?'selected':'' }}>Trail</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <input type="number" name="tahun" id="tahun" class="form-control" value="{{ old('tahun', $motor['tahun']) }}" min="2000" max="2030" required>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="warna">Warna</label>
                    <input type="text" name="warna" id="warna" class="form-control" value="{{ old('warna', $motor['warna']) }}" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga (Rp)</label>
                    <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga', $motor['harga']) }}" min="0" step="100000" required>
                </div>
            </div>
            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok', $motor['stok']) }}" min="0" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi (opsional)</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $motor['deskripsi'] ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="gambar">URL Gambar (opsional)</label>
                <input type="text" name="gambar" id="gambar" class="form-control" value="{{ old('gambar', $motor['gambar'] ?? '') }}">
            </div>
            @if(!empty($motor['gambar']))
                <div style="margin-bottom:1rem;padding:1rem;background:var(--bg);border-radius:var(--radius);border:1px solid var(--border)">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-bottom:0.5rem">Preview Gambar</p>
                    <div style="width:200px;height:140px;background-image:url('{{ $motor['gambar'] }}');background-size:contain;background-position:center;background-repeat:no-repeat;border-radius:var(--radius-sm)"></div>
                </div>
            @endif
            <div style="display:flex;gap:0.75rem;margin-top:1.25rem">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('gateway.motors') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
