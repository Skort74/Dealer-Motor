@extends('layouts.app')

@section('title', 'API Gateway - Dashboard')

@section('content')
    <div class="page-header">
        <h1>Dashboard</h1>
    </div>

    <div class="grid-service-cards">
        <div class="detail-card">
            <div class="service-card-header">
                <h3 style="margin:0;display:flex;align-items:center"><span class="service-icon">🏍️</span>MotorService</h3>
                <span class="service-status {{ ($health['services']['motor_service']['status'] ?? 'down') === 'up' ? 'service-up' : 'service-down' }}">
                    {{ ($health['services']['motor_service']['status'] ?? 'down') === 'up' ? 'Online' : 'Offline' }}
                </span>
            </div>
            <div class="detail-row"><span class="label">URL</span><span class="value" style="font-family:monospace">localhost:8001</span></div>
            <div class="detail-row"><span class="label">Role</span><span class="value">Provider & Consumer</span></div>
            <div class="detail-row"><span class="label">Database</span><span class="value">dealer_motor_service</span></div>
            <a href="http://localhost:8001" target="_blank" class="btn btn-primary btn-sm clean-btn" style="margin-top:0.75rem;width:100%;justify-content:center">Open Service →</a>
        </div>

        <div class="detail-card">
            <div class="service-card-header">
                <h3 style="margin:0;display:flex;align-items:center"><span class="service-icon">📋</span>OrderService</h3>
                <span class="service-status {{ ($health['services']['order_service']['status'] ?? 'down') === 'up' ? 'service-up' : 'service-down' }}">
                    {{ ($health['services']['order_service']['status'] ?? 'down') === 'up' ? 'Online' : 'Offline' }}
                </span>
            </div>
            <div class="detail-row"><span class="label">URL</span><span class="value" style="font-family:monospace">localhost:8002</span></div>
            <div class="detail-row"><span class="label">Role</span><span class="value">Provider & Consumer</span></div>
            <div class="detail-row"><span class="label">Database</span><span class="value">dealer_order_service</span></div>
            <a href="http://localhost:8002" target="_blank" class="btn btn-primary btn-sm clean-btn" style="margin-top:0.75rem;width:100%;justify-content:center">Open Service →</a>
        </div>

        <div class="detail-card">
            <div class="service-card-header">
                <h3 style="margin:0;display:flex;align-items:center"><span class="service-icon">⚡</span>API Gateway</h3>
                <span class="service-status service-up">Online</span>
            </div>
            <div class="detail-row"><span class="label">URL</span><span class="value" style="font-family:monospace">localhost:8000</span></div>
            <div class="detail-row"><span class="label">Role</span><span class="value">Proxy & Router</span></div>
            <div class="detail-row"><span class="label">Health API</span><span class="value" style="font-family:monospace">GET /api/health</span></div>
            <a href="/api/health" target="_blank" class="btn btn-primary btn-sm clean-btn" style="margin-top:0.75rem;width:100%;justify-content:center">Check Health →</a>
        </div>
    </div>

    <div class="grid-stats">
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

    <div class="mb-8">
        <h2 class="section-title">Filter Motors by Brand</h2>
        <div class="filter-bar mb-6">
            <a href="{{ route('gateway.motors') }}" class="btn btn-secondary btn-sm clean-btn">All Motors</a>
            <a href="{{ route('gateway.motors', ['merk' => 'Honda']) }}" class="btn btn-honda btn-sm clean-btn">Honda</a>
            <a href="{{ route('gateway.motors', ['merk' => 'Yamaha']) }}" class="btn btn-yamaha btn-sm clean-btn">Yamaha</a>
            <a href="{{ route('gateway.motors', ['merk' => 'Suzuki']) }}" class="btn btn-accent btn-sm clean-btn">Suzuki</a>
            <a href="{{ route('gateway.motors', ['merk' => 'Kawasaki']) }}" class="btn btn-primary btn-sm clean-btn">Kawasaki</a>
        </div>
    </div>

    @if(count($orders) > 0)
        <h2 class="section-title">Recent Transactions</h2>
        <div class="table-responsive">
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>ID</th><th>Customer</th><th>Motor</th><th>Total</th><th>Status</th><th>Via</th></tr></thead>
                    <tbody>
                        @foreach(array_slice($orders, 0, 5) as $order)
                            <tr>
                                <td style="font-weight:600;color:var(--primary-light)">{{ $order['kode_order'] }}</td>
                                <td>{{ $order['nama_pelanggan'] }}</td>
                                <td>{{ $order['motor_nama'] }}</td>
                                <td><span class="price-text">Rp {{ number_format($order['total'], 0, ',', '.') }}</span></td>
                                <td><span class="status-badge status-{{ $order['status'] }}">{{ ucfirst($order['status']) }}</span></td>
                                <td><span class="gateway-badge">Gateway</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <a href="{{ route('gateway.orders') }}" class="btn btn-primary btn-sm clean-btn mt-6">View All Transactions →</a>
    @endif
@endsection

<style>
    a.btn { display: inline-flex !important; }
</style>

