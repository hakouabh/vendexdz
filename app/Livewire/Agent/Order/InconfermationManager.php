<?php

namespace App\Livewire\Agent\Order;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\OrderNots;
use App\Models\order_Comments;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\firstStepStatu;
use App\Models\order_type;
use App\Models\willaya;
use App\Models\fees;
use App\Models\User;
use App\Services\TerritoryServices\ZRTerritoryService;
use App\Services\TerritoryServices\AndersonTerritoryService;
use App\Services\ShippingSwitcher;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;     
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
class InconfermationManager extends Component
{
    use WithPagination;

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

    public $companie=0;
    public $communes = [];
    public $selectedWilayaId = null;

    public $expandedOrderId = null;
    public $activeOrder = null;
    public $activeTab = 'chat'; 

    public $availableProducts;

    public $client_name;
    public $phone1;
    public $phone2;
    public $wilaya;
    public $city;
    public $address;

    public $Comment;

    public $delivery_type = 1;
    public $order_type = 1;


    public $price = 0;
    public $delivery_price = 0;
    public $discount = 0;
    public $total = 0;
    


    public $items = []; 
    public $can_use_stopdesk = true;
 
    public $newNote = '';
    public $newMessage = '';

    #[On('reset-all-pagination')]
    public function resetPagination()
    {
        $this->resetPage('inPage'); 
    }

    public function updateFilter($storeId)
    {
        $this->storefilter = $storeId;
  
    }
    
    public function updatedCity($value)
    {
       $currentCommune = collect($this->communes)->firstWhere('name', $value);

        if ($currentCommune) {
            switch ((int)$this->companie) {
                case 1001: 
          
                    $this->can_use_stopdesk = (bool)($currentCommune['hasPickupPoint'] ?? false);
                    break;
                
                case 1010: 
                
                    $this->can_use_stopdesk = (bool)($currentCommune['delivery']['hasPickupPoint'] ?? false);
                    break;
               
            }

            if (!$this->can_use_stopdesk) {
                $this->delivery_type = '0';
            }
        }

        if (method_exists($this, 'calculateTotal')) {
            $this->calculateTotal();
        }
    }
    
    public function updatedWilaya($value)
    {
        $this->validate([
        'items'         => 'required|array|min:1',
        'items.*.vid'   => 'required|exists:product_variants,id',
        'items.*.quantity' => 'required|integer|min:1',
        ]);
        if (!$value) {
        $this->reset(['communes', 'selectedWilayaId', 'city', 'can_use_stopdesk']);
        return;
        }
        $firstItemSku = $this->items[0]['sku'] ?? null;
    $fee = fees::where('sid', $this->activeOrder->sid)
               ->where('pid', $firstItemSku)
               ->where('wid', $value)->first();

    if (!$fee) {
        $this->dispatch('notify', message: 'No carrier configured for this Wilaya.', type: 'error');
        return;
    }

    $this->companie = $fee->app_id;

    // Load Communes based on Service
    switch ($this->companie) {
        case 1001: 
            $service = new \App\Services\TerritoryServices\AndersonTerritoryService();
            $data = $service->getEverythingCached();
            $this->communes = $data['communes'][$value] ?? [];
            break;
        case 1010:
            $service = new \App\Services\TerritoryServices\ZRTerritoryService();
            $data = $service->getEverythingCached();
            $zrWilaya = $data['wilayas'][$value] ?? null;
            if ($zrWilaya) {
                $this->selectedWilayaId = $zrWilaya['id'];
                $this->communes = $data['communes'][$this->selectedWilayaId] ?? [];
            }
            break;
    }

    // AUTO-UPDATE TOWN LOGIC
    if (!empty($this->communes)) {
        // Try to find if the current 'city' exists in the new list
        $match = collect($this->communes)->firstWhere('name', $this->city);
        
        if ($match) {
            // Re-trigger the city update logic for stopdesk
            $this->updatedCity($this->city);
        } else {
            // Optional: Auto-select first commune if current one is invalid for new Wilaya
            // $this->city = $this->communes[0]['name'];
            // $this->updatedCity($this->city);
        }
    }
     
    } 
    
    public function render()
    { 
       
        $this->calculateTotal();
        $firstStepStatus = firstStepStatu::all(); 
        $products = Product::whereHas('agents', function ($query) {
              $query->where('aid', Auth::user()->id);
        })->latest()
       ->get();
        $stores = User::whereHas('agents', function ($query) {
              $query->where('aid', Auth::user()->id);
        })->latest()
       ->get();          

       $orders = Order::query()->where('aid',Auth::user()->id)
    // 1. Filter by Status (Table: order_inconfirmations)
    // We use whereHas because every order in this view MUST have a confirmation record
    ->whereHas('Inconfirmation', function ($query) {
        if ($this->statufilter) {
            $query->where('fsid', $this->statufilter);
        } else {
            // Default view logic: Exclude specific statuses
            $query->whereNotIn('fsid', [3, 4]);
        }
    })

    // 2. Store Filter (Directly on the 'orders' table)
    ->when($this->storefilter, function ($query) {
        $query->where('sid', $this->storefilter);
    })

    // 3. Product SKU Filter (Table: order_items)
    ->when($this->productfilter, function ($query) {
        // This enters the 'order_items' table
        $query->whereHas('items', function ($q) {
            $q->where('sku',$this->productfilter);
        });
    })

    // 4. Date Range (Optional)
    ->when($this->start_date, fn($q) => $q->whereDate('created_at', '>=', $this->start_date))
    ->when($this->end_date, fn($q) => $q->whereDate('created_at', '<=', $this->end_date))

    // Eager Load to avoid N+1 based on your Dumped Relations
    ->with([
        'client', 
        'details', 
        'Inconfirmation.firstStepStatu', 
        'items'
    ])
    ->latest()
    ->paginate(1, ['*'], 'inPage');
        $orders->withQueryString();
        $willayas = Willaya::all();
       
        return view('livewire.agent.order.inconfermation-manager', ['orders' => $orders,'wilayas'=>$willayas, 'firstStepStatus'=>$firstStepStatus ,'products'=>$products,'stores'=>$stores]);
    }
    

    //fillter
   
    public function Productfilter($id){
       
        $this->productfilter=$id;
         
    }
    public function Statufilter($id){
        $this->statufilter=$id;
    }

    public function toggleExpand($id)
    {
        if ($this->expandedOrderId === $id) {
            $this->resetActiveOrder();
            return;
        }

        $this->expandedOrderId = $id;
        $this->loadOrderData($id);
    }

    public function loadOrderData($id)
    {
      
        $this->activeOrder = Order::with([
            'client.willaya',
            'logs.user', 
            'logs.statusNew', 
            'logs.statusOld', 
            'Inconfirmation.firstStepStatu', 
            'details', 
            'items', 
            'items.variant.product',
            'Notes.user', 
            'chats.user', 
            'Type', 
            'histories'
        ])->where('oid',$id)->first();
        if ($this->activeOrder) {
      
            $this->client_name = $this->activeOrder->client->full_name ?? '';
            $this->phone1 = $this->activeOrder->client->phone_number_1 ?? '';
            $this->phone2 = $this->activeOrder->client->phone_number_2 ?? '';
            $this->wilaya = $this->activeOrder->client->wilaya ?? '';
            $this->city = $this->activeOrder->client->town ?? '';
            $this->address = $this->activeOrder->client->address ?? '';
            $this->delivery_type = $this->activeOrder->details->stopdesk ?? 0;
            $this->price = $this->activeOrder->details->price ?? 0;
            $this->delivery_price = $this->activeOrder->details->delivery_price ?? 0;
         
            $this->order_type = $this->activeOrder->Type->type_id ?? 1;
            $this->discount = 0; 
            $this->Comment = $this->activeOrder->details->commenter; 
            $this->selectedStatu = $this->activeOrder->Inconfirmation?->firstStepStatu->fsid;
          
            $this->calculateTotal();
             
            $this->items = $this->activeOrder->items->map(function ($item) {
            return [
                'id'         => $item->id,
                'vid'        => $item->vid,
                'product_id' => $item->variant?->product_id, 
                'sku'        => $item->sku,
                'quantity'   => $item->quantity,
                'original'      => $item->variant?->product->price,
                'product_name' => $item->variant?->product?->name ?? 'Unknown Product',
                'variant_info' => ($item->variant?->var_1 ?? '') . ' ' . ($item->variant?->var_2 ?? ''),
             ];
            })->toArray();

            
            $sid = $this->activeOrder->sid ?? '';
            $this->availableProducts = Product::where('sid',$sid)->with(['variants'])->get();
        }
        $this->updatedWilaya($this->wilaya);
    }
    
   public function proposeStatus($orderId, $statusId)
   {
    // If status is 2 (No Answer) or 3 (Postponed), show popup
       if (in_array((int)$statusId, [3, 4])) {
           $this->tempOrderId = $orderId;
           $this->tempStatusId = $statusId;
           $this->scheduleTime = now()->addHours(2)->format('Y-m-d\TH:i'); // Default 2 hours later
           $this->showTimerModal = true;
       } else {
        // Otherwise, update immediately
           $this->updateStatus($orderId, $statusId);
    }
   }
   
    public function confirmStatusWithTimer()
    {
        // Ensure we have an order ID and a time
        if (!$this->tempOrderId || !$this->scheduleTime) return;

        $order = Order::find($this->tempOrderId);

        if ($order && $order->Inconfirmation) {
            // 1. Capture the CURRENT status before we change it (for the logs)
            $oldStatusId = $order->Inconfirmation->fsid;

            // 2. Format the full Date and Time
            $formattedTime = \Carbon\Carbon::parse($this->scheduleTime)->format('Y-m-d H:i:s');

            // 3. Update the Order Status
            $order->Inconfirmation->update(['fsid' => $this->tempStatusId]);

            // 4. Create the Timer entry with the FULL Date/Time
            \App\Models\Timer::updateOrCreate(
                ['oid' => $order->oid],
                ['time' => $formattedTime] 
            );

            // 5. IMPORTANT: Add the Log entry so you don't get the 'statu_old' error
            \App\Models\order_logs::create([
                'oid'       => $order->oid,
                'aid'       => auth()->id() ?? 1,
                'statu_old' => $oldStatusId,
                'statu_new' => $this->tempStatusId,
                'text'      => 'Follow-up scheduled for: ' . $formattedTime,
            ]);

            // Sync UI
            $this->activeOrder = $order->load(['Inconfirmation.firstStepStatu', 'logs.user']);
            $this->showTimerModal = false;
            $this->selectedStatu = $this->tempStatusId;
            
            $this->dispatch('notify', message: 'Status updated and follow-up scheduled for ' . $formattedTime, type: 'success');
        }
    }
    
    public function updateStatus($orderId, $statusId)
    {
        $orderId = (int) $orderId;
        $statusId = (int) $statusId;
        $order = \App\Models\Order::find($orderId);
        
        if ($order && $order->Inconfirmation) {
            // FIX: Get the actual current status from the relationship
            $oldStatusId = $order->Inconfirmation->fsid;

            // Don't log if there's no change
            if ($oldStatusId == $statusId) return;

            // 1. Update the status
            $order->Inconfirmation->update([
                'fsid' => $statusId 
            ]);

            // 2. CREATE THE LOG ENTRY using the captured $oldStatusId
            \App\Models\order_logs::create([
                'oid'       => $order->oid,
                'aid'       => auth()->id() ?? 1,
                'statu_old' => $oldStatusId, // Guaranteed not null now
                'statu_new' => $statusId,
                'text'      => 'Status updated via confirmation manager.',
            ]);

            $this->selectedStatu = $statusId;

            // 3. Cleanup Timer
            if (!in_array($statusId, [2, 3])) {
                \App\Models\Timer::where('oid', $order->oid)->delete();
            }

            $this->activeOrder = $order->load(['Inconfirmation.firstStepStatu', 'logs.user']);
           
            $this->dispatch('notify', message: 'Status updated and history logged.', type: 'success');
        }
    }
    
    private function resetActiveOrder()
    {
        $this->expandedOrderId = null;
        $this->activeOrder = null;
        $this->items = [];
        $this->reset(['client_name','availableProducts', 'phone1', 'phone2', 'price', 'delivery_price', 'discount', 'total']);
    }

  

    public function addItem()
    {
        if (!$this->activeOrder) return;

        $newItemRecord = \App\Models\OrderItems::create([
            'oid' => $this->activeOrder->oid,
            'sku' => 0,
            'vid' => 0,
            'quantity' => 1
        ]);

        $this->items[] = [
            'id'           => $newItemRecord->id,
            'vid'          => '',
            'product_id'   =>  '',
            'sku'          => '',
            'original'          => '',
            'quantity'     => 1,
            'product_name' => 'New Product',
            'variant_info' => 'Select Variant',
        ];
    }
    
    public function updatedItems($value, $key)
    {
       if (strpos($key, '.sku') !== false) {
        $index = explode('.', $key)[0];
        $this->items[$index]['vid'] = ''; 
       }
        if (strpos($key, '.vid') !== false) {
        $index = explode('.', $key)[0];
        
   
        $variant = \App\Models\ProductVariant::with('product')->find($value);

        if ($variant) {
            $this->items[$index]['original'] = $variant->product->price;
            $this->items[$index]['vid'] = $variant->id;
            $this->items[$index]['sku'] = $variant->sku; 
            $this->items[$index]['product_name'] = $variant->product->name;
            $this->items[$index]['variant_info'] = $variant->var_1 . ' ' . $variant->var_2;
            
            $this->calculateTotal();
        }
       }
    }
    
    public function getVariants($sku)
    {
        return \App\Models\ProductVariant::where('sku', $sku)->get();
    }
    
    public function deleteItem($itemId, $index)
    {
        
        OrderItems::where('id', $itemId)->delete();
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    
    public function updated($propertyName)
    {
        
       if (in_array($propertyName, ['price', 'delivery_price', 'discount']) || strpos($propertyName, 'items') !== false) {
        $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
    $computedPrice = 0; 
    $delivery=null;
    foreach ($this->items as $item) {
        
        $price = (float) ($item['original'] ?? 0);
        $qty = (int) ($item['quantity'] ?? 1);
        $computedPrice += ($price * $qty); 
        if($delivery===null){
          if($this->delivery_type==1){
            $this->delivery_price = fees::where('pid',$item['sku'])->where('wid',$this->wilaya)->first()->c_s_p;
          }else{
            $this->delivery_price = fees::where('pid',$item['sku'])->where('wid',$this->wilaya)->first()->c_d_p  ?? 0;
          }
         
          $delivery=$this->delivery_price;
        }

    }

    $this->price = $computedPrice;

   
    $d = (float) ($this->delivery_price ?? 0);
    $dis = (float) ($this->discount ?? 0);

    
    $this->total = ($this->price + $d) - $dis;
}

    

    public function saveOrder()
    {
        if (!$this->activeOrder) return;

        try {
            // 1. Validate Input Fields
            $this->validate([
                'client_name'      => 'required|string|min:3',
                'phone1'           => ['required', 'regex:/^((05|06|07)[0-9]{8})$/'],
                'wilaya'           => 'required',
                'city'             => 'required',
                'address'          => 'required',
                'items'            => 'required|array|min:1',
                'items.*.vid'      => 'required|exists:product_variants,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            \DB::beginTransaction();

            // 2. Update Client Info
            if ($this->activeOrder->client) {
                $this->activeOrder->client->update([
                    'full_name'      => $this->client_name,
                    'phone_number_1' => $this->phone1,
                    'phone_number_2' => $this->phone2,
                    'wilaya'         => $this->wilaya,
                    'town'           => $this->city,
                    'address'        => $this->address,
                ]);
            }

            // 3. Update Order Details
            if ($this->activeOrder->details) {
                $this->activeOrder->details->update([
                    'commenter' => $this->Comment,
                    'stopdesk'  => $this->delivery_type,
                    'price'     => $this->total,
                ]);
            }

            // 4. Update Order Type
            $this->activeOrder->Type()->updateOrCreate(
                ['oid' => $this->activeOrder->oid],
                ['type_id' => $this->order_type]
            );

            // 5. Update Items
            foreach ($this->items as $itemData) {
                if (isset($itemData['id'])) {
                    \App\Models\OrderItems::where('id', $itemData['id'])->update([
                        'vid'      => $itemData['vid'],
                        'sku'      => $itemData['sku'],
                        'quantity' => $itemData['quantity'] ?? 1,
                    ]);
                }
            }

            // 6. Update Carrier (App ID)
            $firstItemSku = $this->items[0]['sku'] ?? null;
            $fee = fees::where('sid', $this->activeOrder->sid)
                ->where('pid', $firstItemSku)
                ->where('wid', $this->wilaya)
                ->first();

            if (!$fee) {
                throw new \Exception("No shipping fees found for this product in the selected Wilaya.");
            }

            $this->activeOrder->update(['app_id' => $fee->app_id]);

            \DB::commit();

            // Success Notification
            $this->dispatch('notify', message: 'Order updated successfully!', type: 'success');
            
            $this->loadOrderData($this->activeOrder->oid);
            $this->expandedOrderId = null;

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation Failed (Missing fields or wrong format)
            $this->dispatch('notify', message: 'Validation Error: Please check required fields.', type: 'error');
            throw $e;

        } catch (\Exception $e) {
            \DB::rollBack();
            
            // Generic Error (Database or Custom Exception)
            $this->dispatch('notify', message: 'Update Failed: ' . $e->getMessage(), type: 'error');
            
            \Log::error("Order Save Error: " . $e->getMessage());
        }
    }

    public function sendMessage()
    {
        if (empty($this->newMessage) || !$this->activeOrder) return;

        order_Comments::create([
            'oid' => $this->activeOrder->oid,
            'uid' => auth()->id() ?? 1, 
            'text' => $this->newMessage
        ]);

        $this->newMessage = '';
        $this->activeOrder->load('chats.user');
        
        $this->dispatch('notify', message: 'Message sent successfully!', type: 'success');
    }

    public function saveNote()
    {
        if (empty($this->newNote) || !$this->activeOrder) return;

        OrderNots::create([
            'oid' => $this->activeOrder->oid,
            'uid' => auth()->id() ?? 1,
            'text' => $this->newNote
        ]);
    
        $this->newNote = '';
        $this->activeOrder->load('Notes.user');
        
        $this->dispatch('notify', message: 'Note saved successfully!', type: 'success');
    }
    
    private function prepareOrderForApi($order)
    {
        return (object) [
            'oid'           => $order->oid,
            'ref'           => 'VN-' . $order->oid,
            'name'          => $order->client->full_name,
            'phone'         => $order->client->phone_number_1,
            'phone2'        => $order->client->phone_number_2,
            'address'       => $order->client->address,
            'city'          => $order->client->town,
            'wilaya'        => $order->client->wilaya,
            'total_price'   => $order->details->price,
            'delivery_type' => $order->details->stopdesk,
            'product_name'  => $order->items->map(function($item) {
                return ($item->variant?->product->name ?? 'Product') . " x" . $item->quantity;
            })->implode(' + '),
        ];
    }
    
    public function sendAllToShipping()
    { 
 
        $ordersToSend = Order::whereHas('Inconfirmation', fn($q) => $q->where('fsid', 2))
            ->where('aid', auth()->id())
            ->with(['client', 'details', 'items.variant.product'])
            ->get();

        if ($ordersToSend->isEmpty()) {
            $this->dispatch('notify', message: 'No confirmed orders available for shipping.', type: 'error');
            return;
        }

        // Group orders by carrier company since each has a separate API
        $groupedOrders = $ordersToSend->groupBy('app_id');
        $totalSuccess = 0;

        try {
            $switcher = new \App\Services\ShippingSwitcher();

            foreach ($groupedOrders as $appId => $ordersInGroup) {
                // Convert the group of orders to a standardized array
                $standardizedList = $ordersInGroup->map(fn($o) => $this->prepareOrderForApi($o))->toArray();

                // Send the group to the Switcher
                $bulkResult = $switcher->dispatch($standardizedList, $appId);

                // Process results (EcoTrack structure: results -> reference)
                if (isset($bulkResult['results'])) {
                    foreach ($ordersInGroup as $order) {
                        $ref = 'VN-' . $order->oid;
                        
                        if (isset($bulkResult['results'][$ref]) && $bulkResult['results'][$ref]['success']) {
                            $order->update([
                                'tracking' => $bulkResult['results'][$ref]['tracking'],
                            ]);
                            $order->Inconfirmation()->delete();
                            \App\Models\OrderWaiting::create(['oid' => $order->oid, 'asid' => 1]);
                            \App\Models\order_logs::create([
                            'oid'       => $order->oid,
                            'aid'       => auth()->id() ?? 1,
                            'statu_old' => 1,
                            'statu_new' => 1,
                            'text'      => 'Follow-up scheduled for: ' . $formattedTime,
                            ]);
                            $totalSuccess++;
                        }
                    }
                }
            }

            $this->resetActiveOrder();
            $this->dispatch('notify', message: "Operation complete: Successfully shipped $totalSuccess orders.", type: 'success');
        } catch (\Exception $e) {
            \Log::error("Bulk Dispatch Error: " . $e->getMessage());
            $this->dispatch('notify', message: "An error occurred during bulk shipping: " . $e->getMessage(), type: 'error');
        }
    }
    
    public function sendToShipping()
    {
        if (!$this->activeOrder) return;

        // 1. Save the updated data
        $this->saveOrder();

        // 2. Prepare data in Object format
        $orderData = $this->prepareOrderForApi($this->activeOrder);

        try {
            $switcher = new \App\Services\ShippingSwitcher();
            
            // Send the order, passing the correct app_id (e.g., 1001 or 1010)
            $result = $switcher->dispatch($orderData, $this->activeOrder->app_id);

            if ($result['success']) {
                $this->activeOrder->update([
                    'tracking' => $result['tracking'],
                    'status'   => 'shipped'
                ]);
                
                $this->resetActiveOrder();
                $this->dispatch('notify', message: "Shipped successfully! Tracking number: " . $result['tracking'], type: 'success');
            } else {
                $this->dispatch('notify', message: "Shipping failed: " . $result['message'], type: 'error');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', message: "System error: " . $e->getMessage(), type: 'error');
        }
    }
    
    public function updatedPhone1($value)
    {
        
        $cleanValue = str_replace([' ', '-', '.', '(', ')'], '', $value);
        if (str_starts_with($cleanValue, '+213')) {
            $cleanValue = '0' . substr($cleanValue, 4);
        }
        $this->phone1 = $cleanValue;

    }
}