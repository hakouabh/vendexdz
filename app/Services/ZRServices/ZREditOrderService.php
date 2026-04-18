<?php

namespace App\Services\ZRServices;

use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Services\TerritoryServices\ZRTerritoryService;

class ZREditOrderService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $tenantId;
    protected $installedApp;

    public function __construct($installedApp)
    {
        $this->installedApp = $installedApp;
        $this->baseUrl = $installedApp->supportedApp->base_url;
        $this->apiKey = $installedApp->token;
        $this->tenantId = $installedApp->key;
    }

    public function updateOrder(string $tracking, $standardOrder)
    {
        $orderRecord = Order::where('tracking', $tracking)->first();
        if (!$orderRecord || !$orderRecord->custom_id) {
            throw new \Exception("Order or Parcel ID missing for tracking: {$tracking}");
        }

        $parcelId = $orderRecord->custom_id;

        $customer = $this->updateCustomer($parcelId, $standardOrder);
        // 2. Update Delivery Address (PATCH) - FIXED URL
        $address = $this->updateAddress($parcelId, $standardOrder);
        // 3. Update Products & Amount (PUT)
        $products = $this->updateProducts($parcelId, $standardOrder);
        return [
            'success'   => $customer->successful() && $address->successful() && $products->successful(),
            'responses' => [
                'customer' => $customer->json(),
                'address'  => $address->json(),
                'products' => $products->json(),
            ],
            'errors' => $this->collectErrors($customer, $address, $products)
        ];
    }

    protected function updateCustomer(string $parcelId, $order)
    {
        return Http::withHeaders($this->getHeaders())
            ->patch("{$this->baseUrl}/parcels/{$parcelId}/customer", [
                "parcelId" => $parcelId,
                "name"     => $order->name,
                "phone"    => $this->formatPhone($order->phone)
            ]);
    }

    protected function updateAddress(string $parcelId, $order)
    {
        $territory = new ZRTerritoryService($this->installedApp);
        $data = $territory->getEverythingCached();

        $zrWilaya = $data['wilayas'][(int)$order->wilaya] ?? null;
        $zrCommune = collect($data['communes'][$zrWilaya['id']] ?? [])->firstWhere('name', $order->city);

        if (!$zrWilaya || !$zrCommune) {
            throw new \Exception("Territory UUIDs not found for Wilaya ID: {$order->wilaya}");
        }

        // URL changed from /address to /deliveryAddress based on your docs
        return Http::withHeaders($this->getHeaders())
            ->patch("{$this->baseUrl}/parcels/{$parcelId}/deliveryAddress", 
            [
                "parcelId"           => $parcelId,
                'deliveryAddress' => [
                    "street"             => $order->address,
                    "city"               => $zrWilaya['name'],
                    "cityTerritoryId"    => $zrWilaya['id'],
                    "district"           => $zrCommune['name'],
                    "districtTerritoryId" => $zrCommune['id'],
                    "country"            => "algeria"
                ]
            ]);
    }

    protected function updateProducts(string $parcelId, $standardOrder)
    { 
        $order_id = str_replace('VN-', '', $standardOrder->ref);

        $order = Order::with(['items'])->find($order_id);
        $orderedProducts = $order->items->map(function($item){
            return [
                "productSku"  => $item->variant ? $item->variant->sku : null,
                "productName" => $item->product->nickname??$item->product->name,
                "unitPrice"   => $item->product->price,
                "quantity"    => $item->quantity,
                "stockType"   => "none"
            ];
        })->toArray();
        $payload = [
            "parcelId"    => $parcelId,
            "description" => $standardOrder->product_name, // Mandatory
            "amount"      => (double) $standardOrder->total_price, // Optional, but recommended to keep synced
            "orderedProducts" => $orderedProducts
        ];

        // The documentation example shows -X PUT even though the header says PATCH
        // We will use PUT as per the cURL example to ensure compatibility
        return Http::withHeaders($this->getHeaders())
            ->patch("{$this->baseUrl}/parcels/{$parcelId}/products", $payload);
    }

    protected function getHeaders(): array
    {
        return [
            'Accept'    => 'application/json',
            'X-Api-Key' => $this->apiKey,
            'X-Tenant'  => $this->tenantId,
        ];
    }

    private function collectErrors($res1, $res2, $res3): array
    {
        $errors = [];
        if (!$res1->successful()) $errors['customer'] = $res1->json();
        if (!$res2->successful()) $errors['address'] = $res2->json();
        if (!$res3->successful()) $errors['products'] = $res3->json();
        return $errors;
    }

    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            return '+213' . substr($phone, 1);
        }
        return '+' . $phone;
    }
}