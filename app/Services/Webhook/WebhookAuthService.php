<?php

namespace App\Services\Webhook;

use Illuminate\Http\Request;

/**
 * Authentication service for INCOMING webhooks
 * Platforms send webhooks to us - we verify them
 */
class WebhookReceiverAuthService
{
    private array $supportedPlatforms = [
        'shopify' => [
            'name' => 'Shopify',
            'webhook_url' => '/api/webhooks/shopify/orders',
            'requires_signature' => true,
            'signature_header' => 'X-Shopify-Hmac-Sha256',
        ],
        'woocommerce' => [
            'name' => 'WooCommerce',
            'webhook_url' => '/api/webhooks/woocommerce/orders',
            'requires_signature' => true,
            'signature_header' => 'X-WC-Webhook-Signature',
        ],
        'magento' => [
            'name' => 'Magento 2',
            'webhook_url' => '/api/webhooks/magento/orders',
            'requires_signature' => false,
        ],
        'prestashop' => [
            'name' => 'PrestaShop',
            'webhook_url' => '/api/webhooks/prestashop/orders',
            'requires_signature' => false,
        ],
        'custom' => [
            'name' => 'Custom Platform',
            'webhook_url' => '/api/webhooks/custom/orders',
            'requires_signature' => false,
        ]
    ];
    
    /**
     * Check if platform is supported for receiving webhooks
     */
    public function isPlatformSupported(string $platform): bool
    {
        return isset($this->supportedPlatforms[$platform]);
    }
    
    /**
     * Get webhook URL for a specific platform
     */
    public function getWebhookUrl(string $platform): string
    {
        return url($this->supportedPlatforms[$platform]['webhook_url'] ?? '');
    }
    
    /**
     * Get configuration for platform webhook setup
     */
    public function getPlatformConfig(string $platform): array
    {
        if (!$this->isPlatformSupported($platform)) {
            throw new \Exception("Platform {$platform} is not supported");
        }
        
        $config = $this->supportedPlatforms[$platform];
        
        return [
            'webhook_url' => $this->getWebhookUrl($platform),
            'http_method' => 'POST',
            'content_type' => 'application/json',
            'events' => ['order.created', 'order.updated'],
            'secret' => $this->getWebhookSecret($platform),
            'instructions' => $this->getSetupInstructions($platform),
        ];
    }
    
    /**
     * Get webhook secret for platform (for signature verification)
     */
    public function getWebhookSecret(string $platform): ?string
    {
        return match($platform) {
            'shopify' => config('services.shopify.webhook_secret'),
            'woocommerce' => config('services.woocommerce.webhook_secret'),
            'magento' => config('services.magento.webhook_secret'),
            default => null,
        };
    }
    
    private function getSetupInstructions(string $platform): array
    {
        return match($platform) {
            'shopify' => [
                '1. Go to Shopify Admin → Settings → Notifications',
                '2. Click "Create webhook"',
                '3. Event: "Order creation"',
                '4. URL: ' . $this->getWebhookUrl($platform),
                '5. Format: JSON',
                '6. Save webhook',
            ],
            'woocommerce' => [
                '1. Go to WooCommerce → Settings → Advanced → Webhooks',
                '2. Click "Add webhook"',
                '3. Topic: "Order created"',
                '4. URL: ' . $this->getWebhookUrl($platform),
                '5. Save webhook',
            ],
            default => [
                'Configure your platform to send POST requests to:',
                $this->getWebhookUrl($platform),
                'Content-Type: application/json',
            ],
        };
    }
    
    public function getSupportedPlatforms(): array
    {
        return array_keys($this->supportedPlatforms);
    }
    
    /**
     * Register a new platform for receiving webhooks
     */
    public function registerPlatform(string $platform, array $config): void
    {
        $this->supportedPlatforms[$platform] = array_merge([
            'name' => ucfirst($platform),
            'webhook_url' => "/api/webhooks/{$platform}/orders",
            'requires_signature' => false,
            'signature_header' => null,
        ], $config);
    }
}