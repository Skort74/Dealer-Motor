@extends('layouts.app')

@section('title', 'Detail Pesanan - Admin')

@section('content')
    <a href="{{ route('gateway.orders') }}" class="back-link">← Kembali ke Transaksi</a>

    <div style="margin-top:1rem;display:flex;justify-content:space-between;align-items:flex-start">
        <div>
            <div class="gateway-badge">⚡ GET /api/orders/{{ $order['id'] }} → Gateway → Dashboard :8002</div>
            <h1 style="font-size:1.5rem;font-weight:800;margin-top:0.5rem">📋 {{ $order['kode_order'] }}</h1>
        </div>
        <span class="status-badge status-{{ $order['status'] }}" style="font-size:0.9rem;padding:6px 16px">{{ ucfirst($order['status']) }}</span>
    </div>

    <div class="detail-grid" style="margin-top:1.5rem">
        <div class="detail-card">
            <h3>👤 Pelanggan</h3>
            <div class="detail-row"><span class="label">Nama</span><span class="value">{{ $order['nama_pelanggan'] }}</span></div>
            <div class="detail-row"><span class="label">Telepon</span><span class="value">{{ $order['no_telepon'] }}</span></div>
            <div class="detail-row"><span class="label">Alamat</span><span class="value">{{ $order['alamat'] }}</span></div>
        </div>
        <div class="detail-card">
            <h3>🏍️ Motor</h3>
            <div class="detail-row"><span class="label">Nama</span><span class="value">{{ $order['motor_nama'] }}</span></div>
            @if(!empty($order['motor_merk']))
            <div class="detail-row"><span class="label">Merk</span><span class="value">{{ $order['motor_merk'] }}</span></div>
            @endif
            <div class="detail-row"><span class="label">ID</span><span class="value" style="font-family:monospace;color:var(--secondary)">#{{ $order['motor_id'] }}</span></div>
        </div>
        <div class="detail-card">
            <h3>💰 Pembayaran</h3>
            <div class="detail-row"><span class="label">Harga/unit</span><span class="value">Rp {{ number_format($order['harga_satuan'] ?? $order['harga'] ?? 0, 0, ',', '.') }}</span></div>
            <div class="detail-row"><span class="label">Jumlah</span><span class="value">{{ $order['jumlah'] }} unit</span></div>
            <div class="detail-row" style="border-top:2px solid var(--border);padding-top:12px">
                <span class="label" style="font-weight:700;color:var(--text-primary)">Total</span>
                <span class="value price-text" style="font-size:1.2rem">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="detail-card">
            <h3>📅 Waktu</h3>
            <div class="detail-row"><span class="label">Dibuat</span><span class="value">{{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') }}</span></div>
            <div class="detail-row"><span class="label">Kode</span><span class="value" style="color:var(--primary-light)">{{ $order['kode_order'] }}</span></div>
        </div>
    </div>
@endsection
