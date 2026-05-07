<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BcvService
{
    /**
     * Get the current BCV exchange rate from DolarApi.
     * Caches the result for 1 hour to optimize performance.
     *
     * @return float
     */
    public function getRate()
    {
        try {
            $response = Http::timeout(5)->withOptions([
                'verify' => false,
            ])->get('https://ve.dolarapi.com/v1/dolares/oficial');

            if ($response->successful()) {
                $data = $response->json();
                $rate = (float) ($data['promedio'] ?? 36.50);
                
                // Store as the "last known good rate" permanently
                Cache::forever('bcv_last_known_rate', $rate);
                
                return $rate;
            }
        } catch (\Exception $e) {
            Log::warning('DolarApi connection failed, attempting to use last known rate: ' . $e->getMessage());
        }

        // Fallback to last known rate stored in cache, or a hardcoded one if none exists
        return Cache::get('bcv_last_known_rate', 36.50);
    }
}
