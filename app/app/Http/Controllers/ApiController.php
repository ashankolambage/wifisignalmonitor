<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function getSignalData()
    {
        $routerType = "outdoor";

        try {
            if ($routerType == "huawei") {

                $postData = [
                    'cmd' => 250,
                    'method' => 'GET',
                    'sessionId' => ''
                ];

                $response = Http::post('http://192.168.8.1/cgi-bin/lua.cgi', $postData);
            } elseif ($routerType == "outdoor") {

                $response = Http::get('http://192.168.8.1/reqproc/proc_get', [
                    'isTest' => 'false',
                    'cmd' => 'system_status'
                ]);
            }
            

            if ($response->successful()) {
                if ($routerType == "huawei") {
                    $data = $response->json();

                    $filteredData = [
                        'modem_rsrp' => 150 + $data['data']['main_info']['modem_rsrp'] ?? 0,
                        'modem_rssi' => 150 + $data['data']['main_info']['modem_rssi'] ?? 0,
                        'modem_rsrq' => 150 + $data['data']['main_info']['modem_rsrq'] ?? 0,
                        'modem_sinr' => $data['data']['main_info']['modem_sinr'] *10 ?? 0,
    
                        'raw_modem_rsrp' => $data['data']['main_info']['modem_rsrp'] ?? 0,
                        'raw_modem_rssi' => $data['data']['main_info']['modem_rssi'] ?? 0,
                        'raw_modem_rsrq' => $data['data']['main_info']['modem_rsrq'] ?? 0,
                        'raw_modem_sinr' => $data['data']['main_info']['modem_sinr'] ?? 0,
                    ];
    
                    $filteredRawData = [
                        'modem_rsrp' => $data['data']['main_info']['modem_rsrp'] ?? 0,
                        'modem_rssi' => $data['data']['main_info']['modem_rssi'] ?? 0,
                        'modem_rsrq' => $data['data']['main_info']['modem_rsrq'] ?? 0,
                        'modem_sinr' => $data['data']['main_info']['modem_sinr'] ?? 0,
                    ];
                } elseif ($routerType == "outdoor") {
                    $data = json_decode($response, true);

                    $filteredData = [
                        'modem_rsrp' => 150 + $data['rsrp'] ?? 0,
                        'modem_rssi' => 150 + $data['rssi'] ?? 0,
                        'modem_rsrq' => 150 + $data['rsrq'] ?? 0,
                        'modem_sinr' => $data['sinr'] *10 ?? 0,
    
                        'raw_modem_rsrp' => $data['rsrp'] ?? 0,
                        'raw_modem_rssi' => $data['rssi'] ?? 0,
                        'raw_modem_rsrq' => $data['rsrq'] ?? 0,
                        'raw_modem_sinr' => $data['sinr'] ?? 0,
                    ];
    
                    $filteredRawData = [
                        'modem_rsrp' => $data['rsrp'] ?? 0,
                        'modem_rssi' => $data['rssi'] ?? 0,
                        'modem_rsrq' => $data['rsrq'] ?? 0,
                        'modem_sinr' => $data['sinr'] ?? 0,
                    ];
                }

                Log::info(json_encode($filteredRawData));
                return response()->json($filteredData);
            } else {
                Log::error('API Request failed with status # ' . $response->status());
                return response()->json(['error' => 'API request failed'], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('API Request exception: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while making the API request'], 500);
        }
    }
}
