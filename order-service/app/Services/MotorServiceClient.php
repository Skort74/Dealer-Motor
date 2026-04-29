<?php

namespace App\Services;

use Config\Services;

class MotorServiceClient
{
    protected $baseUrl = 'http://localhost:8001/api';

    /**
     * Make a GET request to MotorService
     */
    private function get(string $path)
    {
        $client = Services::curlrequest();
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        return $client->get($url, ['timeout' => 5]);
    }

    /**
     * Make a PUT request to MotorService
     */
    private function put(string $path, array $data)
    {
        $client = Services::curlrequest();
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        return $client->put($url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode($data),
            'timeout' => 5,
        ]);
    }

    /**
     * Check stock of a motor by ID via MotorService
     * MotorService returns: { "success": true, "data": { "motor_id":..., "stok":..., "harga":..., "nama":..., "merk":... } }
     */
    public function checkStock($motorId, $requestedQuantity)
    {
        try {
            $response = $this->get("motors/{$motorId}/stock");
            
            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                $data = $body['data'] ?? $body;
                
                if (isset($data['stok']) && $data['stok'] >= $requestedQuantity) {
                    return [
                        'success' => true,
                        'motor' => $data
                    ];
                }
                return [
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Sisa stok: ' . ($data['stok'] ?? 0)
                ];
            }
            return ['success' => false, 'message' => 'Gagal verifikasi stok'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'MotorService tidak dapat dihubungi: ' . $e->getMessage()];
        }
    }

    /**
     * Decrease stock of a motor by ID via MotorService
     * MotorService expects PUT with JSON body: { "jumlah": N }
     */
    public function decreaseStock($motorId, $quantity)
    {
        try {
            $response = $this->put("motors/{$motorId}/stock", ['jumlah' => $quantity]);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Increase stock of a motor (restore stock on cancel/edit)
     * Uses negative jumlah to increase stock via the same PUT endpoint
     */
    public function increaseStock($motorId, $quantity)
    {
        try {
            // Use negative value to increase stock
            $response = $this->put("motors/{$motorId}/stock", ['jumlah' => -$quantity]);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get all motors from MotorService
     * MotorService returns: { "success": true, "data": [ ... ] }
     */
    public function getMotors()
    {
        try {
            $response = $this->get("motors");
            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                return $body['data'] ?? [];
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
