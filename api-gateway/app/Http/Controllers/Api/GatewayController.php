<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    private GatewayService $gateway;

    public function __construct()
    {
        $this->gateway = new GatewayService();
    }

    // ========================================
    // MOTOR SERVICE PROXY ENDPOINTS
    // ========================================

    /**
     * GET /api/motors — Daftar semua motor
     */
    public function getMotors(Request $request): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', '/api/motors', $request->all());
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/motors/honda — Daftar motor Honda saja
     * Mengambil data dari MotorService dengan filter merk=Honda
     */
    public function getMotorsHonda(): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', '/api/motors', ['merk' => 'Honda']);
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/motors/yamaha — Daftar motor Yamaha saja
     * Mengambil data dari MotorService dengan filter merk=Yamaha
     */
    public function getMotorsYamaha(): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', '/api/motors', ['merk' => 'Yamaha']);
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/motors/{id} — Detail motor
     */
    public function getMotor(int $id): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', "/api/motors/{$id}");
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/motors/{id}/stock — Cek stok motor
     */
    public function getMotorStock(int $id): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', "/api/motors/{$id}/stock");
        return response()->json($result['data'], $result['status']);
    }

    /**
     * PUT /api/motors/{id}/stock — Update stok
     */
    public function updateMotorStock(Request $request, int $id): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('PUT', "/api/motors/{$id}/stock", $request->all());
        return response()->json($result['data'], $result['status']);
    }

    /**
     * POST /api/motors/update-terlaris — Sync terlaris
     */
    public function updateTerlaris(): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('POST', '/api/motors/update-terlaris');
        return response()->json($result['data'], $result['status']);
    }

    // ========================================
    // ORDER SERVICE PROXY ENDPOINTS
    // ========================================

    /**
     * GET /api/orders — Daftar transaksi
     */
    public function getOrders(): JsonResponse
    {
        $result = $this->gateway->proxyToOrderService('GET', '/api/orders');
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/orders/{id} — Detail transaksi
     */
    public function getOrder(int $id): JsonResponse
    {
        $result = $this->gateway->proxyToOrderService('GET', "/api/orders/{$id}");
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/orders/statistics — Statistik penjualan
     */
    public function getStatistics(): JsonResponse
    {
        $result = $this->gateway->proxyToOrderService('GET', '/api/orders/statistics');
        return response()->json($result['data'], $result['status']);
    }

    /**
     * POST /api/orders — Buat pesanan baru
     */
    public function createOrder(Request $request): JsonResponse
    {
        $result = $this->gateway->proxyToOrderService('POST', '/api/orders', $request->all());
        return response()->json($result['data'], $result['status']);
    }

    // ========================================
    // HEALTH CHECK
    // ========================================

    /**
     * GET /api/health — Status semua service
     */
    public function health(): JsonResponse
    {
        $health = $this->gateway->healthCheck();
        $statusCode = $health['all_healthy'] ? 200 : 503;
        return response()->json($health, $statusCode);
    }

    // ========================================
    // EXTERNAL API (via MotorService → API Ninjas)
    // ========================================

    /**
     * GET /api/external/motorcycles?make={make}&year={year}
     * Proxy ke MotorService → API Ninjas
     */
    public function externalMotorcycles(Request $request): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', '/api/external/motorcycles', $request->all());
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/external/motorcycles/honda
     */
    public function externalHonda(Request $request): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', '/api/external/motorcycles/honda', $request->all());
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/external/motorcycles/yamaha
     */
    public function externalYamaha(Request $request): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', '/api/external/motorcycles/yamaha', $request->all());
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/external/motorcycles/kawasaki
     */
    public function externalKawasaki(Request $request): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', '/api/external/motorcycles/kawasaki', $request->all());
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/external/motorcycles/suzuki
     */
    public function externalSuzuki(Request $request): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', '/api/external/motorcycles/suzuki', $request->all());
        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/external/motorcycles/all-brands
     */
    public function externalAllBrands(Request $request): JsonResponse
    {
        $result = $this->gateway->proxyToMotorService('GET', '/api/external/motorcycles/all-brands', $request->all());
        return response()->json($result['data'], $result['status']);
    }
}
