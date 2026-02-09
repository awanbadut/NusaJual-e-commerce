<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    private function fetchApi($endpoint, $params = [])
    {
        $baseUrl = env('API_CO_ID_BASE_URL');
        $apiKey = env('API_CO_ID_KEY');
        $url = $baseUrl . $endpoint;
        
        $cacheKey = 'location_' . md5($endpoint . json_encode($params));
        
        return Cache::remember($cacheKey, 86400, function () use ($url, $apiKey, $params) {
            try {
                $response = Http::withHeaders([
                    'x-api-co-id' => $apiKey,
                    'Accept' => 'application/json'
                ])->timeout(15)->get($url, $params);

                if ($response->failed()) {
                    \Log::error('Location API Error', [
                        'Status' => $response->status(),
                        'URL' => $url,
                        'Body' => $response->body()
                    ]);
                    return [];
                }

                $data = $response->json();
                return $data['data'] ?? [];
                
            } catch (\Exception $e) {
                \Log::error('Location API Exception', [
                    'Message' => $e->getMessage(),
                    'URL' => $url
                ]);
                return [];
            }
        });
    }
    
    public function getProvinces()
    {
        // Endpoint: /regional/indonesia/provinces
        $data = $this->fetchApi('/regional/indonesia/provinces');
        return response()->json($data);
    }

    public function getCities($provinceCode)
    {
        // Endpoint: /regional/indonesia/provinces/{code}/regencies
        $data = $this->fetchApi("/regional/indonesia/provinces/{$provinceCode}/regencies");
        return response()->json($data);
    }

    public function getDistricts($cityCode)
    {
        // Endpoint: /regional/indonesia/regencies/{code}/districts
        $data = $this->fetchApi("/regional/indonesia/regencies/{$cityCode}/districts");
        return response()->json($data);
    }

    public function getVillages($districtCode)
    {
        // Endpoint: /regional/indonesia/districts/{code}/villages
        $data = $this->fetchApi("/regional/indonesia/districts/{$districtCode}/villages");
        return response()->json($data);
    }
}
