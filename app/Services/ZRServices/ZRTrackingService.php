<?php

namespace App\Services\ZRServices;

use Illuminate\Support\Facades\Http;

class ZRTrackingService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $tenantId;

    public function __construct($installedApp)
    {
       $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->apiKey = $installedApp->token;
        $this->tenantId = $installedApp->key;
    }

    public function trackOrders(array $trackings)
    {
        $tracking = $trackings[0];
        $response = Http::withHeaders([
            'Accept'    => 'application/json',
            'X-Api-Key' => $this->apiKey,
            'X-Tenant'  => $this->tenantId,
        ])->get("{$this->baseUrl}/get/tracking/info", [
            'tracking' => $tracking
        ]);

        \Log::alert($response);
        if (!$response->successful()) return null;

        $rawData = $response->json();

        return [];

        // الوصول إلى مصفوفة النشاطات (Activity) لأنها هي التي تحتوي على الحالات
        $activity = collect($rawData['activity'] ?? []);

        if ($activity->isEmpty()) {
            return [
                'last_status' => 'pending',
                'history' => []
            ];
        }

        // الحصول على آخر حالة من مصفوفة activity
        $lastStep = $activity->last();

        return [
            'raw_data' => $rawData,
            'last_status' => $this->mapStatus($lastStep['status'] ?? 'unknown'),
            'history' => $activity->map(function($step) {
                return [
                    'status'      => $this->mapStatus($step['status'] ?? 'unknown'),
                    'description' => $step['status'] ?? 'No description',
                    // دمج التاريخ والوقت كما يظهر في رد الـ API الخاص بك
                    'date'        => ($step['date'] ?? '') . ' ' . ($step['time'] ?? ''),
                    'station'     => $step['station'] ?? ''
                ];
            })->toArray()
        ];
    }

  
    private function mapStatus($status)
    {
        $map = [
            'order_information_received_by_carrier' => 'pending',
            'picked'               => 'accepted',
            'accepted_by_carrier'  => 'in_transit',
            'dispatched_to_driver' => 'out_for_delivery',
            'livred'               => 'delivered',
            'encaissed'            => 'collected',
            'payed'                => 'paid',
            'return_asked'         => 'returning',
            'Return_received'      => 'returned',
        ];

        return $map[$status] ?? 'unknown';
    }
}