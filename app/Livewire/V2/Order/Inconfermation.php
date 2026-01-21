<?php

namespace App\Livewire\V2\Order;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\firstStepStatu;
use App\Models\User;
use App\Services\ShippingSwitcher;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;     
use Illuminate\Support\Facades\Auth;
use App\Livewire\V2\Order\Traits\OrderTrait;

class Inconfermation extends Component
{
    use OrderTrait;
    public $showTimerModal = false;
    public $tempStatusId;
    public $tempOrderId;
    public $scheduleTime;
    public $selectedStatu = null;
    public $storefilter=null;
    public $productfilter=null;
    public $statufilter=null;
    public $start_date=null;
    public $end_date=null;

    public $activeTab = 'chat'; 
 
    public function render()
    { 
        $firstStepStatus = firstStepStatu::all(); 
        $products = Product::where('store_id', Auth::user()->store_id)->latest()->get();
        $orders = Order::query()->where('sid',Auth::user()->id)
            ->whereHas('Inconfirmation', function ($query) {
            if ($this->statufilter) {
                $query->where('fsid', $this->statufilter);
            } else {
                $query->whereNotIn('fsid', [2, 3]);
            }
        })
        ->when($this->storefilter, function ($query) {
            $query->where('sid', $this->storefilter);
        })
        ->when($this->productfilter, function ($query) {
            $query->whereHas('items', function ($q) {
                $q->where('product_id',$this->productfilter);
            });
        })
        ->when($this->start_date, fn($q) => $q->whereDate('created_at', '>=', $this->start_date))
        ->when($this->end_date, fn($q) => $q->whereDate('created_at', '<=', $this->end_date))
        ->with([
            'client', 
            'details', 
            'Inconfirmation.firstStepStatu', 
            'items'
        ])
        ->latest()
        ->paginate(10, ['*'], 'inPage');
        $orders->withQueryString();
        return view('livewire.v2.order.inconfermation', ['orders' => $orders, 'firstStepStatus'=>$firstStepStatus ,'products'=>$products]);
    }
    
    public function sendToShipping()
    {
        if (!$this->activeOrder) return;

        $this->saveOrder();

        // This creates the stdClass (Standardized Object)
        $standardOrder = $this->getStandardizedData();

        try {
            $switcher = new \App\Services\ShippingSwitcher();
            
            // This now sends the stdClass to the updated dispatch method
            $result = $switcher->dispatch($standardOrder, 2);

            if ($result['success']) {
                $this->activeOrder->update([
                    'tracking_number' => $result['tracking'],
                
                    'company_id' => 1
                ]);

                session()->flash('success', "Dispatched! Tracking: " . $result['tracking']);
                $this->resetActiveOrder();
            } else {
                session()->flash('error', $result['message']);
            }
        } catch (\Exception $e) {
            session()->flash('error', "Error: " . $e->getMessage());
        }
    }
    public function sendAllToShipping()
    { 

        $ordersToSend = Order::whereHas('Inconfirmation.firstStepStatu', function ($query) {
                $query->where('fsid', 1) 
                    ->where('aid', auth()->id()); 
            })
            ->with(['client', 'details', 'items.variant.product'])
            ->get();

        if ($ordersToSend->isEmpty()) {
            session()->flash('error', 'No confirmed orders found to dispatch.');
            return;
        }


        $groupedOrders = $ordersToSend->groupBy('app_id');

        $totalSuccess = 0;
        $totalErrors = 0;

        try {
            $switcher = new \App\Services\ShippingSwitcher();

            foreach ($groupedOrders as $appId => $ordersInGroup) {
                
            
                $standardizedOrders = $ordersInGroup->map(function($order) {
                    $this->activeOrder = $order;
                    return $this->getStandardizedData();
                })->toArray();

            
                $bulkResult = $switcher->dispatch($standardizedOrders, $appId);

    
                foreach ($ordersInGroup as $order) {
                    $ref = $order->ref;
                    
            
                    if (isset($bulkResult['successes'])) {
                        $successData = collect($bulkResult['successes'])->firstWhere('externalId', $ref);
                        
                        if ($successData) {
                            $order->update([
                                'tracking'  => $successData['trackingNumber'],
                                'custom_id' => $successData['parcelId'],
                                'status'    => 'shipped', 
                            ]);
                            $totalSuccess++;
                            continue;
                        }
                    }

            
                    $totalErrors++;
                    $failureData = collect($bulkResult['failures'] ?? [])->firstWhere('externalId', $ref);
                    $errorMsg = $failureData['errorMessage'] ?? 'Rejected by Carrier';
                    \Log::warning("Order #{$ref} (App: {$appId}) failed: " . $errorMsg);
                }
            }

            $this->resetActiveOrder();
            session()->flash('success', "Process Finished: $totalSuccess orders sent. $totalErrors failed.");

        } catch (\Exception $e) {
            \Log::error("Critical Bulk Shipping Error: " . $e->getMessage());
            session()->flash('error', "Error: " . $e->getMessage());
        }
    }
}