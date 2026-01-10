<?php

namespace App\Services\TerritoryServices;

use Illuminate\Support\Facades\Http;

class ZRTerritoryService
{
    protected string $baseUrl = 'https://api.zrexpress.app/api/v1.0';
    protected string $apiKey;
    protected string $tenantId;

    public function __construct()
    {
        $this->apiKey   = config('services.zr.api_key');
        $this->tenantId = config('services.zr.tenant_id');
    }

    
    public function getEverythingCached()
{
    return cache()->remember('zr_territory_full_map', 86400, function () {
        $allCommunes = collect();
        $allWilayas = collect();
        $page = 1;
        $totalPages = 1;

        // حلقة تكرار لجلب كافة الصفحات
        do {
            $response = Http::withHeaders([
                'Accept'    => 'application/json',
                'X-Api-Key' => $this->apiKey,
                'X-Tenant'  => $this->tenantId,
            ])->post("{$this->baseUrl}/territories/search", [
                'pageNumber' => $page,
                'pageSize'   => 500, // طلب 500 في كل مرة لضمان استقرار السيرفر
                'filters'    => [],
                'orderBy'    => ['name asc'],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $items = collect($data['items'] ?? []);
                
                // تقسيم البيانات القادمة حسب المستوى
                $allWilayas = $allWilayas->merge($items->where('level', 'wilaya'));
                $allCommunes = $allCommunes->merge($items->where('level', 'commune'));

                // تحديث عدد الصفحات الكلي (بناءً على رد السيرفر)
                $totalPages = $data['totalPages'] ?? 1;
                $page++;
            } else {
                break; // توقف في حال حدوث خطأ
            }

        } while ($page <= $totalPages);

        return [
            'wilayas'  => $allWilayas->keyBy(fn($i) => (int)$i['code'])->toArray(),
            'communes' => $allCommunes->groupBy('parentId')->toArray(),
        ];
    });
}


}