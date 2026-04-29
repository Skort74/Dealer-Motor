<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Services\OrderServiceClient;
use App\Services\MotorcycleApiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    /**
     * GET /api/motors
     * Menampilkan daftar semua motor (dengan filter opsional)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Motor::query();

        // Filter berdasarkan merk
        if ($request->has('merk') && $request->merk) {
            $query->where('merk', $request->merk);
        }

        // Filter berdasarkan tipe
        if ($request->has('tipe') && $request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan keyword pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('tipe', 'like', "%{$search}%");
            });
        }

        $motors = $query->orderBy('is_terlaris', 'desc')->orderBy('nama')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar motor berhasil diambil',
            'data' => $motors,
        ]);
    }

    /**
     * GET /api/motors/{id}
     * Menampilkan detail satu motor
     */
    public function show(int $id): JsonResponse
    {
        $motor = Motor::find($id);

        if (!$motor) {
            return response()->json([
                'success' => false,
                'message' => 'Motor tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail motor berhasil diambil',
            'data' => $motor,
        ]);
    }

    /**
     * GET /api/motors/{id}/stock
     * Cek ketersediaan stok motor (diakses oleh OrderService)
     */
    public function checkStock(int $id): JsonResponse
    {
        $motor = Motor::find($id);

        if (!$motor) {
            return response()->json([
                'success' => false,
                'message' => 'Motor tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'motor_id' => $motor->id,
                'nama' => $motor->nama,
                'merk' => $motor->merk,
                'stok' => $motor->stok,
                'harga' => $motor->harga,
                'tersedia' => $motor->stok > 0,
            ],
        ]);
    }

    /**
     * PUT /api/motors/{id}/stock
     * Update (kurangi) stok motor setelah order berhasil (diakses oleh OrderService)
     */
    public function updateStock(Request $request, int $id): JsonResponse
    {
        $motor = Motor::find($id);

        if (!$motor) {
            return response()->json([
                'success' => false,
                'message' => 'Motor tidak ditemukan',
            ], 404);
        }

        $jumlah = $request->input('jumlah', 1);

        if ($motor->stok < $jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi',
                'data' => [
                    'stok_tersedia' => $motor->stok,
                    'jumlah_diminta' => $jumlah,
                ],
            ], 400);
        }

        $motor->stok -= $jumlah;
        $motor->save();

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui',
            'data' => [
                'motor_id' => $motor->id,
                'nama' => $motor->nama,
                'stok_baru' => $motor->stok,
            ],
        ]);
    }

    /**
     * POST /api/motors/update-terlaris
     * Mengambil statistik dari OrderService dan update label motor terlaris
     */
    public function updateTerlaris(): JsonResponse
    {
        $client = new OrderServiceClient();
        $statistik = $client->getStatistikPenjualan();

        if (!$statistik || !isset($statistik['success']) || !$statistik['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data statistik dari OrderService. Pastikan OrderService berjalan di port 8002.',
            ], 503);
        }

        // Reset semua label terlaris
        Motor::where('is_terlaris', true)->update(['is_terlaris' => false]);

        // Set motor terlaris berdasarkan statistik
        $terlaris = $statistik['data']['terlaris'] ?? [];

        $updatedMotors = [];
        foreach ($terlaris as $item) {
            $motor = Motor::find($item['motor_id']);
            if ($motor) {
                $motor->is_terlaris = true;
                $motor->save();
                $updatedMotors[] = [
                    'motor_id' => $motor->id,
                    'nama' => $motor->nama,
                    'total_terjual' => $item['total_terjual'],
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Label motor terlaris berhasil diperbarui berdasarkan data OrderService',
            'data' => [
                'motor_terlaris' => $updatedMotors,
            ],
        ]);
    }

    // ========================================
    // EXTERNAL API — API Ninjas Motorcycles
    // ========================================

    /**
     * GET /api/external/motorcycles?make={make}&year={year}
     * Mengambil data motor dari Public API (API Ninjas)
     */
    public function externalMotorcycles(Request $request): JsonResponse
    {
        $make = $request->input('make', 'Honda');
        $year = $request->input('year');

        $client = new MotorcycleApiClient();
        $result = $client->getByMake($make, $year ? (int) $year : null);

        if (!$result || !$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Gagal mengambil data dari API Ninjas',
                'info' => 'Pastikan API key sudah diset di .env (API_NINJAS_KEY)',
            ], 503);
        }

        return response()->json($result);
    }

    /**
     * GET /api/external/motorcycles/honda
     * Data motor Honda dari API Ninjas
     */
    public function externalHonda(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $client = new MotorcycleApiClient();
        $result = $client->getByMake('Honda', $year ? (int) $year : null);

        return response()->json($result ?? ['success' => false, 'message' => 'Gagal mengambil data']);
    }

    /**
     * GET /api/external/motorcycles/yamaha
     * Data motor Yamaha dari API Ninjas
     */
    public function externalYamaha(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $client = new MotorcycleApiClient();
        $result = $client->getByMake('Yamaha', $year ? (int) $year : null);

        return response()->json($result ?? ['success' => false, 'message' => 'Gagal mengambil data']);
    }

    /**
     * GET /api/external/motorcycles/kawasaki
     * Data motor Kawasaki dari API Ninjas
     */
    public function externalKawasaki(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $client = new MotorcycleApiClient();
        $result = $client->getByMake('Kawasaki', $year ? (int) $year : null);

        return response()->json($result ?? ['success' => false, 'message' => 'Gagal mengambil data']);
    }

    /**
     * GET /api/external/motorcycles/suzuki
     * Data motor Suzuki dari API Ninjas
     */
    public function externalSuzuki(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $client = new MotorcycleApiClient();
        $result = $client->getByMake('Suzuki', $year ? (int) $year : null);

        return response()->json($result ?? ['success' => false, 'message' => 'Gagal mengambil data']);
    }

    /**
     * GET /api/external/motorcycles/all-brands
     * Data dari semua 4 merk sekaligus
     */
    public function externalAllBrands(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $client = new MotorcycleApiClient();
        $result = $client->getAllBrands($year ? (int) $year : null);

        return response()->json($result);
    }
}
