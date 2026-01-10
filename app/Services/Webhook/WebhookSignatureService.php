<?php

namespace App\Services\Webhook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Verify signatures of INCOMING webhooks from platforms
 */
class WebhookSignatureVerifier
{
    /**
     * Verify webhook signature based on platform
     */
    public function verifyWebhook(Request $request, string $platform): bool
    {
        // Some platforms don't require signature verification
        if (!$this->requiresSignature($platform)) {
            return true;
        }
        
        $secret = $this->getSecret($platform);
        $signature = $this->getSignature($request, $platform);
        
        if (empty($secret) || empty($signature)) {
            Log::warning("Missing signature or secret for {$platform} webhook");
            return false;
        }
        
        return $this->verifySignature($request->getContent(), $signature, $secret, $platform);
    }
    
    private function requiresSignature(string $platform): bool
    {
        return match($platform) {
            'shopify', 'woocommerce' => true,
            default => false,
        };
    }
    
    private function getSecret(string $platform): ?string
    {
        return match($platform) {
            'shopify' => config('services.shopify.webhook_secret'),
            'woocommerce' => config('services.woocommerce.webhook_secret'),
            default => null,
        };
    }
    
    private function getSignature(Request $request, string $platform): ?string
    {
        return match($platform) {
            'shopify' => $request->header('X-Shopify-Hmac-Sha256'),
            'woocommerce' => $request->header('X-WC-Webhook-Signature'),
            default => $request->header('X-Webhook-Signature'),
        };
    }
    
    private function verifySignature(string $payload, string $signature, string $secret, string $platform): bool
    {
        $calculatedSignature = match($platform) {
            'shopify' => base64_encode(hash_hmac('sha256', $payload, $secret, true)),
            'woocommerce' => base64_encode(hash_hmac('sha256', $payload, $secret, true)),
            default => hash_hmac('sha256', $payload, $secret),
        };
        
        return hash_equals($signature, $calculatedSignature);
    }
    
    /**
     * Test signature verification (for setup)
     */
    public function testSignature(string $platform, string $payload, string $signature): array
    {
        $secret = $this->getSecret($platform);
        
        if (!$secret) {
            return [
                'success' => false,
                'message' => 'No webhook secret configured for this platform',
            ];
        }
        
        $isValid = $this->verifySignature($payload, $signature, $secret, $platform);
        
        return [
            'success' => $isValid,
            'message' => $isValid ? 'Signature is valid' : 'Signature is invalid',
            'platform' => $platform,
            'has_secret' => !empty($secret),
        ];
    }
}