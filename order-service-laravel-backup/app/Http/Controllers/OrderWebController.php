<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MotorServiceClient;
use Illuminate\Http\Request;

class OrderWebController extends Controller
{
    /**
     * Dashboard - ringkasan transaksi
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        $totalConfirmed = Order::where('status', 'confirmed')->count();
        $totalPendapatan = Order::where('status', 'confirmed')->sum('total');

        return view('orders.index', compact('orders', 'totalConfirmed', 'totalPendapatan'));
    }

    /**
     * Form pemesanan baru - ambil data motor dari MotorService
     */
    public function create(Request $request)
    {
        $client = new MotorServiceClient();
        $motorsData = $client->getAllMotors();

        $motors = [];
        $selectedMotor = null;
        $error = null;

        if ($motorsData && isset($motorsData['success']) && $motorsData['success']) {
            $motors = $motorsData['data'];
            if ($request->has('motor_id')) {
                $motorDetail = $client->getMotor($request->motor_id);
                if ($motorDetail && isset($motorDetail['success']) && $motorDetail['success']) {
                    $selectedMotor = $motorDetail['data'];
                }
            }
        } else {
            $error = 'Gagal mengambil data motor dari MotorService. Pastikan MotorService berjalan di port 8001.';
        }

        return view('orders.create', compact('motors', 'selectedMotor', 'error'));
    }

    /**
     * Simpan pesanan baru
     */
    public function store(Request $request)
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

        // Verifikasi stok ke MotorService
        $stokData = $client->cekStok($request->motor_id);

        if (!$stokData || !isset($stokData['success']) || !$stokData['success']) {
            return redirect()->back()->withInput()
                ->with('error', 'Gagal menghubungi MotorService untuk verifikasi stok.');
        }

        $motorInfo = $stokData['data'];

        if (!$motorInfo['tersedia'] || $motorInfo['stok'] < $request->jumlah) {
            return redirect()->back()->withInput()
                ->with('error', "Stok motor {$motorInfo['nama']} tidak mencukupi. Tersedia: {$motorInfo['stok']} unit.");
        }

        // Buat order
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

        // Kurangi stok di MotorService
        $client->kurangiStok($request->motor_id, $request->jumlah);

        return redirect()->route('orders.show', $order->id)
            ->with('success', "Pesanan {$order->kode_order} berhasil dibuat!");
    }

    /**
     * Detail transaksi
     */
    public function show(int $id)
    {
        $order = Order::findOrFail($id);
        return view('orders.show', compact('order'));
    }
}
