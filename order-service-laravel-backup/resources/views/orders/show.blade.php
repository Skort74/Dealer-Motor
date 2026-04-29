@extends('layouts.app')

@section('title', $order->kode_order . ' - Detail Pesanan')

@section('content')
    <a href="{{ route('orders.index') }}" class="back-link">← Kembali ke Dashboard</a>

    <div class="page-header" style="margin-top:1rem;display:flex;justify-content:space-between;align-items:flex-start">
        <div>
            <h1>📋 Detail Pesanan</h1>
            <p>Kode: <strong style="color:var(--primary-light)">{{ $order->kode_order }}</strong></p>
        </div>
        <span class="status-badge status-{{ $order->status }}" style="font-size:0.9rem;padding:6px 16px">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="detail-grid">
        <div class="detail-card">
            <h3>👤 Informasi Pelanggan</h3>
            <div class="detail-row">
                <span class="label">Nama</span>
                <span class="value">{{ $order->nama_pelanggan }}</span>
            </div>
            <div class="detail-row">
                <span class="label">No. Telepon</span>
                <span class="value">{{ $order->no_telepon }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Alamat</span>
                <span class="value">{{ $order->alamat }}</span>
            </div>
            @if($order->catatan)
            <div class="detail-row">
                <span class="label">Catatan</span>
                <span class="value">{{ $order->catatan }}</span>
            </div>
            @endif
        </div>

        <div class="detail-card">
            <h3>🏍️ Informasi Motor</h3>
            <div class="detail-row">
                <span class="label">Motor</span>
                <span class="value">{{ $order->motor_nama }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Merk</span>
                <span class="value">{{ $order->motor_merk }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Motor ID</span>
                <span class="value" style="font-family:monospace;color:var(--secondary)">#{{ $order->motor_id }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Harga/unit</span>
                <span class="value">Rp {{ number_format($order->harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="detail-card">
            <h3>💰 Rincian Pembayaran</h3>
            <div class="detail-row">
                <span class="label">Harga Satuan</span>
                <span class="value">Rp {{ number_format($order->harga, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Jumlah</span>
                <span class="value">{{ $order->jumlah }} unit</span>
            </div>
            <div class="detail-row" style="padding-top:12px;border-top:2px solid var(--border)">
                <span class="label" style="font-weight:700;color:var(--text-primary);font-size:1rem">Total</span>
                <span class="value price-text" style="font-size:1.3rem">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="detail-card">
            <h3>📅 Informasi Waktu</h3>
            <div class="detail-row">
                <span class="label">Dibuat</span>
                <span class="value">{{ $order->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Diperbarui</span>
                <span class="value">{{ $order->updated_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Kode Order</span>
                <span class="value" style="color:var(--primary-light)">{{ $order->kode_order }}</span>
            </div>
        </div>
    </div>

    <div style="margin-top:2rem;padding:1.5rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg)">
        <h3 style="margin-bottom:1rem;color:var(--text-primary)">🔗 Alur Komunikasi yang Terjadi</h3>
        <div style="display:flex;gap:1rem;flex-wrap:wrap">
            <div style="flex:1;min-width:250px;padding:1rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.75rem;color:var(--accent);font-weight:600;margin-bottom:4px">Step 1 — Verifikasi Stok</div>
                <div style="font-family:monospace;font-size:0.8rem;color:var(--secondary)">OrderService → GET MotorService:8001/api/motors/{{ $order->motor_id }}/stock</div>
            </div>
            <div style="flex:1;min-width:250px;padding:1rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.75rem;color:var(--success);font-weight:600;margin-bottom:4px">Step 2 — Simpan Order</div>
                <div style="font-family:monospace;font-size:0.8rem;color:var(--secondary)">OrderService → Database (status: {{ $order->status }})</div>
            </div>
            <div style="flex:1;min-width:250px;padding:1rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.75rem;color:var(--primary-light);font-weight:600;margin-bottom:4px">Step 3 — Kurangi Stok</div>
                <div style="font-family:monospace;font-size:0.8rem;color:var(--secondary)">OrderService → PUT MotorService:8001/api/motors/{{ $order->motor_id }}/stock</div>
            </div>
        </div>
    </div>
@endsection
