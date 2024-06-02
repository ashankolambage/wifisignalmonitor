<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    private $maxIterations = 20;

    public function getSignalData()
    {
        $this->fetchAndLogSignalData(0);
    }

    private function fetchAndLogSignalData($iteration)
    {
        if ($iteration >= $this->maxIterations) {
            Log::info('Reached max iterations.');
            return;
        }

        $url = 'http://192.168.8.1/cgi-bin/lua.cgi';

        $postData = [
            'cmd' => 250,
            'method' => 'GET',
            'sessionId' => ''
        ];

        try {
            $response = Http::post($url, $postData);

            if ($response->successful()) {
                $data = $response->json();
                $filteredData = [
                    'modem_rsrp' => $data['data']['main_info']['modem_rsrp'] ?? null,
                    'modem_rssi' => $data['data']['main_info']['modem_rssi'] ?? null,
                    'modem_rsrq' => $data['data']['main_info']['modem_rsrq'] ?? null,
                    'modem_sinr' => $data['data']['main_info']['modem_sinr'] ?? null,
                ];

                Log::info('Filtered API Response: ' . json_encode($filteredData));
            } else {
                Log::error('API Request failed with status ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('API Request exception: ' . $e->getMessage());
        }

        $this->fetchAndLogSignalData($iteration + 1);
    }
}
