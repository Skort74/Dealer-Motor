<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.order_service.url', 'http://localhost:8002');
    }

    /**
     * Mengambil statistik penjualan dari OrderService
     * Digunakan untuk menentukan motor terlaris
     */
    public function getStatistikPenjualan(): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/orders/statistics");

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('OrderService: Gagal mengambil statistik penjualan', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OrderService: Tidak dapat terhubung', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Mengambil riwayat transaksi dari OrderService
     */
    public function getRiwayatTransaksi(): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/orders");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('OrderService: Tidak dapat mengambil riwayat transaksi', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
