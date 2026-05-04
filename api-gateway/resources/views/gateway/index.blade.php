@extends('layouts.app')

@section('title', 'Admin - Dashboard')

@section('content')
    <div class="page-header">
        <h1>⚙️ Admin Dashboard</h1>
        <p>Monitoring dan kontrol terpusat untuk semua layanan dealer motor</p>
    </div>

    {{-- SERVICE STATUS --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem;margin-bottom:2rem">
        <div class="detail-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                <h3 style="margin:0">🏍️ Katalog</h3>
                <span class="service-status {{ ($health['services']['motor_service']['status'] ?? 'down') === 'up' ? 'service-up' : 'service-down' }}">
                    {{ ($health['services']['motor_service']['status'] ?? 'down') === 'up' ? '● Online' : '● Offline' }}
                </span>
            </div>
            <div class="detail-row"><span class="label">URL</span><span class="value" style="font-family:monospace;font-size:0.8rem">localhost:8001</span></div>
            <div class="detail-row"><span class="label">Peran</span><span class="value">Provider & Consumer</span></div>
            <div class="detail-row"><span class="label">Database</span><span class="value">dealer_motor_service</span></div>
            <a href="http://localhost:8001" target="_blank" class="btn btn-secondary btn-sm" style="margin-top:0.75rem;width:100%;justify-content:center">Buka Katalog →</a>
        </div>

        <div class="detail-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                <h3 style="margin:0">📋 Dashboard</h3>
                <span class="service-status {{ ($health['services']['order_service']['status'] ?? 'down') === 'up' ? 'service-up' : 'service-down' }}">
                    {{ ($health['services']['order_service']['status'] ?? 'down') === 'up' ? '● Online' : '● Offline' }}
                </span>
            </div>
            <div class="detail-row"><span class="label">URL</span><span class="value" style="font-family:monospace;font-size:0.8rem">localhost:8002</span></div>
            <div class="detail-row"><span class="label">Peran</span><span class="value">Provider & Consumer</span></div>
            <div class="detail-row"><span class="label">Database</span><span class="value">dealer_order_service</span></div>
            <a href="http://localhost:8002" target="_blank" class="btn btn-secondary btn-sm" style="margin-top:0.75rem;width:100%;justify-content:center">Buka Dashboard →</a>
        </div>

        <div class="detail-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                <h3 style="margin:0">⚙️ Admin</h3>
                <span class="service-status service-up">● Online</span>
            </div>
            <div class="detail-row"><span class="label">URL</span><span class="value" style="font-family:monospace;font-size:0.8rem">localhost:8000</span></div>
            <div class="detail-row"><span class="label">Peran</span><span class="value">Proxy & Router</span></div>
            <div class="detail-row"><span class="label">Health API</span><span class="value" style="font-family:monospace;font-size:0.8rem">GET /api/health</span></div>
            <a href="/api/health" target="_blank" class="btn btn-primary btn-sm" style="margin-top:0.75rem;width:100%;justify-content:center">Cek Health →</a>
        </div>
    </div>

    {{-- STATISTICS --}}
    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-value">{{ count($motors) }}</span>
            <span class="stat-label">Total Motor</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ collect($motors)->sum('stok') }}</span>
            <span class="stat-label">Total Stok</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $statistics['total_transaksi'] ?? 0 }}</span>
            <span class="stat-label">Total Transaksi</span>
        </div>
        <div class="stat-item">
            <span class="stat-value price-text">Rp {{ number_format($statistics['total_pendapatan'] ?? 0, 0, ',', '.') }}</span>
            <span class="stat-label">Total Pendapatan</span>
        </div>
    </div>

    {{-- QUICK ACCESS FILTER --}}
    <div style="margin-bottom:2rem">
        <h2 style="font-size:1.2rem;font-weight:700;margin-bottom:1rem">🏍️ Filter Motor by Merk</h2>
        <div class="filter-bar">
            <a href="{{ route('gateway.motors') }}" class="btn btn-primary btn-sm">📋 Semua Motor</a>
            <a href="{{ route('gateway.motors', ['merk' => 'Honda']) }}" class="btn btn-honda btn-sm">🔴 Motor Honda</a>
            <a href="{{ route('gateway.motors', ['merk' => 'Yamaha']) }}" class="btn btn-yamaha btn-sm">🔵 Motor Yamaha</a>
            <a href="{{ route('gateway.motors', ['merk' => 'Suzuki']) }}" class="btn btn-accent btn-sm">🟡 Motor Suzuki</a>
            <a href="{{ route('gateway.motors', ['merk' => 'Kawasaki']) }}" class="btn btn-primary btn-sm">🟢 Motor Kawasaki</a>
        </div>
    </div>



    {{-- RECENT ORDERS --}}
    @if(count($orders) > 0)
        <h2 style="font-size:1.2rem;font-weight:700;margin-bottom:1rem">📋 Transaksi Terbaru</h2>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Kode</th><th>Pelanggan</th><th>Motor</th><th>Total</th><th>Status</th><th>Via</th></tr></thead>
                <tbody>
                    @foreach(array_slice($orders, 0, 5) as $order)
                        <tr>
                            <td style="font-weight:600;color:var(--primary-light)">{{ $order['kode_order'] }}</td>
                            <td>{{ $order['nama_pelanggan'] }}</td>
                            <td>{{ $order['motor_nama'] }}</td>
                            <td><span class="price-text">Rp {{ number_format($order['total'], 0, ',', '.') }}</span></td>
                            <td><span class="status-badge status-{{ $order['status'] }}">{{ ucfirst($order['status']) }}</span></td>
                            <td><span class="gateway-badge">⚙️ Admin</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('gateway.orders') }}" class="btn btn-secondary btn-sm" style="margin-top:1rem">Lihat Semua Transaksi →</a>
    @endif
@endsection
