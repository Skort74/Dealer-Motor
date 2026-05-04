@extends('layouts.app')

@section('title', 'Dealer Motor - Katalog Motor')

@section('content')
    <div class="page-header">
        <h1>🏍️ Katalog Motor</h1>
        <p>Temukan motor impian Anda dari berbagai merk terkemuka</p>
    </div>

    <div class="toolbar">
        <div class="stats-bar">
            <div class="stat-item">
                <span class="stat-value">{{ $motors->count() }}</span>
                <span class="stat-label">Total Motor</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ $motors->sum('stok') }}</span>
                <span class="stat-label">Total Stok</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ $motors->where('is_terlaris', true)->count() }}</span>
                <span class="stat-label">Motor Terlaris</span>
            </div>
        </div>
        <form action="{{ route('motors.syncTerlaris') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-accent">🔄 Sync Motor Terlaris</button>
        </form>
    </div>

    <form method="GET" action="{{ route('motors.index') }}" class="filters">
        <div class="filter-group">
            <label>Merk:</label>
            <select name="merk" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Merk</option>
                @foreach($merks as $merk)
                    <option value="{{ $merk }}" {{ request('merk') == $merk ? 'selected' : '' }}>{{ $merk }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Tipe:</label>
            <select name="tipe" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                @foreach($tipes as $tipe)
                    <option value="{{ $tipe }}" {{ request('tipe') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Cari:</label>
            <input type="text" name="search" class="filter-input" placeholder="Cari motor..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-secondary btn-sm">🔍 Cari</button>
        @if(request('merk') || request('tipe') || request('search'))
            <a href="{{ route('motors.index') }}" class="btn btn-secondary btn-sm">✕ Reset</a>
        @endif
    </form>

    @if($motors->count() > 0)
        <div class="motor-grid">
            @foreach($motors as $motor)
                <div class="card">
                    <div class="card-image">
                        <span class="badge-merk">{{ $motor->merk }}</span>
                        @if($motor->is_terlaris)
                            <span class="badge-terlaris">🔥 Terlaris</span>
                        @endif
                        <span class="motor-icon">🏍️</span>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="{{ route('motors.show', $motor->id) }}">{{ $motor->nama }}</a>
                        </h3>
                        
                        <div class="card-meta">
                            <span class="card-tag">📅 {{ $motor->tahun }}</span>
                            <span class="card-tag">🎨 {{ $motor->warna }}</span>
                            <span class="card-tag">⚡ {{ $motor->tipe }}</span>
                        </div>
                        <div class="card-price">Rp {{ number_format($motor->harga, 0, ',', '.') }}</div>
                        <div class="card-stock">
                            @if($motor->stok > 5)
                                <span class="stock-indicator available"><span class="dot"></span> Stok: {{ $motor->stok }} unit</span>
                            @elseif($motor->stok > 0)
                                <span class="stock-indicator low"><span class="dot"></span> Terbatas: {{ $motor->stok }} unit</span>
                            @else
                                <span class="stock-indicator empty"><span class="dot"></span> Stok Habis</span>
                            @endif
                            <a href="{{ route('motors.show', $motor->id) }}" class="btn btn-primary btn-sm">Detail →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center;padding:4rem;color:var(--text-muted)">
            <p style="font-size:3rem">🔍</p>
            <p>Tidak ada motor ditemukan</p>
            <a href="{{ route('motors.index') }}" class="btn btn-secondary" style="margin-top:1rem">Lihat Semua</a>
        </div>
    @endif
@endsection
