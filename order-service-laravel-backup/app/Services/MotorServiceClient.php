<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MotorServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.motor_service.url', 'http://localhost:8001');
    }

    /**
     * Mengambil daftar semua motor dari MotorService
     */
    public function getAllMotors(): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/motors");
            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            Log::error('MotorService: Tidak dapat terhubung', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Mengambil detail satu motor dari MotorService
     */
    public function getMotor(int $motorId): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/motors/{$motorId}");
            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            Log::error('MotorService: Gagal ambil detail motor', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Cek ketersediaan stok motor dari MotorService
     */
    public function cekStok(int $motorId): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/motors/{$motorId}/stock");
            if ($response->successful()) {
                return $response->json();
            }
            Log::warning('MotorService: Gagal cek stok', ['status' => $response->status()]);
            return null;
        } catch (\Exception $e) {
            Log::error('MotorService: Tidak dapat cek stok', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Kurangi stok motor di MotorService setelah order berhasil
     */
    public function kurangiStok(int $motorId, int $jumlah): ?array
    {
        try {
            $response = Http::timeout(10)->put("{$this->baseUrl}/api/motors/{$motorId}/stock", [
                'jumlah' => $jumlah,
            ]);
            if ($response->successful()) {
                return $response->json();
            }
            Log::warning('MotorService: Gagal kurangi stok', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('MotorService: Tidak dapat kurangi stok', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
