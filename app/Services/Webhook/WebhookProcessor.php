<?php

namespace App\Services\Webhook;

use App\Models\Order;
use App\Models\Customer;
use App\Models\WebhookLog;
use App\Services\Order\OrderCreatorService;
use App\Services\Customer\CustomerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Process incoming webhooks and create orders in our system
 */
class WebhookProcessor
{
    public function __construct(
        private OrderCreatorService $orderCreator,
        private CustomerService $customerService,
        private WebhookNormalizer $normalizer
    ) {}
    
    /**
     * Process incoming webhook from platform
     */
    public function process(string $platform, array $payload, array $context = []): array
    {
        DB::beginTransaction();
        
        try {
            // 1. Normalize platform-specific data to our format
            $normalizedData = $this->normalizer->normalize($platform, $payload);
            
            // 2. Create or update customer
            $customer = $this->customerService->findOrCreate(
                $normalizedData['customer']['email'] ?? null,
                $normalizedData['customer']['phone'] ?? null,
                $normalizedData['customer']
            );
            
            // 3. Create order from normalized data
            $order = $this->orderCreator->createFromWebhook(
                $normalizedData['order'],
                $customer->id,
                $platform,
                $normalizedData['items'] ?? []
            );
            
            // 4. Log webhook processing
            $this->logWebhook($platform, $payload, $normalizedData, $order, $context);
            
            DB::commit();
            
            return [
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_id' => $customer->id,
                'total' => $order->total,
                'currency' => $order->currency,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->logFailedWebhook($platform, $payload, $e, $context);
            
            throw new \Exception("Failed to process {$platform} webhook: " . $e->getMessage());
        }
    }
    
    private function logWebhook(string $platform, array $payload, array $normalizedData, Order $order, array $context): void
    {
        WebhookLog::create([
            'webhook_id' => $context['webhook_id'] ?? uniqid(),
            'platform' => $platform,
            'status' => 'processed',
            'event_type' => $payload['event'] ?? 'order.created',
            'payload' => json_encode($payload),
            'normalized_data' => json_encode($normalizedData),
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'ip_address' => $context['ip_address'] ?? null,
            'response' => json_encode([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $order->total,
            ]),
        ]);
    }
    
    private function logFailedWebhook(string $platform, array $payload, \Exception $e, array $context): void
    {
        WebhookLog::create([
            'webhook_id' => $context['webhook_id'] ?? uniqid(),
            'platform' => $platform,
            'status' => 'failed',
            'event_type' => $payload['event'] ?? 'order.created',
            'payload' => json_encode($payload),
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'ip_address' => $context['ip_address'] ?? null,
        ]);
    }
    
    /**
     * Get webhook processing statistics
     */
    public function getStats(): array
    {
        $today = now()->today();
        
        return [
            'today' => [
                'total' => WebhookLog::whereDate('created_at', $today)->count(),
                'success' => WebhookLog::whereDate('created_at', $today)
                    ->where('status', 'processed')->count(),
                'failed' => WebhookLog::whereDate('created_at', $today)
                    ->where('status', 'failed')->count(),
            ],
            'by_platform' => WebhookLog::select('platform', DB::raw('count(*) as total'))
                ->groupBy('platform')
                ->get()
                ->pluck('total', 'platform')
                ->toArray(),
        ];
    }
}