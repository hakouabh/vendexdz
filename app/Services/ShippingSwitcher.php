<?php

namespace App\Services;

use App\Services\AndersonServices\AndersonCreateOrderService;
use App\Services\NoestServices\NoestCreateOrderService;
use App\Services\ZRServices\ZRCreateOrderService;
use App\Models\installedApps;
use App\Models\OrderInconfirmation;
use App\Models\OrderWaiting;
use App\Models\Order;

class ShippingSwitcher
{
    public function dispatch($orders, $order) 
    {  
        // 1. Resolve service
        $service = $this->resolveService($order->app_id, $order->sid);

        // 2. Normalize input: If it's a single object, wrap it in an array
        $orderList = is_array($orders) ? $orders : [$orders];

        // 3. Send all orders to the service
        // The service now handles the formatting loop inside sendOrders
        $result = $service->sendOrders($orderList);

        // 4. If we sent multiple, return the raw result to the component for bulk processing
        if (is_array($orders)) {
            
            return $result;
        }

        // 5. If it was a single order, process the individual response as before
        $singleRef = $orderList[0]->ref;
        return $this->processResponse($singleRef, $result);
    }

    public function createParcels($orders, $order) 
    {  
        $service = $this->resolveService($order->app_id, $order->sid);

        $result = $service->sendOrders($orders);
        
        return $this->processCreateOrdersResponse($result);
    }

    protected function resolveService($id, $sid)
    {  
        $installedApp = installedApps::where('sid', $sid)->where('app_id', $id)->first();
        return match ((int)$id) {
            1001 => new AndersonCreateOrderService($installedApp),
            1002 => new AndersonCreateOrderService($installedApp),
            1003 => new AndersonCreateOrderService($installedApp),
            1015 => new NoestCreateOrderService($installedApp),
            1010 => new ZRCreateOrderService($installedApp),
            default => throw new \Exception("Carrier Service ID [{$id}] not found in Switcher."),
        };
    }

    protected function processResponse($ref, $result)
    {
        if (isset($result['results'][$ref]['success']) && $result['results'][$ref]['success']) {
            return [
                'success' => true,
                'tracking' => $result['results'][$ref]['tracking'],
                'reference' => $ref
            ];
        }
        if (isset($result['passed'][0]['success']) && $result['passed'][0]['success']) {
            return [
                'success' => true,
                'tracking' => $result['passed'][0]['tracking'],
            ];
        }
        if(isset($result['successCount']) && $result['successCount'] > 0) {
            return [
                'success' => true,
                'tracking' => $result['successes'][0]['trackingNumber'],
                'parcelId' => $result['successes'][0]['parcelId']
            ];
        }
        if (isset($result['failureCount']) && $result['failureCount'] > 0) {
            return [
                'success' => false, 
                'message' => $result['failures'][0]['errorMessage'] ?? 'API error for ' . $ref
            ];
        }

        return [
            'success' => false, 
            'message' => $result['results'][$ref]['errors'] ?? 'API error for ' . $ref
        ];
    }
    protected function processCreateOrdersResponse($response)
    {
        $notifications = [];
        if (isset($response['passed'])) {
            foreach ($response['passed'] as $passed) {
                $reference = $passed['reference'] ?? null;

                if (!$reference) {
                    continue;
                }
                $id = str_replace('VN-', '', $reference);
                $order = Order::find($id);

                if (!$order) {
                    continue;
                }
                $order->tracking = $passed['tracking'];
                $order->save();
                OrderInconfirmation::where('oid', $order->oid)->delete();
                OrderWaiting::create([
                    'oid'  => $order->oid,
                    'asid' => 1
                ]);

                $notifications[] = [
                    'order_number' => $reference,
                    'status' => 'success',
                    'message' => 'Parcel created successfully',
                ];
            }
        }
        if (isset($response['successes'])) {
            foreach ($response['successes'] as $successe) {
                $reference = $successe['externalId'] ?? null;

                if (!$reference) {
                    continue;
                }

                $id = str_replace('VN-', '', $reference);
                $order = Order::find($id);

                if (!$order) {
                    continue;
                }
                $order->tracking = $successe['trackingNumber'];
                $order->custom_id = $successe['parcelId'] ?? null;
                $order->save();
                OrderInconfirmation::where('oid', $order->oid)->delete();
                OrderWaiting::create([
                    'oid'  => $order->oid,
                    'asid' => 1
                ]);

                $notifications[] = [
                    'order_number' => $reference,
                    'status' => 'success',
                    'message' => 'Parcel created successfully',
                ];
            }
        }
        if (isset($response['failed'])) {
            foreach ($response['failed'] as $failed) {
                $reference = $failed['reference'] ?? null;

                if (!$reference) {
                    continue;
                }

                $message = collect($failed)
                    ->except('reference')
                    ->flatten()
                    ->implode(' ');

                $notifications[] = [
                    'order_number' => $reference,
                    'status' => 'failed',
                    'message' => $message,
                ];
            }
        }
        if (isset($response['failures'])) {
            foreach ($response['failures'] as $failure) {
                $reference = $failure['externalId'] ?? null;

                if (!$reference) {
                    continue;
                }

                $message = collect($failure)
                    ->except('reference')
                    ->flatten()
                    ->implode(' ');

                $notifications[] = [
                    'order_number' => $reference,
                    'status' => 'failed',
                    'message' => $message,
                ];
            }
        }
        if (isset($response['results'])) {
            foreach ($response['results'] as $reference => $result) {
                $id = str_replace('VN-', '', $reference);
                $order = Order::find($id);

                if (!$order) {
                    continue;
                }

                if (($result['success'] ?? false) === true) {

                    $order->tracking = $result['tracking'] ?? null;
                    $order->save();
                    OrderInconfirmation::where('oid', $order->oid)->delete();
                    OrderWaiting::create([
                        'oid'  => $order->oid,
                        'asid' => 1
                    ]);

                    $notifications[] = [
                        'order_number' => $reference,
                        'status' => 'success',
                        'message' => 'Parcel created successfully',
                    ];
                } else {
                    $message = collect($result)
                        ->except(['success', 'reference'])
                        ->flatten()
                        ->implode(' ');

                    $notifications[] = [
                        'order_number' => $reference,
                        'status' => 'failed',
                        'message' => $message ?: 'Parcel creation failed',
                    ];
                }
            }
        }
        return $notifications;
    }
}