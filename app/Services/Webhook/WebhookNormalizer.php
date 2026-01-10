<?php

namespace App\Services\Webhook;

/**
 * Normalize incoming webhook data from different platforms to our standard format
 */
class WebhookNormalizer
{
    /**
     * Normalize platform-specific data to our standard format
     */
    public function normalize(string $platform, array $payload): array
    {
        $method = 'normalize' . ucfirst($platform);
        
        if (method_exists($this, $method)) {
            return $this->$method($payload);
        }
        
        // Default normalization for unknown platforms
        return $this->normalizeGeneric($payload);
    }
    
    /**
     * Shopify normalization
     */
    private function normalizeShopify(array $payload): array
    {
        return [
            'order' => [
                'platform_order_id' => $payload['id'] ?? null,
                'order_number' => $payload['order_number'] ?? null,
                'total' => $payload['total_price'] ?? 0,
                'subtotal' => $payload['subtotal_price'] ?? 0,
                'tax' => $payload['total_tax'] ?? 0,
                'discount' => $payload['total_discounts'] ?? 0,
                'currency' => $payload['currency'] ?? 'USD',
                'status' => $payload['financial_status'] ?? 'pending',
                'created_at' => $payload['created_at'] ?? now()->toISOString(),
            ],
            'customer' => [
                'platform_customer_id' => $payload['customer']['id'] ?? null,
                'email' => $payload['customer']['email'] ?? '',
                'first_name' => $payload['customer']['first_name'] ?? '',
                'last_name' => $payload['customer']['last_name'] ?? '',
                'phone' => $payload['customer']['phone'] ?? '',
            ],
            'shipping' => [
                'address' => [
                    'address1' => $payload['shipping_address']['address1'] ?? '',
                    'address2' => $payload['shipping_address']['address2'] ?? '',
                    'city' => $payload['shipping_address']['city'] ?? '',
                    'province' => $payload['shipping_address']['province'] ?? '',
                    'country' => $payload['shipping_address']['country'] ?? '',
                    'zip' => $payload['shipping_address']['zip'] ?? '',
                ],
                'method' => $payload['shipping_lines'][0]['title'] ?? '',
                'price' => $payload['shipping_lines'][0]['price'] ?? 0,
            ],
            'items' => array_map(function($item) {
                return [
                    'platform_item_id' => $item['id'] ?? null,
                    'sku' => $item['sku'] ?? '',
                    'name' => $item['name'] ?? '',
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $item['price'] ?? 0,
                    'total' => ($item['price'] ?? 0) * ($item['quantity'] ?? 1),
                ];
            }, $payload['line_items'] ?? []),
        ];
    }
    
    /**
     * WooCommerce normalization
     */
    private function normalizeWoocommerce(array $payload): array
    {
        return [
            'order' => [
                'platform_order_id' => $payload['id'] ?? null,
                'order_number' => $payload['number'] ?? null,
                'total' => $payload['total'] ?? 0,
                'subtotal' => $payload['subtotal'] ?? 0,
                'tax' => $payload['total_tax'] ?? 0,
                'discount' => $payload['discount_total'] ?? 0,
                'currency' => $payload['currency'] ?? 'USD',
                'status' => $payload['status'] ?? 'pending',
                'created_at' => $payload['date_created'] ?? now()->toISOString(),
            ],
            'customer' => [
                'email' => $payload['billing']['email'] ?? '',
                'first_name' => $payload['billing']['first_name'] ?? '',
                'last_name' => $payload['billing']['last_name'] ?? '',
                'phone' => $payload['billing']['phone'] ?? '',
            ],
            'shipping' => [
                'address' => [
                    'address1' => $payload['shipping']['address_1'] ?? '',
                    'address2' => $payload['shipping']['address_2'] ?? '',
                    'city' => $payload['shipping']['city'] ?? '',
                    'state' => $payload['shipping']['state'] ?? '',
                    'country' => $payload['shipping']['country'] ?? '',
                    'postcode' => $payload['shipping']['postcode'] ?? '',
                ],
            ],
            'items' => array_map(function($item) {
                return [
                    'sku' => $item['sku'] ?? '',
                    'name' => $item['name'] ?? '',
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $item['price'] ?? 0,
                    'total' => ($item['price'] ?? 0) * ($item['quantity'] ?? 1),
                ];
            }, $payload['line_items'] ?? []),
        ];
    }
    
    /**
     * Generic normalization for unknown platforms
     */
    private function normalizeGeneric(array $payload): array
    {
        return [
            'order' => [
                'platform_order_id' => $payload['id'] ?? $payload['order_id'] ?? null,
                'order_number' => $payload['order_number'] ?? $payload['number'] ?? null,
                'total' => $payload['total'] ?? $payload['amount'] ?? 0,
                'currency' => $payload['currency'] ?? 'USD',
                'status' => $payload['status'] ?? 'pending',
                'created_at' => $payload['created_at'] ?? $payload['date'] ?? now()->toISOString(),
            ],
            'customer' => [
                'email' => $payload['email'] ?? $payload['customer']['email'] ?? '',
                'first_name' => $payload['first_name'] ?? $payload['customer']['first_name'] ?? '',
                'last_name' => $payload['last_name'] ?? $payload['customer']['last_name'] ?? '',
                'phone' => $payload['phone'] ?? $payload['customer']['phone'] ?? '',
            ],
            'items' => $payload['items'] ?? $payload['products'] ?? [],
        ];
    }
}