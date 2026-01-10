<?php

namespace App\Services\Webhook;

use App\Services\Webhook\Handlers\HandlerFactory;
use App\Services\Webhook\Validators\WebhookValidator;
use App\Services\Webhook\Processors\OrderProcessor;
use App\Repositories\WebhookLogRepository;
use Illuminate\Support\Facades\Log;

/**
 * Main webhook processing service
 * Orchestrates the flow: Handler → Validator → Processor
 */
class WebhookService
{
    public function __construct(
        private HandlerFactory $handlerFactory,
        private WebhookValidator $validator,
        private OrderProcessor $orderProcessor,
        private WebhookLogRepository $logRepository
    ) {}
    
    /**
     * Process webhook payload through pipeline
     */
    public function process(string $platform, array $payload, array $headers = []): array
    {
        $startTime = microtime(true);
        
        try {
            // 1. Get platform-specific handler
            $handler = $this->handlerFactory->make($platform);
            
            // 2. Normalize payload to standard format
            $normalizedData = $handler->normalize($payload, $headers);
            
            // 3. Validate normalized data
            $this->validator->validate($normalizedData, $platform);
            
            // 4. Process and create order
            $result = $this->orderProcessor->process($normalizedData, $platform);
            
            // 5. Log successful processing
            $this->logRepository->create([
                'platform' => $platform,
                'status' => 'success',
                'payload' => json_encode($payload),
                'normalized_data' => json_encode($normalizedData),
                'result' => json_encode($result),
                'processing_time' => microtime(true) - $startTime,
                'ip_address' => request()->ip(),
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            // Log failure
            $this->logRepository->create([
                'platform' => $platform,
                'status' => 'failed',
                'payload' => json_encode($payload),
                'error_message' => $e->getMessage(),
                'processing_time' => microtime(true) - $startTime,
                'ip_address' => request()->ip(),
            ]);
            
            throw new WebhookException(
                "Webhook processing failed for {$platform}: " . $e->getMessage(),
                previous: $e
            );
        }
    }
    
    /**
     * Test webhook endpoint (for configuration)
     */
    public function testEndpoint(string $platform, array $testData = []): array
    {
        $handler = $this->handlerFactory->make($platform);
        
        return [
            'handler' => get_class($handler),
            'normalized_sample' => $handler->normalize($testData),
            'validation_rules' => $this->validator->getRules($platform),
            'supported' => true,
        ];
    }
}