<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GatewayService
{
    private string $motorServiceUrl;
    private string $orderServiceUrl;

    public function __construct()
    {
        $this->motorServiceUrl = config('services.motor_service.url', 'http://localhost:8001');
        $this->orderServiceUrl = config('services.order_service.url', 'http://localhost:8002');
    }

    /**
     * Proxy request ke MotorService
     */
    public function proxyToMotorService(string $method, string $path, array $data = []): array
    {
        return $this->proxyRequest($this->motorServiceUrl, $method, $path, $data);
    }

    /**
     * Proxy request ke OrderService
     */
    public function proxyToOrderService(string $method, string $path, array $data = []): array
    {
        return $this->proxyRequest($this->orderServiceUrl, $method, $path, $data);
    }

    /**
     * Generic proxy request
     */
    private function proxyRequest(string $baseUrl, string $method, string $path, array $data = []): array
    {
        $url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

        try {
            $response = match (strtoupper($method)) {
                'GET' => Http::timeout(15)->get($url, $data),
                'POST' => Http::timeout(15)->post($url, $data),
                'PUT' => Http::timeout(15)->put($url, $data),
                'DELETE' => Http::timeout(15)->delete($url, $data),
                default => Http::timeout(15)->get($url),
            };

            return [
                'status' => $response->status(),
                'data' => $response->json(),
                'success' => $response->successful(),
            ];
        } catch (\Exception $e) {
            Log::error("Gateway proxy error: {$method} {$url}", [
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 503,
                'data' => [
                    'success' => false,
                    'message' => 'Service tidak tersedia: ' . $e->getMessage(),
                ],
                'success' => false,
            ];
        }
    }

    /**
     * Cek status kedua service
     */
    public function healthCheck(): array
    {
        $motorStatus = $this->checkService($this->motorServiceUrl, 'MotorService');
        $orderStatus = $this->checkService($this->orderServiceUrl, 'OrderService');

        return [
            'gateway' => 'running',
            'services' => [
                'motor_service' => $motorStatus,
                'order_service' => $orderStatus,
            ],
            'all_healthy' => $motorStatus['status'] === 'up' && $orderStatus['status'] === 'up',
        ];
    }

    private function checkService(string $url, string $name): array
    {
        try {
            $response = Http::timeout(5)->get($url . '/up');
            return [
                'name' => $name,
                'url' => $url,
                'status' => $response->successful() ? 'up' : 'down',
                'response_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'name' => $name,
                'url' => $url,
                'status' => 'down',
                'error' => $e->getMessage(),
            ];
        }
    }
}
