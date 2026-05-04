@extends('layouts.app')

@section('title', 'Katalog Motor - Admin')

@section('content')
    <div class="page-header">
        <h1>Katalog Motor</h1>
        <p>Kelola inventaris motor dealer</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if($error)
        <div class="alert alert-error">{{ $error }}</div>
    @endif

    <div class="filter-bar">
        <a href="{{ route('gateway.motor.create') }}" class="btn btn-primary">+ Tambah Motor</a>
        <div style="flex:1"></div>
        <a href="{{ route('gateway.motors') }}" class="btn {{ !request('merk') ? 'btn-primary' : 'btn-secondary' }} btn-sm">Semua</a>
        <a href="{{ route('gateway.motors', ['merk' => 'Honda']) }}" class="btn {{ request('merk') == 'Honda' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Honda</a>
        <a href="{{ route('gateway.motors', ['merk' => 'Yamaha']) }}" class="btn {{ request('merk') == 'Yamaha' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Yamaha</a>
        <a href="{{ route('gateway.motors', ['merk' => 'Suzuki']) }}" class="btn {{ request('merk') == 'Suzuki' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Suzuki</a>
        <a href="{{ route('gateway.motors', ['merk' => 'Kawasaki']) }}" class="btn {{ request('merk') == 'Kawasaki' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Kawasaki</a>

        <form action="{{ route('gateway.syncTerlaris') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">Refresh Terlaris</button>
        </form>
    </div>

    <div class="grid-stats" style="margin-bottom:1.5rem">
        <div class="stat-item">
            <span class="stat-value">{{ count($motors) }}</span>
            <span class="stat-label">{{ request('merk') ? ucfirst(request('merk')) : 'Total Motor' }}</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ collect($motors)->sum('stok') }}</span>
            <span class="stat-label">Total Stok</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ collect($motors)->where('is_terlaris', true)->count() }}</span>
            <span class="stat-label">Terlaris</span>
        </div>
    </div>

    @if(count($motors) > 0)
        <div class="motor-grid">
            @foreach($motors as $motor)
                <div class="card">
                    <div class="card-image" @if($motor['gambar']) style="background-image: url('{{ $motor['gambar'] }}'); background-size: cover; background-position: center;" @endif>
                        <span class="badge-merk">{{ $motor['merk'] }}</span>
                        @if($motor['is_terlaris'])
                            <span class="badge-terlaris">Terlaris</span>
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
                            <span class="card-tag">{{ $motor['tahun'] }}</span>
                            <span class="card-tag">{{ $motor['warna'] }}</span>
                            <span class="card-tag">{{ $motor['tipe'] }}</span>
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
                        </div>
                        <div style="display:flex;gap:4px;margin-top:0.75rem;border-top:1px solid var(--border);padding-top:0.75rem">
                            <a href="{{ route('gateway.motor.edit', $motor['id']) }}" class="btn btn-secondary btn-sm" style="flex:1;text-align:center">Edit</a>
                            <form action="{{ route('gateway.motor.destroy', $motor['id']) }}" method="POST" style="flex:1" onsubmit="return confirm('Hapus motor {{ $motor['nama'] }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="width:100%;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;cursor:pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center;padding:4rem;color:var(--text-muted);background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border)">
            <p style="margin-bottom:1rem">Tidak ada motor ditemukan</p>
            <a href="{{ route('gateway.motor.create') }}" class="btn btn-primary">Tambah Motor Pertama</a>
        </div>
    @endif
@endsection
