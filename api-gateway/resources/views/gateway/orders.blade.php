@extends('layouts.app')

@section('title', 'Transaksi - API Gateway')

@section('content')
    <div class="page-header">
        <h1>Orders History</h1>
    </div>

    @if($error)
        <div class="alert alert-error">❌ {{ $error }}</div>
    @endif

    <div style="margin-bottom:1.5rem">
        <a href="{{ route('gateway.order.create') }}" class="btn btn-primary clean-btn">Create New Order</a>
    </div>

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
                                <div class="actions-grid">
                                    @if(!empty($order['can_edit']))
                                        <a href="{{ route('gateway.order.edit', $order['id']) }}" class="btn btn-primary btn-sm clean-btn" style="padding:4px 8px;font-size:0.75rem">Edit</a>
                                        <form method="POST" action="{{ route('gateway.order.cancel', $order['id']) }}" style="display:inline" onsubmit="return confirm('Cancel order {{ $order['kode_order'] }}? Stock will be restored.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm clean-btn" style="padding:4px 8px;font-size:0.75rem;background:rgba(239,68,68,0.2);color:#fca5a5;border:1px solid rgba(239,68,68,0.3)">Cancel</button>
                                        </form>
                                    @else
                                        <span style="font-size:0.75rem;color:var(--text-muted)">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="table-responsive">
            <div style="text-align:center;padding:4rem;color:var(--text-muted);background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border)">
                <h3 style="margin-bottom:1rem">No transactions yet</h3>
                <a href="{{ route('gateway.order.create') }}" class="btn btn-primary clean-btn">Create First Order</a>
            </div>
        </div>
    @endif
@endsection
