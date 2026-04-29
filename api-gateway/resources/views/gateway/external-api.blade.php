@extends('layouts.app')

@section('title', 'External API - Data Motor dari API Ninjas')

@section('content')
    <div class="page-header">
        <div class="gateway-badge">🌐 Gateway → MotorService :8001 → API Ninjas (api-ninjas.com)</div>
        <h1>🌐 External API — Data Motor</h1>
        <p>Data jenis motor, tahun, dan spesifikasi dari <strong>API Ninjas Motorcycles API</strong> (Public API)</p>
    </div>

    @if($error)
        <div class="alert alert-error">
            ❌ {{ $error }}
            <br><small>Pastikan API key sudah diset di file <code>motor-service/.env</code> → <code>API_NINJAS_KEY=your_key</code></small>
        </div>
    @endif

    {{-- FILTER --}}
    <form method="GET" action="{{ route('gateway.external') }}" class="filter-bar" style="margin-bottom:1.5rem">
        <a href="{{ route('gateway.external', ['make' => 'Honda']) }}" class="btn {{ $make == 'Honda' ? 'btn-honda' : 'btn-secondary' }} btn-sm">🔴 Honda</a>
        <a href="{{ route('gateway.external', ['make' => 'Yamaha']) }}" class="btn {{ $make == 'Yamaha' ? 'btn-yamaha' : 'btn-secondary' }} btn-sm">🔵 Yamaha</a>
        <a href="{{ route('gateway.external', ['make' => 'Kawasaki']) }}" class="btn {{ $make == 'Kawasaki' ? 'btn-primary' : 'btn-secondary' }} btn-sm">🟢 Kawasaki</a>
        <a href="{{ route('gateway.external', ['make' => 'Suzuki']) }}" class="btn {{ $make == 'Suzuki' ? 'btn-accent' : 'btn-secondary' }} btn-sm">🟡 Suzuki</a>

        <div style="flex:1"></div>

        <select name="make" class="form-control" style="width:auto;display:inline-block">
            <option value="Honda" {{ $make == 'Honda' ? 'selected' : '' }}>Honda</option>
            <option value="Yamaha" {{ $make == 'Yamaha' ? 'selected' : '' }}>Yamaha</option>
            <option value="Kawasaki" {{ $make == 'Kawasaki' ? 'selected' : '' }}>Kawasaki</option>
            <option value="Suzuki" {{ $make == 'Suzuki' ? 'selected' : '' }}>Suzuki</option>
        </select>
        <input type="number" name="year" class="form-control" style="width:120px;display:inline-block" placeholder="Tahun" value="{{ $year }}">
        <button type="submit" class="btn btn-primary btn-sm">🔍 Cari</button>
    </form>

    {{-- API INFO --}}
    <div style="margin-bottom:1.5rem;padding:1rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <span class="gateway-badge" style="margin:0">⚡ API Call</span>
        <span style="font-family:monospace;font-size:0.85rem;color:var(--secondary)">
            Gateway :8000 → MotorService :8001 → <span style="color:var(--accent)">api-ninjas.com/v1/motorcycles?make={{ $make }}{{ $year ? "&year={$year}" : '' }}</span>
        </span>
        <span style="font-size:0.75rem;color:var(--text-muted);margin-left:auto">Source: {{ $source ?? 'API Ninjas' }}</span>
    </div>

    {{-- RESULTS --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
        <h2 style="font-size:1.1rem;font-weight:700">📋 Hasil: {{ count($motorcycles) }} motor {{ $make }} {{ $year ? "tahun {$year}" : '' }}</h2>
    </div>

    @if(count($motorcycles) > 0)
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Type</th>
                        <th>Engine</th>
                        <th>Power</th>
                        <th>Torque</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($motorcycles as $idx => $mc)
                        <tr>
                            <td style="color:var(--text-muted)">{{ $idx + 1 }}</td>
                            <td style="font-weight:600;color:var(--primary-light)">{{ $mc['make'] ?? '-' }}</td>
                            <td style="font-weight:600">{{ $mc['model'] ?? '-' }}</td>
                            <td>{{ $mc['year'] ?? '-' }}</td>
                            <td><span class="card-tag">{{ $mc['type'] ?? '-' }}</span></td>
                            <td style="font-size:0.8rem">{{ $mc['engine'] ?? '-' }}</td>
                            <td style="font-size:0.8rem;color:var(--accent)">{{ $mc['power'] ?? '-' }}</td>
                            <td style="font-size:0.8rem">{{ $mc['torque'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align:center;padding:4rem;color:var(--text-muted);background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border)">
            <p style="font-size:3rem;margin-bottom:1rem">🌐</p>
            <p style="font-size:1.1rem;margin-bottom:0.5rem">Tidak ada data ditemukan</p>
            <p style="font-size:0.85rem">Pastikan <code>API_NINJAS_KEY</code> sudah diset di <code>motor-service/.env</code></p>
            <p style="font-size:0.85rem;margin-top:0.5rem">Daftar gratis di: <a href="https://api-ninjas.com/register" target="_blank" style="color:var(--primary-light)">api-ninjas.com/register</a></p>
        </div>
    @endif

    {{-- API ENDPOINTS INFO --}}
    <div class="detail-card" style="margin-top:2rem">
        <h3 style="margin-bottom:1rem">📡 External API Endpoints (via Gateway)</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:0.75rem">
            <div style="padding:0.75rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.65rem;color:#fca5a5;text-transform:uppercase;margin-bottom:2px">Honda</div>
                <div style="font-family:monospace;font-size:0.8rem;color:var(--secondary)">GET /api/external/motorcycles/honda</div>
            </div>
            <div style="padding:0.75rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.65rem;color:#93c5fd;text-transform:uppercase;margin-bottom:2px">Yamaha</div>
                <div style="font-family:monospace;font-size:0.8rem;color:var(--secondary)">GET /api/external/motorcycles/yamaha</div>
            </div>
            <div style="padding:0.75rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.65rem;color:#6ee7b7;text-transform:uppercase;margin-bottom:2px">Kawasaki</div>
                <div style="font-family:monospace;font-size:0.8rem;color:var(--secondary)">GET /api/external/motorcycles/kawasaki</div>
            </div>
            <div style="padding:0.75rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.65rem;color:#fcd34d;text-transform:uppercase;margin-bottom:2px">Suzuki</div>
                <div style="font-family:monospace;font-size:0.8rem;color:var(--secondary)">GET /api/external/motorcycles/suzuki</div>
            </div>
            <div style="padding:0.75rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.65rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:2px">Custom Filter</div>
                <div style="font-family:monospace;font-size:0.8rem;color:var(--secondary)">GET /api/external/motorcycles?make=X&year=Y</div>
            </div>
            <div style="padding:0.75rem;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius)">
                <div style="font-size:0.65rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:2px">Semua Brand</div>
                <div style="font-family:monospace;font-size:0.8rem;color:var(--secondary)">GET /api/external/motorcycles/all-brands</div>
            </div>
        </div>
        <div style="margin-top:1rem;font-size:0.8rem;color:var(--text-muted)">
            <strong>Alur:</strong> Client → API Gateway :8000 → MotorService :8001 → API Ninjas (api-ninjas.com) → Response kembali
        </div>
    </div>
@endsection
