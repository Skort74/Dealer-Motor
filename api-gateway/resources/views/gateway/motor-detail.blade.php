@extends('layouts.app')

@section('title', $motor['nama'] . ' - Admin')

@section('content')
    <a href="{{ route('gateway.motors') }}" class="back-link">← Kembali ke Katalog</a>

    <div style="margin-top:1rem">
        <div class="gateway-badge">⚡ GET /api/motors/{{ $motor['id'] }} → Katalog :8001</div>
    </div>

    <div class="detail-grid" style="margin-top:1rem">
        <div class="detail-card" style="display:flex;align-items:center;justify-content:center;min-height:300px;font-size:5rem;position:relative;{{ $motor['gambar'] ? 'background-image: url(' . $motor['gambar'] . '); background-size: contain; background-repeat: no-repeat; background-position: center;' : '' }}">
            @if($motor['is_terlaris'])
                <span class="badge-terlaris" style="position:absolute;top:16px;right:16px;font-size:0.85rem;padding:8px 16px;z-index:10;">🔥 Terlaris</span>
            @endif
            @if(!$motor['gambar'])
                🏍️
            @endif
        </div>

        <div style="display:flex;flex-direction:column;gap:1.25rem">
            <div>
                <div class="card-meta" style="margin-bottom:0.5rem">
                    <span class="card-tag">{{ $motor['merk'] }}</span>
                    <span class="card-tag">{{ $motor['tipe'] }}</span>
                    <span class="card-tag">{{ $motor['tahun'] }}</span>
                </div>
                <h1 style="font-size:1.8rem;font-weight:800">{{ $motor['nama'] }}</h1>
            </div>

            <div class="card-price" style="font-size:1.8rem">Rp {{ number_format($motor['harga'], 0, ',', '.') }}</div>

            <div class="detail-card" style="padding:1rem">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem">
                    <div><span style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase">Merk</span><br><strong>{{ $motor['merk'] }}</strong></div>
                    <div><span style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase">Tipe</span><br><strong>{{ $motor['tipe'] }}</strong></div>
                    <div><span style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase">Warna</span><br><strong>{{ $motor['warna'] }}</strong></div>
                    <div><span style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase">Stok</span><br><strong style="color:{{ $motor['stok'] > 0 ? '#6ee7b7' : '#fca5a5' }}">{{ $motor['stok'] }} unit</strong></div>
                </div>
            </div>

            @if($motor['deskripsi'])
                <div class="detail-card" style="padding:1rem;color:var(--text-secondary);line-height:1.8">
                    {{ $motor['deskripsi'] }}
                </div>
            @endif

            <div style="display:flex;gap:1rem">
                <a href="{{ route('gateway.motors') }}" class="btn btn-secondary">← Katalog</a>
            </div>
        </div>
    </div>
@endsection
