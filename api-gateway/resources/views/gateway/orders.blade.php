@extends('layouts.app')

@section('title', 'Transaksi - Admin')

@section('content')
    <div class="page-header">
        <h1>📋 Riwayat Transaksi</h1>
        <p>Semua transaksi via Admin</p>
    </div>

    @if($error)
        <div class="alert alert-error">❌ {{ $error }}</div>
    @endif

    @if(count($orders) > 0)
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Kode</th><th>Pelanggan</th><th>Motor</th><th>Jumlah</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="font-weight:600;color:var(--primary-light)">{{ $order['kode_order'] }}</td>
                            <td>{{ $order['nama_pelanggan'] }}</td>
                            <td>{{ $order['motor_nama'] }}@if(!empty($order['motor_merk']))<br><span style="font-size:0.7rem;color:var(--text-muted)">{{ $order['motor_merk'] }}</span>@endif</td>
                            <td>{{ $order['jumlah'] }} unit</td>
                            <td><span class="price-text">Rp {{ number_format($order['total'], 0, ',', '.') }}</span></td>
                            <td><span class="status-badge status-{{ $order['status'] }}">{{ ucfirst($order['status']) }}</span></td>
                            <td style="font-size:0.8rem">{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</td>
                            <td>
                                <div style="display:flex;gap:4px;flex-wrap:nowrap">
                                    @if(!empty($order['can_edit']))
                                        <a href="{{ route('gateway.order.edit', $order['id']) }}" class="btn btn-primary btn-sm" style="padding:4px 10px;font-size:0.75rem">✏️ Edit</a>
                                        <form method="POST" action="{{ route('gateway.order.cancel', $order['id']) }}" style="margin:0" onsubmit="return confirm('Yakin ingin membatalkan pesanan {{ $order['kode_order'] }}? Stok motor akan dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="padding:4px 10px;font-size:0.75rem;background:rgba(239,68,68,0.2);color:#fca5a5;border:1px solid rgba(239,68,68,0.3);cursor:pointer">🗑️ Batal</button>
                                        </form>
                                    @else
                                        <span style="font-size:0.7rem;color:var(--text-muted)">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align:center;padding:4rem;color:var(--text-muted);background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border)">
            <p style="font-size:3rem">📋</p>
            <p style="margin-bottom:1rem">Belum ada transaksi</p>
        </div>
    @endif
@endsection
