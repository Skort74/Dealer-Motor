@extends('layouts.app')

@section('title', 'Edit Pesanan - API Gateway')

@section('content')
    <a href="{{ route('gateway.orders') }}" class="back-link">← Kembali ke Transaksi</a>

    <div style="margin-top:1rem">
        <div class="gateway-badge">⚡ PUT /api/orders/{{ $order['id'] }} → Gateway → OrderService :8002</div>
        <h1 style="font-size:1.5rem;font-weight:800;margin-top:0.5rem">✏️ Edit Pesanan</h1>
        <p style="color:var(--text-secondary)">Kode: <strong style="color:var(--primary-light)">{{ $order['kode_order'] }}</strong> — Motor: <strong>{{ $order['motor_nama'] }}</strong></p>
    </div>

    @php
        $createdAt = strtotime($order['created_at']);
        $sisaDetik = ($createdAt + 12*3600) - time();
        $sisaJam = floor($sisaDetik / 3600);
        $sisaMenit = floor(($sisaDetik % 3600) / 60);
    @endphp
    <div style="padding:0.75rem 1rem;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);border-radius:var(--radius);margin:1rem 0 1.5rem;display:flex;align-items:center;gap:10px">
        <span>⏰</span>
        <span style="font-size:0.85rem;color:#fcd34d">Sisa waktu edit: <strong>{{ $sisaJam }}j {{ $sisaMenit }}m</strong> — Edit hanya bisa dilakukan dalam 12 jam setelah pemesanan</span>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem">
        <div class="form-card">
            <h3 style="margin-bottom:1.5rem;color:var(--text-primary)">📝 Edit Data Pesanan</h3>

            <form method="POST" action="{{ route('gateway.order.update', $order['id']) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>🏍️ Motor (tidak dapat diubah)</label>
                    <input type="text" class="form-control" value="{{ $order['motor_nama'] }}" disabled style="opacity:0.6;cursor:not-allowed">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_pelanggan">👤 Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control"
                               value="{{ old('nama_pelanggan', $order['nama_pelanggan']) }}" placeholder="Nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label for="no_telepon">📱 No. Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control"
                               value="{{ old('no_telepon', $order['no_telepon']) }}" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">📍 Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat lengkap" required>{{ old('alamat', $order['alamat']) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="jumlah">📦 Jumlah Unit</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control"
                           value="{{ old('jumlah', $order['jumlah']) }}" min="1" required>
                </div>

                <div class="form-group">
                    <label for="catatan">📝 Catatan (opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" placeholder="Catatan tambahan...">{{ old('catatan', $order['catatan']) }}</textarea>
                </div>

                <div style="display:flex;gap:1rem;margin-top:1.5rem">
                    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                    <a href="{{ route('gateway.orders') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <div>
            <div class="form-card" style="position:sticky;top:90px">
                <h3 style="margin-bottom:1rem;color:var(--text-primary)">📋 Info Pesanan</h3>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Kode Order</div>
                    <div style="font-weight:700;color:var(--primary-light)">{{ $order['kode_order'] }}</div>
                </div>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Motor</div>
                    <div style="font-weight:600;color:var(--text-primary)">{{ $order['motor_nama'] }}</div>
                </div>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Harga Satuan</div>
                    <div style="font-weight:600;color:var(--text-primary)">Rp {{ number_format($order['harga_satuan'] ?? $order['harga'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Status</div>
                    <span class="status-badge status-{{ $order['status'] }}">{{ ucfirst($order['status']) }}</span>
                </div>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Tanggal Pesan</div>
                    <div style="font-size:0.85rem;color:var(--text-secondary)">{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</div>
                </div>
                <div>
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Total Saat Ini</div>
                    <div class="price-text" style="font-size:1.3rem" id="total-display">Rp {{ number_format($order['total'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const jumlahInput = document.getElementById('jumlah');
    const totalDisplay = document.getElementById('total-display');
    const hargaSatuan = {{ (float)($order['harga_satuan'] ?? $order['harga'] ?? 0) }};

    jumlahInput.addEventListener('input', function() {
        const jumlah = parseInt(this.value) || 0;
        const total = hargaSatuan * jumlah;
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    });
</script>
@endsection
