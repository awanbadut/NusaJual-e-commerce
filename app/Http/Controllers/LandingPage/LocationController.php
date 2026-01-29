<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    private function fetchApi($endpoint, $params = [])
    {
        $url = env('API_CO_ID_BASE_URL') . $endpoint;

        $response = Http::withHeaders([
            'x-api-co-id' => env('API_CO_ID_KEY')
        ])->get($url, $params);

        // --- DEBUGGING ERROR ---
        if ($response->failed()) {
            // Tampilkan Status Code & Isi Pesan Errornya
            dd([
                'Status' => $response->status(),
                'URL' => $url,
                'Body' => $response->body() // Ini akan menampilkan pesan error asli dari server
            ]);
        }

        return $response->json()['data'] ?? [];
    }
    public function getProvinces()
    {
        return $this->fetchApi('/provinces');
    }

    public function getCities($provinceCode)
    {
        return $this->fetchApi('/regencies', ['province_code' => $provinceCode]);
    }

    public function getDistricts($cityCode)
    {
        return $this->fetchApi('/districts', ['regency_code' => $cityCode]);
    }

    public function getVillages($districtCode)
    {
        return $this->fetchApi('/villages', ['district_code' => $districtCode]);
    }
}
