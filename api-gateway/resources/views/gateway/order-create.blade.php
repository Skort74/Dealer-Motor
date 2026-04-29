@extends('layouts.app')

@section('title', 'Buat Pesanan - API Gateway')

@section('content')
    <a href="{{ route('gateway.motors') }}" class="back-link">← Kembali ke Katalog</a>

    <div class="page-header" style="margin-top:1rem">
        <div class="gateway-badge">⚡ POST /api/orders → Gateway → OrderService :8002 → verifikasi stok ke MotorService :8001</div>
        <h1>➕ Buat Pesanan via Gateway</h1>
        <p>Pesanan diproses melalui API Gateway. OrderService akan verifikasi stok ke MotorService secara otomatis.</p>
    </div>

    @if($error)
        <div class="alert alert-error">❌ {{ $error }}</div>
    @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem">
        <div class="form-card">
            <form method="POST" action="{{ route('gateway.order.store') }}">
                @csrf
                <div class="form-group">
                    <label>🏍️ Pilih Motor</label>
                    <select name="motor_id" id="motor_id" class="form-control" required>
                        <option value="">-- Pilih Motor --</option>
                        @foreach($motors as $motor)
                            <option value="{{ $motor['id'] }}" data-harga="{{ $motor['harga'] }}" data-stok="{{ $motor['stok'] }}" data-merk="{{ $motor['merk'] }}" data-gambar="{{ $motor['gambar'] ?? '' }}"
                                {{ ($selectedMotorId ?? '') == $motor['id'] ? 'selected' : '' }}>
                                {{ $motor['nama'] }} — {{ $motor['merk'] }} (Stok: {{ $motor['stok'] }}) — Rp {{ number_format($motor['harga'], 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>👤 Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}" required>
                    </div>
                    <div class="form-group">
                        <label>📱 No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>📍 Alamat</label>
                    <textarea name="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
                </div>
                <div class="form-group">
                    <label>📦 Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required>
                </div>
                <div class="form-group">
                    <label>📝 Catatan (opsional)</label>
                    <textarea name="catatan" class="form-control">{{ old('catatan') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">⚡ Pesan via Gateway</button>
            </form>
        </div>

        <div>
            <div class="form-card" style="position:sticky;top:90px">
                <h3 style="margin-bottom:1rem">💰 Ringkasan</h3>
                <div id="summary">
                    <div style="text-align:center;padding:2rem;color:var(--text-muted)">
                        <p style="font-size:2rem">🏍️</p><p>Pilih motor</p>
                    </div>
                </div>
            </div>
            <div class="form-card" style="margin-top:1rem">
                <h3 style="margin-bottom:0.75rem">🔗 Alur Komunikasi</h3>
                <div style="font-size:0.75rem;color:var(--text-secondary);line-height:2">
                    <div>1️⃣ Client → <strong style="color:var(--primary-light)">Gateway :8000</strong></div>
                    <div>2️⃣ Gateway → <strong style="color:var(--purple)">OrderService :8002</strong></div>
                    <div>3️⃣ OrderService → <strong style="color:var(--indigo)">MotorService :8001</strong> (cek stok)</div>
                    <div>4️⃣ OrderService → simpan order</div>
                    <div>5️⃣ OrderService → <strong style="color:var(--indigo)">MotorService :8001</strong> (kurangi stok)</div>
                    <div>6️⃣ Response kembali ke client via Gateway</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const sel = document.getElementById('motor_id');
    const qty = document.getElementById('jumlah');
    const sum = document.getElementById('summary');
    function update() {
        const o = sel.options[sel.selectedIndex];
        if (!o || !o.value) { sum.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--text-muted)"><p style="font-size:2rem">🏍️</p><p>Pilih motor</p></div>'; return; }
        const h = parseFloat(o.dataset.harga), s = parseInt(o.dataset.stok), q = parseInt(qty.value)||1, t = h*q, n = o.text.split(' — ')[0], g = o.dataset.gambar;
        let imgHtml = g && g !== '' ? `<div style="width: 100%; height: 150px; background-image: url('${g}'); background-size: contain; background-position: center; background-repeat: no-repeat; margin-bottom: 1rem; border-radius: var(--radius-sm);"></div>` : `<div style="text-align:center;padding:1rem;color:var(--text-muted)"><p style="font-size:2rem;margin-bottom:0.5rem">🏍️</p></div>`;
        sum.innerHTML = `${imgHtml}<div style="margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid var(--border)"><div style="font-weight:700">${n}</div><div style="font-size:0.8rem;color:var(--text-secondary)">${o.dataset.merk}</div></div><div style="display:flex;justify-content:space-between;margin-bottom:0.5rem"><span style="color:var(--text-muted);font-size:0.85rem">Harga</span><span style="font-weight:600">Rp ${h.toLocaleString('id-ID')}</span></div><div style="display:flex;justify-content:space-between;margin-bottom:0.5rem"><span style="color:var(--text-muted);font-size:0.85rem">Jumlah</span><span style="font-weight:600">${q} unit</span></div><div style="display:flex;justify-content:space-between;margin-bottom:0.5rem"><span style="color:var(--text-muted);font-size:0.85rem">Stok</span><span style="color:${s>0?'#6ee7b7':'#fca5a5'};font-weight:600">${s}</span></div><hr style="border:none;border-top:1px solid var(--border);margin:1rem 0"><div style="display:flex;justify-content:space-between"><span style="font-weight:700">Total</span><span class="price-text" style="font-size:1.2rem">Rp ${t.toLocaleString('id-ID')}</span></div>`;
    }
    sel.addEventListener('change', update);
    qty.addEventListener('input', update);
    update();
</script>
@endsection
