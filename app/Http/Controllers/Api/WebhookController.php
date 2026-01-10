<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Order;
use App\Models\WebhookLog;
use App\Services\Webhooks\WebhookFactory;

class WebhookController extends Controller
{
    public function handleCreate(Request $request, $platform, $secret)
    {

        $shop = Shop::where('webhook_secret', $secret)->first();

        if (! $shop || $shop->platform !== $platform) {
            return response()->json(['message' => 'Unauthorized or Invalid Platform'], 401);
        }


        $log = WebhookLog::create([
            'shop_id' => $shop->id,
            'source'  => $platform,
            'event'   => 'created',
            'payload' => $request->all(),
            'status'  => 'pending'
        ]);

        try {

            $adapter = WebhookFactory::make($platform);
            $data = $adapter->parse($request->all());

            if (! $data['external_id']) {
                throw new \Exception("External ID missing from payload");
            }


            $order = Order::firstOrCreate(
                [
                    'shop_id'     => $shop->id,
                    'external_id' => $data['external_id']
                ],
                [
                    'user_id'       => $shop->user_id,
                    'customer_name' => $data['customer_name'],
                    'phone'         => $data['phone'],
                    'total'         => $data['total'],
                    'status'        => 'new' // الحالة الافتراضية
                ]
            );

            
            $statusMessage = $order->wasRecentlyCreated ? 'Order Created' : 'Order Already Exists';

          
            $log->update(['status' => 'processed']);

            return response()->json(['success' => true, 'message' => $statusMessage], 200);

        } catch (\Exception $e) {
        
            $log->update([
                'status' => 'failed', 
                'error_message' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }
}
