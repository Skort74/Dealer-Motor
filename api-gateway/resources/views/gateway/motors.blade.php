@extends('layouts.app')

@section('title', 'Katalog Motor - API Gateway')

@section('content')
    <div class="page-header">
        <div class="gateway-badge">⚡ Data diambil melalui API Gateway → MotorService :8001</div>
        <h1>🏍️ Katalog Motor</h1>
        <p>Semua data motor diambil dari MotorService melalui API Gateway</p>
    </div>

    @if($error)
        <div class="alert alert-error">❌ {{ $error }}</div>
    @endif

    {{-- FILTER BY MERK --}}
    <div class="filter-bar">
        <a href="{{ route('gateway.motors') }}" class="btn {{ !request('merk') ? 'btn-primary' : 'btn-secondary' }} btn-sm">📋 Semua</a>
        <a href="{{ route('gateway.motors', ['merk' => 'Honda']) }}" class="btn {{ request('merk') == 'Honda' ? 'btn-honda' : 'btn-secondary' }} btn-sm">🔴 Honda</a>
        <a href="{{ route('gateway.motors', ['merk' => 'Yamaha']) }}" class="btn {{ request('merk') == 'Yamaha' ? 'btn-yamaha' : 'btn-secondary' }} btn-sm">🔵 Yamaha</a>
        <a href="{{ route('gateway.motors', ['merk' => 'Suzuki']) }}" class="btn {{ request('merk') == 'Suzuki' ? 'btn-accent' : 'btn-secondary' }} btn-sm">🟡 Suzuki</a>
        <a href="{{ route('gateway.motors', ['merk' => 'Kawasaki']) }}" class="btn {{ request('merk') == 'Kawasaki' ? 'btn-primary' : 'btn-secondary' }} btn-sm">🟢 Kawasaki</a>

        <div style="flex:1"></div>

        <form action="{{ route('gateway.syncTerlaris') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-accent btn-sm">🔄 Sync Terlaris</button>
        </form>
    </div>

    @if(request('merk'))
        <div style="margin-bottom:1.5rem;padding:1rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);display:flex;align-items:center;gap:10px">
            <span class="gateway-badge" style="margin:0">⚡ API Call</span>
            <span style="font-family:monospace;font-size:0.85rem;color:var(--secondary)">Gateway GET /api/motors?merk={{ request('merk') }} → MotorService :8001</span>
        </div>
    @endif

    {{-- STATS --}}
    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-value">{{ count($motors) }}</span>
            <span class="stat-label">{{ request('merk') ? 'Motor ' . request('merk') : 'Total Motor' }}</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ collect($motors)->sum('stok') }}</span>
            <span class="stat-label">Total Stok</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ collect($motors)->where('is_terlaris', true)->count() }}</span>
            <span class="stat-label">Motor Terlaris</span>
        </div>
    </div>

    {{-- MOTOR GRID --}}
    @if(count($motors) > 0)
        <div class="motor-grid">
            @foreach($motors as $motor)
                @php
                    $merkLower = strtolower($motor['merk']);
                    $badgeClass = match($merkLower) {
                        'honda' => 'badge-honda',
                        'yamaha' => 'badge-yamaha',
                        'suzuki' => 'badge-suzuki',
                        'kawasaki' => 'badge-kawasaki',
                        default => 'badge-default',
                    };
                @endphp
                <div class="card">
                    <div class="card-image" @if($motor['gambar']) style="background-image: url('{{ $motor['gambar'] }}'); background-size: cover; background-position: center;" @endif>
                        <span class="badge-merk {{ $badgeClass }}">{{ $motor['merk'] }}</span>
                        @if($motor['is_terlaris'])
                            <span class="badge-terlaris">🔥 Terlaris</span>
                        @endif
                        @if(!$motor['gambar'])
                            <span class="motor-icon">🏍️</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="{{ route('gateway.motor.detail', $motor['id']) }}">{{ $motor['nama'] }}</a>
                        </h3>
                        <div class="card-meta">
                            <span class="card-tag">📅 {{ $motor['tahun'] }}</span>
                            <span class="card-tag">🎨 {{ $motor['warna'] }}</span>
                            <span class="card-tag">⚡ {{ $motor['tipe'] }}</span>
                        </div>
                        <div class="card-price">Rp {{ number_format($motor['harga'], 0, ',', '.') }}</div>
                        <div class="card-stock">
                            @if($motor['stok'] > 5)
                                <span class="stock-indicator stock-available"><span class="dot"></span> Stok: {{ $motor['stok'] }}</span>
                            @elseif($motor['stok'] > 0)
                                <span class="stock-indicator stock-low"><span class="dot"></span> Terbatas: {{ $motor['stok'] }}</span>
                            @else
                                <span class="stock-indicator stock-empty"><span class="dot"></span> Habis</span>
                            @endif
                            <a href="{{ route('gateway.order.create', ['motor_id' => $motor['id']]) }}" class="btn btn-primary btn-sm">🛒 Pesan</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center;padding:4rem;color:var(--text-muted);background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border)">
            <p style="font-size:3rem">🔍</p>
            <p>Tidak ada motor ditemukan</p>
        </div>
    @endif
@endsection
