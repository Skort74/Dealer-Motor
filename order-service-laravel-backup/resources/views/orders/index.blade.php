@extends('layouts.app')

@section('title', 'OrderService - Dashboard Transaksi')

@section('content')
    <div class="page-header">
        <h1>📊 Dashboard Transaksi</h1>
        <p>Riwayat dan ringkasan semua transaksi pemesanan motor</p>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-value">{{ $orders->count() }}</span>
            <span class="stat-label">Total Pesanan</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $totalConfirmed }}</span>
            <span class="stat-label">Terkonfirmasi</span>
        </div>
        <div class="stat-item">
            <span class="stat-value price-text">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            <span class="stat-label">Total Pendapatan</span>
        </div>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <h2 style="font-size:1.2rem;font-weight:700">📋 Riwayat Transaksi</h2>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">➕ Buat Pesanan Baru</a>
    </div>

    @if($orders->count() > 0)
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Kode Order</th>
                        <th>Pelanggan</th>
                        <th>Motor</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="font-weight:600;color:var(--primary-light)">{{ $order->kode_order }}</td>
                            <td>{{ $order->nama_pelanggan }}</td>
                            <td>{{ $order->motor_nama }}<br><span style="font-size:0.75rem;color:var(--text-muted)">{{ $order->motor_merk }}</span></td>
                            <td>{{ $order->jumlah }} unit</td>
                            <td><span class="price-text">Rp {{ number_format($order->total, 0, ',', '.') }}</span></td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="font-size:0.8rem">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td><a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary btn-sm">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align:center;padding:4rem;color:var(--text-muted);background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border)">
            <p style="font-size:3rem;margin-bottom:1rem">📋</p>
            <p style="font-size:1.1rem;margin-bottom:1rem">Belum ada transaksi</p>
            <a href="{{ route('orders.create') }}" class="btn btn-primary">➕ Buat Pesanan Pertama</a>
        </div>
    @endif

    <div style="margin-top:2rem;padding:1.5rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg)">
        <h3 style="margin-bottom:1rem;color:var(--text-primary)">📡 API Endpoints (Provider)</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1rem">
            <div style="padding:0.75rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Daftar Transaksi</div>
                <div style="font-family:monospace;font-size:0.85rem;color:var(--secondary)">GET /api/orders</div>
            </div>
            <div style="padding:0.75rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Statistik Penjualan</div>
                <div style="font-family:monospace;font-size:0.85rem;color:var(--secondary)">GET /api/orders/statistics</div>
            </div>
            <div style="padding:0.75rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Buat Pesanan (Consumer)</div>
                <div style="font-family:monospace;font-size:0.85rem;color:var(--secondary)">POST /api/orders</div>
            </div>
        </div>
    </div>
@endsection
