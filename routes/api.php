<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\OrderWebhookController;
use App\Http\Controllers\Admin\LanguageController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/lang-import', [LanguageController::class, 'langImport']);
Route::get('/ping', function () {
    return app()->currentLocale();
});

Route::prefix('webhook')->group(function () {
    
    Route::post('/shopify/created', [OrderWebhookController::class, 'orderCreated'])->withoutMiddleware([\Illuminate\Auth\Middleware\Authenticate::class]);
    Route::post('/shopify/updated', [OrderWebhookController::class, 'orderUpdated']);
    Route::post('/shopify/cancelled', [OrderWebhookController::class, 'orderCancelled']);
    
    // WooCommerce
    Route::post('/woocommerce/created', [OrderWebhookController::class, 'orderCreated']);
    Route::post('/woocommerce/updated', [OrderWebhookController::class, 'orderUpdated']);
    Route::post('/woocommerce/cancelled', [OrderWebhookController::class, 'orderCancelled']);
    
    // Magento
    Route::post('/magento/created', [OrderWebhookController::class, 'orderCreated']);
    Route::post('/magento/updated', [OrderWebhookController::class, 'orderUpdated']);
    Route::post('/magento/cancelled', [OrderWebhookController::class, 'orderCancelled']);
    
    // OpenCart
    Route::post('/opencart/created', [OrderWebhookController::class, 'orderCreated']);
    Route::post('/opencart/updated', [OrderWebhookController::class, 'orderUpdated']);
    Route::post('/opencart/cancelled', [OrderWebhookController::class, 'orderCancelled']);
    
    // PrestaShop
    Route::post('/prestashop/created', [OrderWebhookController::class, 'orderCreated']);
    Route::post('/prestashop/updated', [OrderWebhookController::class, 'orderUpdated']);
    Route::post('/prestashop/cancelled', [OrderWebhookController::class, 'orderCancelled']);
    
    // Custom/Other platforms
    Route::post('/{platform}/created', [OrderWebhookController::class, 'orderCreated']);
    Route::post('/{platform}/updated', [OrderWebhookController::class, 'orderUpdated']);
    Route::post('/{platform}/cancelled', [OrderWebhookController::class, 'orderCancelled']);
    //ayor
    Route::post('/ayor/created', [OrderWebhookController::class, 'orderCreated']);
    Route::post('/ayor/updated', [OrderWebhookController::class, 'orderUpdated']);
    Route::post('/ayor/cancelled', [OrderWebhookController::class, 'orderCancelled']);
    //lightfunnels
    Route::post('/webhook/order/lightfunnels', [OrderWebhookController::class, 'orderCreated'])
    ->name('webhook.lightfunnels.created');

     Route::post('/webhook/order/foorweb', [OrderWebhookController::class, 'orderCreated'])
    ->name('webhook.foorweb.created');
});