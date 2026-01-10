<?php

namespace App\Services\ZRServices;

use Illuminate\Support\Facades\Http;

class ZRTrackingService
{
    protected string $baseUrl = 'https://app.zrexpress.app';
    protected string $apiKey;
    protected string $tenantId;

    public function __construct()
    {
        $this->apiKey   = config('services.zr.api_key');
        $this->tenantId = config('services.zr.tenant_id');
    }

    public function getTrackingHistory(string $tracking)
{
    $response = Http::withHeaders([
        'Accept'    => 'application/json',
        'X-Api-Key' => $this->apiKey,
        'X-Tenant'  => $this->tenantId,
    ])->get("{$this->baseUrl}/api/v1/get/tracking/info", [
        'tracking' => $tracking
    ]);

    if (!$response->successful()) return null;

    $rawData = $response->json();

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