@extends('layouts.app')

@section('title', $motor->nama . ' - Dealer Motor')

@section('content')
    <a href="{{ route('motors.index') }}" class="back-link">← Kembali ke Katalog</a>

    <div class="detail-container">
        <div class="detail-image">
            @if($motor->is_terlaris)
                <span class="badge-terlaris" style="position:absolute;top:16px;right:16px;background:linear-gradient(135deg,#ef4444,#f97316);color:#fff;padding:8px 18px;border-radius:20px;font-size:0.85rem;font-weight:700;z-index:2">🔥 Motor Terlaris</span>
            @endif
            <span style="font-size:6rem">🏍️</span>
        </div>

        <div class="detail-info">
            <div>
                <div class="card-meta" style="margin-bottom:0.5rem">
                    <span class="card-tag">{{ $motor->merk }}</span>
                    <span class="card-tag">{{ $motor->tipe }}</span>
                    <span class="card-tag">{{ $motor->tahun }}</span>
                </div>
                <h1 class="detail-title">{{ $motor->nama }}</h1>
            </div>

            <div class="detail-price">Rp {{ number_format($motor->harga, 0, ',', '.') }}</div>

            <div class="detail-specs">
                <div class="spec-item">
                    <div class="spec-label">Merk</div>
                    <div class="spec-value">{{ $motor->merk }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Tipe</div>
                    <div class="spec-value">{{ $motor->tipe }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Tahun</div>
                    <div class="spec-value">{{ $motor->tahun }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Warna</div>
                    <div class="spec-value">{{ $motor->warna }}</div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Stok</div>
                    <div class="spec-value">
                        @if($motor->stok > 5)
                            <span style="color:#6ee7b7">{{ $motor->stok }} unit ✓</span>
                        @elseif($motor->stok > 0)
                            <span style="color:#fcd34d">{{ $motor->stok }} unit ⚠️</span>
                        @else
                            <span style="color:#fca5a5">Habis ✕</span>
                        @endif
                    </div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Status</div>
                    <div class="spec-value">
                        @if($motor->is_terlaris)
                            <span style="color:#f97316">🔥 Terlaris</span>
                        @else
                            <span style="color:var(--text-secondary)">Regular</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($motor->deskripsi)
                <div class="detail-desc">
                    <strong style="color:var(--text-primary)">Deskripsi</strong><br><br>
                    {{ $motor->deskripsi }}
                </div>
            @endif

            <div style="display:flex;gap:1rem;margin-top:0.5rem">
                <a href="http://localhost:8002/orders/create?motor_id={{ $motor->id }}" class="btn btn-primary" target="_blank">🛒 Pesan di OrderService</a>
                <a href="{{ route('motors.index') }}" class="btn btn-secondary">← Katalog</a>
            </div>
        </div>
    </div>

    <div style="margin-top:3rem;padding:1.5rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg)">
        <h3 style="margin-bottom:1rem;color:var(--text-primary)">📡 API Endpoint (Provider)</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div class="spec-item">
                <div class="spec-label">Detail Motor</div>
                <div class="spec-value" style="font-family:monospace;font-size:0.85rem;color:var(--secondary)">GET /api/motors/{{ $motor->id }}</div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Cek Stok</div>
                <div class="spec-value" style="font-family:monospace;font-size:0.85rem;color:var(--secondary)">GET /api/motors/{{ $motor->id }}/stock</div>
            </div>
        </div>
    </div>
@endsection
