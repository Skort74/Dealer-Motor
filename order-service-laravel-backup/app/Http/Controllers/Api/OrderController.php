<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MotorServiceClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * GET /api/orders
     * Daftar semua transaksi
     */
    public function index(): JsonResponse
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi berhasil diambil',
            'data' => $orders,
        ]);
    }

    /**
     * GET /api/orders/{id}
     * Detail satu transaksi
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * POST /api/orders
     * Buat pesanan baru — CONSUMER: verifikasi stok ke MotorService dulu
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'motor_id' => 'required|integer',
            'nama_pelanggan' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $client = new MotorServiceClient();

        // Step 1: Verifikasi stok ke MotorService
        $stokData = $client->cekStok($request->motor_id);

        if (!$stokData || !isset($stokData['success']) || !$stokData['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi MotorService untuk verifikasi stok. Pastikan MotorService berjalan di port 8001.',
            ], 503);
        }

        $motorInfo = $stokData['data'];

        if (!$motorInfo['tersedia'] || $motorInfo['stok'] < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok motor tidak mencukupi',
                'data' => [
                    'motor' => $motorInfo['nama'],
                    'stok_tersedia' => $motorInfo['stok'],
                    'jumlah_diminta' => $request->jumlah,
                ],
            ], 400);
        }

        // Step 2: Buat order
        $order = Order::create([
            'kode_order' => Order::generateKodeOrder(),
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'motor_id' => $request->motor_id,
            'motor_nama' => $motorInfo['nama'],
            'motor_merk' => $motorInfo['merk'],
            'harga' => $motorInfo['harga'],
            'jumlah' => $request->jumlah,
            'total' => $motorInfo['harga'] * $request->jumlah,
            'status' => 'confirmed',
            'catatan' => $request->catatan,
        ]);

        // Step 3: Kurangi stok di MotorService
        $kurangiResult = $client->kurangiStok($request->motor_id, $request->jumlah);

        if (!$kurangiResult) {
            $order->update(['status' => 'pending', 'catatan' => ($order->catatan ?? '') . ' [Stok belum dikurangi - gagal komunikasi]']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat! Stok motor telah diperbarui.',
            'data' => $order,
        ], 201);
    }

    /**
     * GET /api/orders/statistics
     * Statistik penjualan — PROVIDER: dikonsumsi oleh MotorService
     */
    public function statistics(): JsonResponse
    {
        $totalTransaksi = Order::where('status', 'confirmed')->count();
        $totalPendapatan = Order::where('status', 'confirmed')->sum('total');

        // Motor terlaris: group by motor_id, hitung total terjual
        $terlaris = Order::where('status', 'confirmed')
            ->selectRaw('motor_id, motor_nama, motor_merk, SUM(jumlah) as total_terjual, COUNT(*) as total_transaksi')
            ->groupBy('motor_id', 'motor_nama', 'motor_merk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Statistik penjualan berhasil diambil',
            'data' => [
                'total_transaksi' => $totalTransaksi,
                'total_pendapatan' => $totalPendapatan,
                'terlaris' => $terlaris,
            ],
        ]);
    }
}
