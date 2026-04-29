<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MotorcycleApiClient
{
    private string $baseUrl = 'https://api.api-ninjas.com/v1';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.api_ninjas.key', '');
    }

    /**
     * Cari motor berdasarkan merk (make)
     * GET /v1/motorcycles?make={make}
     */
    public function getByMake(string $make, ?int $year = null): ?array
    {
        $cacheKey = "motorcycles_{$make}" . ($year ? "_{$year}" : '');

        return Cache::remember($cacheKey, 3600, function () use ($make, $year) {
            try {
                $params = ['make' => $make];
                if ($year) {
                    $params['year'] = $year;
                }

                $response = Http::withHeaders([
                    'X-Api-Key' => $this->apiKey,
                ])->timeout(15)->get("{$this->baseUrl}/motorcycles", $params);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'source' => 'API Ninjas (api-ninjas.com)',
                        'make' => $make,
                        'year' => $year,
                        'count' => count($response->json()),
                        'data' => $response->json(),
                    ];
                }

                Log::warning('API Ninjas: Request gagal', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal mengambil data dari API Ninjas. Status: ' . $response->status(),
                ];
            } catch (\Exception $e) {
                Log::error('API Ninjas: Connection error', ['error' => $e->getMessage()]);
                return [
                    'success' => false,
                    'message' => 'Tidak dapat terhubung ke API Ninjas: ' . $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Cari motor berdasarkan model
     * GET /v1/motorcycles?model={model}
     */
    public function getByModel(string $model, ?string $make = null): ?array
    {
        $cacheKey = "motorcycles_model_{$model}" . ($make ? "_{$make}" : '');

        return Cache::remember($cacheKey, 3600, function () use ($model, $make) {
            try {
                $params = ['model' => $model];
                if ($make) {
                    $params['make'] = $make;
                }

                $response = Http::withHeaders([
                    'X-Api-Key' => $this->apiKey,
                ])->timeout(15)->get("{$this->baseUrl}/motorcycles", $params);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'source' => 'API Ninjas (api-ninjas.com)',
                        'model' => $model,
                        'make' => $make,
                        'count' => count($response->json()),
                        'data' => $response->json(),
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Gagal mengambil data: ' . $response->status(),
                ];
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Cari motor berdasarkan merk dan tahun
     */
    public function getByMakeAndYear(string $make, int $year): ?array
    {
        return $this->getByMake($make, $year);
    }

    /**
     * Ambil data motor dari semua 4 merk sekaligus
     */
    public function getAllBrands(?int $year = null): array
    {
        $brands = ['Honda', 'Yamaha', 'Kawasaki', 'Suzuki'];
        $result = [];

        foreach ($brands as $brand) {
            $data = $this->getByMake($brand, $year);
            $result[$brand] = $data;
        }

        return [
            'success' => true,
            'source' => 'API Ninjas (api-ninjas.com)',
            'year' => $year,
            'brands' => $result,
        ];
    }
}
