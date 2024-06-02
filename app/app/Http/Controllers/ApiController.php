<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function getSignalData()
    {
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

                // Log the filtered data
                Log::info('Filtered API Response: ' . json_encode($filteredData));

                // Optionally return the filtered data
                return response()->json([
                    'status' => 'success',
                    'data' => $filteredData,
                ]);
            } else {
                Log::error('API Request failed with status ' . $response->status());

                return response()->json([
                    'status' => 'error',
                    'message' => 'API request failed',
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('API Request exception: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while making the API request',
            ], 500);
        }
    }
}
