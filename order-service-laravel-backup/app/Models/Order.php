<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'kode_order',
        'nama_pelanggan',
        'no_telepon',
        'alamat',
        'motor_id',
        'motor_nama',
        'motor_merk',
        'harga',
        'jumlah',
        'total',
        'status',
        'catatan',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Generate kode order unik
     */
    public static function generateKodeOrder(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())->orderBy('id', 'desc')->first();
        $sequence = $lastOrder ? ((int) substr($lastOrder->kode_order, -3)) + 1 : 1;
        return "ORD-{$date}-" . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
