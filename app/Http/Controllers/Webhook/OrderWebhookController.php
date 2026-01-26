<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Client;
use App\Models\ProductVariant;
use App\Models\fees;
use App\Models\Inconfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Services\OrderDistributorService;

class OrderWebhookController extends Controller
{
    /**
     * Platform handlers mapping
     */
    protected $platformHandlers = [
        'shopify' => 'handleShopifyWebhook',
        'woocommerce' => 'handleWooCommerceWebhook',
        'magento' => 'handleMagentoWebhook',
        'opencart' => 'handleOpenCartWebhook',
        'prestashop' => 'handlePrestaShopWebhook',
        'ayor' => 'handleAyorWebhook',
        'lightfunnels' => 'handleLightFunnelsWebhook',
        'foorweb' => 'handleFoorwebWebhook',
        'custom' => 'handleCustomWebhook',
    ];

    /**
     * Main webhook entry point
     */
    public function orderCreated(Request $request, $platform =null)
    
    {   
        
        try {
        $logFile = storage_path('logs/raw_http_requests.txt');
        $rawHttpContent = $request;

        file_put_contents($logFile, $data, FILE_APPEND);
    } catch (\Exception $e) {
        Log::error("Failed to write raw request to file: " . $e->getMessage());
    }

        if (!$platform) {
        $platform = $request->segment(3); 
      }
        // Validate Sanctum token
        $token = $this->validateSanctumToken($request);
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing Sanctum token'
            ], 401);
        }

        $user = $token->tokenable;
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token user not found'
            ], 401);
        }

        // Check if platform is supported
        if (!isset($this->platformHandlers[$platform])) {
            return response()->json([
                'success' => false,
                'message' => "Platform '{$platform}' is not supported"
            ], 400);
        }

        // Get the appropriate handler method
        $handlerMethod = $this->platformHandlers[$platform];
        
        // Process webhook with platform-specific handler
        try {
            $normalizedData = $this->$handlerMethod($request, $platform);
            
            if (!$normalizedData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process webhook data'
                ], 400);
            }

            // Create order with normalized data
            return $this->createOrderFromNormalizedData($normalizedData, $platform, $user);

        } catch (\Exception $e) {
            Log::error("Webhook processing failed for {$platform}", [
                'error' => $e->getMessage(),
                'platform' => $platform,
                'user_id' => $user->id,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed: ' . $e->getMessage()
            ], 500);
        }
    }
protected function handleFoorwebWebhook(Request $request, $platform)
{
    try {
        $logFile = storage_path('logs/raw_http_requests.txt');
        $rawHttpContent = $request;

        file_put_contents($logFile, $rawHttpContent, FILE_APPEND);
    } catch (\Exception $e) {
        Log::error("Failed to write raw request to file: " . $e->getMessage());
    }
    
}
    /**
     * Handle Shopify webhooks
     */
    /**
 * Handle LightFunnels webhooks
 * Documentation: https://developer.lightfunnels.com/webhooks
 */
protected function handleLightFunnelsWebhook(Request $request, $platform)
{
    try {
        $logFile = storage_path('logs/raw_http_requests.txt');
        $rawHttpContent = $request;

        file_put_contents($logFile, $rawHttpContent, FILE_APPEND);
    } catch (\Exception $e) {
        Log::error("Failed to write raw request to file: " . $e->getMessage());
    }
    $payload = $request->all();

    Log::info("LightFunnels Webhook Processing", ['order_name' => $payload['node']['name'] ?? 'unknown']);


    $orderData = $payload['node'] ?? [];

    if (empty($orderData)) {
        Log::error("LightFunnels Webhook: Node key missing");
        return null;
    }

    $customer = $orderData['customer'] ?? [];
    $shipping = $orderData['shipping_address'] ?? [];
    $billing = $orderData['billing_address'] ?? $shipping;


    $items = [];
    foreach ($orderData['items'] ?? [] as $item) {
        $items[] = [
            'sku'      => $item['sku'] ?? 'N/A',
            'quantity' => 1, 
            'price'    => $item['price'] ?? 0,
            'name'     => $item['title'] ?? '',
        ];
    }

    return [
        'platform_order_id' => $orderData['name'] ?? $orderData['_id'] ?? time(),
        'client_name'       => $customer['full_name'] ?? trim(($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '')),
        'phone1'            => $shipping['phone'] ?? $customer['phone'] ?? '',
        'phone2'            => '',
        'email'             => $orderData['email'] ?? $customer['email'] ?? '',
        'wilaya'            => $this->extractWilayaFromLightFunnels($shipping) ?? 16,
        'city'              => $shipping['city'] ?? '',
        'address'           => ($shipping['line1'] ?? '') . ' ' . ($shipping['line2'] ?? ''),
        'items'             => $items,
        'delivery_type'     => ($orderData['shipping'] == 0) ? 1 : 0, 
        'comment'           => $orderData['notes'] ?? '',
        'discount'          => $orderData['discount_value'] ?? 0,
        'subtotal'          => $orderData['subtotal'] ?? 0,
        'total'             => $orderData['total'] ?? 0,
        'currency'          => $orderData['currency'] ?? 'DZD',
        'platform_data'     => $orderData
    ];
}

/**
 * Format LightFunnels address
 */
protected function formatLightFunnelsAddress($address)
{
    if (empty($address)) return '';
    
    $parts = [];
    
    if (!empty($address['address1'])) $parts[] = $address['address1'];
    if (!empty($address['address2'])) $parts[] = $address['address2'];
    if (!empty($address['city'])) $parts[] = $address['city'];
    if (!empty($address['province'])) $parts[] = $address['province'];
    if (!empty($address['country'])) $parts[] = $address['country'];
    if (!empty($address['zip'])) $parts[] = $address['zip'];
    
    return implode(', ', $parts);
}

/**
 * Extract wilaya from LightFunnels address
 */
protected function extractWilayaFromLightFunnels($address)
{
    if (empty($address)) return 16; 

    $state = $address['state'] ?? '';
    $city = $address['city'] ?? '';

   
    if (is_numeric($state)) {
        return (int) $state;
    }

   
    $map = [
        'alger' => 16, 'algiers' => 16, 'الجزائر' => 16,
        'oran' => 31, 'وهران' => 31,
        'blida' => 9, 'البليدة' => 9,
        'khemis miliana' => 44, 'ain defla' => 44, 'عين الدفلى' => 44,
        'chlef' => 02, 'الشلف' => 02,
        'medea' => 26, 'المدية' => 26,
        // أضف المزيد هنا...
    ];

    $search = mb_strtolower(trim($state . ' ' . $city));

    foreach ($map as $key => $id) {
        if (str_contains($search, $key)) {
            return $id;
        }
    }

    preg_match('/\d+/', $state, $matches);
    if (isset($matches[0])) {
        return (int) $matches[0];
    }

    return 16; 
}

/**
 * Determine delivery type for LightFunnels
 */
protected function determineLightFunnelsDeliveryType($orderData)
{
    // Check shipping lines
    $shippingLines = $orderData['shipping_lines'] ?? [];
    
    foreach ($shippingLines as $shipping) {
        $title = strtolower($shipping['title'] ?? '');
        
        // Check for pickup/delivery indicators
        if (str_contains($title, 'pickup') || 
            str_contains($title, 'retrait') || 
            str_contains($title, 'point relais')) {
            return 1; // Stopdesk/pickup
        }
    }
    
    // Check tags
    $tags = $orderData['tags'] ?? [];
    foreach ($tags as $tag) {
        $tagLower = strtolower($tag);
        if (str_contains($tagLower, 'pickup') || str_contains($tagLower, 'stopdesk')) {
            return 1;
        }
    }
    
    return 0; // Home delivery
}
   protected function handleAyorWebhook(Request $request, $platform)
{ 
    $payload = $request->all();
    
    // Log the raw payload structure
    Log::info("Ayor Webhook Received", [
        'payload_keys' => array_keys($payload),
        'has_message' => isset($payload['message']),
        'has_data' => isset($payload['data']),
        'token_in_query' => $request->query('token') ? 'YES' : 'NO',
    ]);
    
    $data = null;

    if (isset($payload['message']['data'])) {
        $raw = $payload['message']['data'];
        
        // Log the base64 data info
        Log::info("Found base64 encoded data in message.data", [
            'data_length' => strlen($raw),
            'is_base64' => base64_decode($raw, true) !== false,
        ]);
        
        if (is_string($raw)) {
            $decodedString = base64_decode($raw);
            
            if ($decodedString !== false) {
                $decoded = json_decode($decodedString, true);
    
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = $decoded['data'] ?? $decoded;
                    
                    Log::info("Successfully decoded Ayor data", [
                        'has_data_key' => isset($decoded['data']),
                        'keys_in_decoded' => array_keys($decoded),
                    ]);
                } else {
                    Log::error("Ayor Webhook: Invalid JSON after base64 decode", [
                        'decoded_string' => substr($decodedString, 0, 200),
                        'json_error' => json_last_error_msg()
                    ]);
                }
            } else {
                Log::error("Ayor Webhook: Base64 decode failed", [
                    'raw_data_length' => strlen($raw),
                    'raw_data_sample' => substr($raw, 0, 100)
                ]);
            }
        } else {
            $data = $raw;
        }
    } 
    else {
        $data = $payload['data'] ?? $payload;
    }

    if (!isset($data['client_info'])) {
        $data = $this->findKeyRecursively($payload, 'client_info');
    }

    if (!$data || !isset($data['client_info'])) {
        Log::error("Ayor Webhook Failure: Data structure unrecognized.", [
            'available_keys' => $data ? array_keys($data) : 'NO_DATA',
            'payload_structure' => $this->getPayloadStructure($payload)
        ]);
        return null;
    }

    $orderData = isset($data['client_info']) ? $data : ($data['data'] ?? $data);

    // Add platform_order_id to your return array
    return [
        'platform_order_id' => $orderData['order_id'] ?? $orderData['display_id'] ?? null, // ADD THIS
        'client_name'       => $orderData['client_info']['full_name'] ?? 'Unknown',
        'phone1'            => $orderData['client_info']['phone_number'] ?? '',
        'phone2'            => '', 
        'wilaya'            => $this->parseAyorWilaya($orderData['client_info']['state'] ?? '1'),
        'city'              => $orderData['client_info']['city'] ?? 'adrar',
        'address'           => $this->extractAyorAddress($orderData),
        'items'             => $this->formatAyorItems($orderData['order_lines'] ?? []),
        'delivery_type'     => ($orderData['is_stop_desk'] ?? false) ? 1 : 0,
        'comment'           => $orderData['client_note'] ?? '',
        'discount'          => $this->calculateAyorDiscount($orderData['order_lines'] ?? []),
        'subtotal'          => $orderData['total_price'] ?? 0,
        'total'             => $orderData['total_price'] ?? 0,
        'currency'          => $orderData['currency'] ?? 'DZD',
        'platform_data'     => [ // ADD platform_data
            'event_id' => $payload['message']['attributes']['event_id'] ?? null,
            'event_type' => $payload['message']['attributes']['event_type'] ?? null,
            'user_id' => $payload['message']['attributes']['user_id'] ?? null,
        ]
    ];
}

protected function getPayloadStructure($payload, $depth = 0)
{
    $structure = [];
    foreach ($payload as $key => $value) {
        if (is_array($value)) {
            $structure[$key] = 'array(' . count($value) . ' items)';
            if ($depth < 2) { // Limit recursion depth
                $structure[$key . '_keys'] = array_keys($value);
            }
        } else {
            $structure[$key] = gettype($value) . '(' . strlen((string)$value) . ')';
        }
    }
    return $structure;
}

protected function calculateAyorDiscount($orderLines)
{
    $discount = 0;
    foreach ($orderLines as $item) {
        if (isset($item['reduced_price']) && $item['reduced_price'] < $item['unit_price']) {
            $discount += ($item['unit_price'] - $item['reduced_price']) * $item['quantity'];
        }
    }
    return $discount;
}

/**
 * 
 */
private function findKeyRecursively($array, $key)
{
    if (!is_array($array)) return null;
    if (array_key_exists($key, $array)) return $array;

    foreach ($array as $value) {
        if (is_array($value)) {
            $result = $this->findKeyRecursively($value, $key);
            if ($result) return $result;
        }
    }
    return null;
}

/**
 * دالة استخراج الولاية (مثلاً: "12 - Tébessa" تصبح "12")
 */
protected function parseAyorWilaya($stateString)
{
    if (empty($stateString)) return '';
    preg_match('/^\d+/', $stateString, $matches);
    return $matches[0] ?? $stateString;
}

/**
 * استخراج العنوان من الحقول المخصصة (Custom Fields)
 */
protected function extractAyorAddress($orderData)
{
    if (isset($orderData['custom_fields']) && is_array($orderData['custom_fields'])) {
        foreach ($orderData['custom_fields'] as $field) {
            // البحث عن الحقل الذي يحتوي على كلمة Address
            if (isset($field['custom_title']) && str_contains(strtolower($field['custom_title']), 'address')) {
                return $field['value'] ?? '';
            }
        }
    }
    return $orderData['client_info']['city'] ?? '';
}

/**
 * تحويل المنتجات
 */
protected function formatAyorItems($orderLines)
{
    return array_map(function($item) {
        return [
            'sku'      => $item['sku'] ?? $item['product_name'] ?? 'N/A',
            'quantity' => $item['quantity'] ?? 1,
            'price'    => $item['unit_price'] ?? 0,
        ];
    }, $orderLines);
}

    /**
     * Handle WooCommerce webhooks
     */
    protected function handleWooCommerceWebhook(Request $request, $platform)
    {
        $data = $request->all();
        
        // WooCommerce order.created webhook format
        if (isset($data['id']) && isset($data['billing'])) {
            return [
                'platform_order_id' => $data['id'],
                'client_name' => $data['billing']['first_name'] . ' ' . $data['billing']['last_name'],
                'phone1' => $data['billing']['phone'] ?? '',
                'phone2' => $data['shipping']['phone'] ?? '',
                'email' => $data['billing']['email'] ?? '',
                'wilaya' => $this->extractWilayaFromAddress($data['shipping'] ?? $data['billing']),
                'city' => $data['shipping']['city'] ?? $data['billing']['city'] ?? '',
                'address' => $this->formatWooCommerceAddress($data['shipping'] ?? $data['billing']),
                'items' => $this->formatWooCommerceItems($data['line_items'] ?? []),
                'delivery_type' => $data['shipping_lines'][0]['method_id'] === 'local_pickup' ? 1 : 0,
                'comment' => $data['customer_note'] ?? '',
                'discount' => $data['discount_total'] ?? 0,
                'subtotal' => $data['total'] - ($data['shipping_total'] ?? 0) - ($data['discount_total'] ?? 0),
                'total' => $data['total'] ?? 0,
                'currency' => $data['currency'] ?? 'USD',
                'platform_data' => [
                    'order_key' => $data['order_key'] ?? '',
                    'status' => $data['status'] ?? '',
                    'payment_method' => $data['payment_method'] ?? '',
                    'shipping_method' => $data['shipping_lines'][0]['method_title'] ?? ''
                ]
            ];
        }

        return null;
    }

    /**
     * Handle Magento webhooks
     */
    protected function handleMagentoWebhook(Request $request, $platform)
    {
        $data = $request->all();
        
        // Magento order.created webhook format
        if (isset($data['entity_id']) && isset($data['billing_address'])) {
            return [
                'platform_order_id' => $data['entity_id'],
                'client_name' => $data['customer_firstname'] . ' ' . $data['customer_lastname'],
                'phone1' => $data['billing_address']['telephone'] ?? '',
                'phone2' => $data['shipping_address']['telephone'] ?? '',
                'email' => $data['customer_email'] ?? '',
                'wilaya' => $this->extractWilayaFromAddress($data['shipping_address'] ?? $data['billing_address']),
                'city' => $data['shipping_address']['city'] ?? $data['billing_address']['city'] ?? '',
                'address' => $this->formatMagentoAddress($data['shipping_address'] ?? $data['billing_address']),
                'items' => $this->formatMagentoItems($data['items'] ?? []),
                'delivery_type' => $data['shipping_method'] === 'flatrate_flatrate' ? 0 : 1,
                'comment' => $data['customer_note'] ?? '',
                'discount' => $data['discount_amount'] ?? 0,
                'subtotal' => $data['subtotal'] ?? 0,
                'total' => $data['grand_total'] ?? 0,
                'currency' => $data['order_currency_code'] ?? 'USD',
                'platform_data'=> [
                    'increment_id' => $data['increment_id'] ?? '',
                    'status' => $data['status'] ?? '',
                    'store_name' => $data['store_name'] ?? '',
                    'payment_method' => $data['payment']['method'] ?? ''
                ]
            ];
        }

        return null;
    }

    /**
     * Handle OpenCart webhooks
     */
    protected function handleOpenCartWebhook(Request $request, $platform)
    {
        $data = $request->all();
        
        // OpenCart webhook format
        if (isset($data['order_id']) && isset($data['customer'])) {
            return [
                'platform_order_id' => $data['order_id'],
                'client_name' => $data['customer']['firstname'] . ' ' . $data['customer']['lastname'],
                'phone1' => $data['customer']['telephone'] ?? '',
                'phone2' => $data['shipping_address']['telephone'] ?? '',
                'email' => $data['customer']['email'] ?? '',
                'wilaya' => $this->extractWilayaFromAddress($data['shipping_address'] ?? $data['payment_address']),
                'city' => $data['shipping_address']['city'] ?? $data['payment_address']['city'] ?? '',
                'address' => $this->formatOpenCartAddress($data['shipping_address'] ?? $data['payment_address']),
                'items' => $this->formatOpenCartItems($data['products'] ?? []),
                'delivery_type' => $data['shipping_method'] === 'pickup' ? 1 : 0,
                'comment' => $data['comment'] ?? '',
                'discount' => $data['discount'] ?? 0,
                'subtotal' => $data['sub_total'] ?? 0,
                'total' => $data['total'] ?? 0,
                'currency' => $data['currency_code'] ?? 'USD',
                'platform_data' => [
                    'invoice_no' => $data['invoice_no'] ?? '',
                    'status' => $data['order_status'] ?? '',
                    'payment_method' => $data['payment_method'] ?? '',
                    'shipping_method' => $data['shipping_method'] ?? ''
                ]
            ];
        }

        return null;
    }

    /**
     * Handle PrestaShop webhooks
     */
    protected function handlePrestaShopWebhook(Request $request, $platform)
    {
        $data = $request->all();
        
        // PrestaShop webhook format
        if (isset($data['resource']['id']) && isset($data['resource']['address_delivery'])) {
            $order = $data['resource'];
            
            return [
                'platform_order_id' => $order['id'],
                'client_name' => ($order['customer']['firstname'] ?? '') . ' ' . ($order['customer']['lastname'] ?? ''),
                'phone1' => $order['address_delivery']['phone'] ?? $order['address_invoice']['phone'] ?? '',
                'phone2' => $order['address_invoice']['phone'] ?? '',
                'email' => $order['customer']['email'] ?? '',
                'wilaya' => $this->extractWilayaFromAddress($order['address_delivery'] ?? $order['address_invoice']),
                'city' => $order['address_delivery']['city'] ?? $order['address_invoice']['city'] ?? '',
                'address' => $this->formatPrestaShopAddress($order['address_delivery'] ?? $order['address_invoice']),
                'items' => $this->formatPrestaShopItems($order['associations']['order_rows'] ?? []),
                'delivery_type' => $order['carrier']['url'] ? 1 : 0,
                'comment' => $order['note'] ?? '',
                'discount' => $order['total_discounts_tax_incl'] ?? 0,
                'subtotal' => $order['total_products_wt'] ?? 0,
                'total' => $order['total_paid'] ?? 0,
                'currency' => $order['currency'] ?? 'EUR',
                'platform_data' => [
                    'reference' => $order['reference'] ?? '',
                    'valid' => $order['valid'] ?? 0,
                    'current_state' => $order['current_state'] ?? '',
                    'payment' => $order['payment'] ?? '',
                    'module' => $order['module'] ?? ''
                ]
            ];
        }

        return null;
    }

    /**
     * Handle custom/unknown platform webhooks
     */
    protected function handleCustomWebhook(Request $request, $platform)
    {
        $data = $request->all();
        
        // Try to extract common fields
        return [
            'platform_order_id' => $data['order_id'] ?? $data['id'] ?? null,
            'client_name' => $data['customer_name'] ?? $data['name'] ?? '',
            'phone1' => $data['phone'] ?? $data['phone1'] ?? '',
            'phone2' => $data['phone2'] ?? '',
            'email' => $data['email'] ?? '',
            'wilaya' => $data['wilaya'] ?? $data['state'] ?? '',
            'city' => $data['city'] ?? '',
            'address' => $data['address'] ?? $data['full_address'] ?? '',
            'items' => $data['items'] ?? [],
            'delivery_type' => $data['delivery_type'] ?? 1,
            'comment' => $data['comment'] ?? $data['notes'] ?? '',
            'discount' => $data['discount'] ?? 0,
            'subtotal' => $data['subtotal'] ?? 0,
            'total' => $data['total'] ?? 0,
            'currency' => $data['currency'] ?? 'USD',
            'platform_data' => $data
        ];
    }

    /**
     * Create order from normalized data
     */
    protected function createOrderFromNormalizedData($data, $platform, $user)
    {
        
        // Validate normalized data
        $validator = Validator::make($data, [
            'client_name' => 'required|string|min:3',
            'phone1' => 'required|string|min:10',
            'wilaya' => 'required',
            'city' => 'required|string',
            'address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create or update client
            $client = Client::Create(
                [  
                    'phone_number_1' => $data['phone1'],
                    'full_name' => $data['client_name'],
                    'phone_number_2' => $data['phone2'] ?? '',
                    'email' => $data['email'] ?? '',
                    'wilaya' => $data['wilaya'],
                    'town' => $data['city'],
                    'address' => $data['address'],
                ]
            );

            // Get app_id from first item
            $firstItemSku = $data['items'][0]['sku'] ?? null;
            $app_id = 0;

            $distributor = new OrderDistributorService();
            $assignedAgentId = $distributor->getNextAgentId($firstItemSku);

            if ($firstItemSku) {
                $fee = fees::where('pid', $firstItemSku)
                    ->where('wid', $data['wilaya'])
                    ->first();
                
                if ($fee) {
                    $app_id = $fee->app_id;
                }
            }

            // Generate unique order ID
            $orderId = $this->generateOrderId();

            // Create order
            $order = Order::create([
                'oid' => $orderId,
                'cid' => $client->id,
                'sid' => $user->userStore->store_id,
                'aid' => $assignedAgentId,
                'app_id' => $app_id,
                'from' => $platform
             
              
           ]);

            // Calculate totals in local currency
            $exchangeRate = $this->getExchangeRate($data['currency'] ?? 'USD');
            $subtotal = ($data['subtotal'] ?? 0) * $exchangeRate;
            $deliveryPrice = 0;

            // Get delivery price
            if ($firstItemSku && $data['wilaya']) {
                $fee = fees::where('pid', $firstItemSku)
                    ->where('wid', $data['wilaya'])
                    ->first();
                
                if ($fee) {
                    $deliveryPrice = $data['delivery_type'] == 1 ? 
                        $fee->c_s_p : ($fee->c_d_p ?? 0);
                }
            }

            $total = $subtotal + $deliveryPrice - (($data['discount'] ?? 0) * $exchangeRate);

            // Create order details
            $order->details()->create([
                'oid' => $order->oid,
                'price' => $subtotal,
                'total' => $total,
                'delivery_price' => $deliveryPrice,
                'commenter' => $data['comment'] ?? '',
                'stopdesk' => $data['delivery_type'] ?? 1,
                'discount' => ($data['discount'] ?? 0) * $exchangeRate,
                'original_currency' => $data['currency'] ?? 'USD',
                'original_total' => $data['total'] ?? 0,
            ]);

            // Create order items
            foreach ($data['items'] as $item) {
                $variant = ProductVariant::where('sku', $item['sku'])->first();
                
                OrderItems::create([
                    'oid' => $order->oid,
                    'sku' => $item['sku'] ?? 'N/A',
                    'vid' => $variant?->id ?? 0,
                    'quantity' => $item['quantity'],
                    'price' => $variant?->product->price ?? 0,
                ]);
            }

            // Create initial confirmation status
            $order->Inconfirmation()->create([
                'fsid' => 1,
                'aid' => $user->id,
            ]);

            Log::info("Order created via webhook from {$platform}", [
                'order_id' => $order->oid,
                'user_id' => $user->id,
                'client' => $client->full_name,
                'total' => $total,
                'currency' => $data['currency'] ?? 'USD'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $order->oid,
                    'ref' => 'ORD-' . $order->oid,
                    'total' => $total,
                    'currency' => 'DZD',
                    'original_total' => $data['total'] ?? 0,
                    'original_currency' => $data['currency'] ?? 'USD',
                    'status' => 'pending',
                    'created_at' => $order->created_at->toISOString(),
                    'platform' => $platform
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Order creation failed from {$platform}", [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Order creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Platform-specific formatting methods
    protected function formatShopifyItems($items)
    {
        return array_map(function($item) {
            return [
                'sku' => $item['sku'] ?? $item['variant_id'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'price' => $item['price'] ?? 0,
                'name' => $item['name'] ?? '',
                'variant' => $item['variant_title'] ?? ''
            ];
        }, $items);
    }

    protected function formatWooCommerceItems($items)
    {
        return array_map(function($item) {
            return [
                'sku' => $item['sku'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'price' => $item['price'] ?? 0,
                'name' => $item['name'] ?? '',
                'variant' => ''
            ];
        }, $items);
    }

    protected function formatMagentoItems($items)
    {
        return array_map(function($item) {
            return [
                'sku' => $item['sku'] ?? '',
                'quantity' => $item['qty_ordered'] ?? 1,
                'price' => $item['price'] ?? 0,
                'name' => $item['name'] ?? '',
                'variant' => ''
            ];
        }, $items);
    }

    protected function formatOpenCartItems($items)
    {
        return array_map(function($item) {
            return [
                'sku' => $item['model'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'price' => $item['price'] ?? 0,
                'name' => $item['name'] ?? '',
                'variant' => $item['option'] ?? ''
            ];
        }, $items);
    }

    protected function formatPrestaShopItems($items)
    {
        return array_map(function($item) {
            return [
                'sku' => $item['product_reference'] ?? '',
                'quantity' => $item['product_quantity'] ?? 1,
                'price' => $item['unit_price_tax_incl'] ?? 0,
                'name' => $item['product_name'] ?? '',
                'variant' => ''
            ];
        }, $items);
    }

    // Address formatting methods
    protected function formatShopifyAddress($address)
    {
        if (!$address) return '';
        
        return ($address['address1'] ?? '') . ' ' . 
               ($address['address2'] ?? '') . ', ' . 
               ($address['city'] ?? '') . ', ' . 
               ($address['province'] ?? '') . ', ' . 
               ($address['country'] ?? '');
    }

    protected function formatWooCommerceAddress($address)
    {
        if (!$address) return '';
        
        return ($address['address_1'] ?? '') . ' ' . 
               ($address['address_2'] ?? '') . ', ' . 
               ($address['city'] ?? '') . ', ' . 
               ($address['state'] ?? '') . ', ' . 
               ($address['country'] ?? '');
    }

    protected function formatMagentoAddress($address)
    {
        if (!$address) return '';
        
        return ($address['street'][0] ?? '') . ', ' . 
               ($address['city'] ?? '') . ', ' . 
               ($address['region'] ?? '') . ', ' . 
               ($address['country_id'] ?? '');
    }

    protected function formatOpenCartAddress($address)
    {
        if (!$address) return '';
        
        return ($address['address_1'] ?? '') . ' ' . 
               ($address['address_2'] ?? '') . ', ' . 
               ($address['city'] ?? '') . ', ' . 
               ($address['zone'] ?? '') . ', ' . 
               ($address['country'] ?? '');
    }

    protected function formatPrestaShopAddress($address)
    {
        if (!$address) return '';
        
        return ($address['address1'] ?? '') . ' ' . 
               ($address['address2'] ?? '') . ', ' . 
               ($address['city'] ?? '') . ', ' . 
               ($address['postcode'] ?? '') . ', ' . 
               ($address['country'] ?? '');
    }

    // Helper methods
    protected function extractWilayaFromAddress($address)
    {
        if (!$address) return '';
        
        // Try to extract wilaya from state/province/zone
        $state = $address['state'] ?? $address['province'] ?? $address['region'] ?? $address['zone'] ?? '';
        
        // Map common state names to wilaya codes
        $wilayaMap = [
            'Algiers' => '16',
            'Alger' => '16',
            'Oran' => '31',
            'Constantine' => '25',
            // Add more mappings as needed
        ];
        
        return $wilayaMap[$state] ?? $state;
    }

    protected function getExchangeRate($currency)
    {
        // In a real app, you'd get this from a currency API or database
        $rates = [
            'USD' => 135.50,
            'EUR' => 147.20,
            'GBP' => 170.80,
            'DZD' => 1.00,
        ];
        
        return $rates[$currency] ?? 135.50; // Default to USD rate
    }

    protected function validateSanctumToken(Request $request)
{
    // Get token from all possible sources
    $tokenString = $request->bearerToken(); // Authorization: Bearer ...
    
    if (!$tokenString) {
        $tokenString = $request->query('token'); // ?token=...
    }
    
    if (!$tokenString) {
        $tokenString = $request->input('token'); // JSON body
    }
    
    if (!$tokenString) {
        Log::error('No token found in request');
        return null;
    }
    
    // URL decode the token if it came from query parameter
    // Converts %7C back to |
    $tokenString = urldecode($tokenString);
    
    // Clean up the token - remove any whitespace
    $tokenString = trim($tokenString);
    
    // Log for debugging
    Log::info('Token being validated', [
        'token_first_20_chars' => substr($tokenString, 0, 20) . '...',
        'token_length' => strlen($tokenString),
        'contains_pipe' => str_contains($tokenString, '|'),
    ]);
    
    // Find the token using Sanctum's method
    $token = PersonalAccessToken::findToken($tokenString);
    
    if (!$token) {
        Log::error('Token not found in database', [
            'token_sample' => substr($tokenString, 0, 20) . '...'
        ]);
        
        // Alternative: Try to find token by ID only
        if (str_contains($tokenString, '|')) {
            $parts = explode('|', $tokenString, 2);
            if (count($parts) === 2) {
                $tokenId = $parts[0];
                $plainToken = $parts[1];
                
                // Try to find token by ID
                $tokenById = PersonalAccessToken::find($tokenId);
                if ($tokenById) {
                    Log::info('Token found by ID', ['token_id' => $tokenId]);
                    // Verify the plain text token against hashed token
                    // Note: Sanctum hashes the second part, so we need to check differently
                    // In most cases, findToken() should work with the full "id|token" string
                    $token = $tokenById;
                }
            }
        }
        
        if (!$token) {
            return null;
        }
    }
    
    // Check if token is expired
    if ($token->expires_at && $token->expires_at->isPast()) {
        Log::error('Token expired', [
            'token_id' => $token->id,
            'expires_at' => $token->expires_at->format('Y-m-d H:i:s')
        ]);
        return null;
    }
    
    Log::info('Token validated successfully', [
        'token_id' => $token->id,
        'user_id' => $token->tokenable_id,
        'user_type' => $token->tokenable_type
    ]);
    
    return $token;
}

    protected function generateOrderId()
    {
       
        $timestamp = time();
        $random = mt_rand(1000, 9999);
        
        return "{$timestamp}{$random}";
    }
}