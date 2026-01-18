<?php

namespace App\Livewire\V2\Order;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\OrderNots;
use App\Models\order_Comments;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SecondStepStatu;
use App\Models\willaya;
use App\Models\fees;
use App\Models\User;
use App\Services\TerritoryServices\ZRTerritoryService;
use App\Services\TerritoryServices\AndersonTerritoryService;
use App\Services\ShippingSwitcher;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule; 
use App\Livewire\V2\Order\Traits\OrderTrait;    
use Illuminate\Support\Facades\Auth;
class Indelivery extends Component
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
    public $order_type = 'Normal';


    public $price = 0;
    public $delivery_price = 0;
    public $discount = 0;
    public $total = 0;
    
    private $relationsToLoad = [
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
        'histories'
    ];

    public $items = []; 
    public $can_use_stopdesk = true;
 
    public $newNote = '';
    public $newMessage = '';

    public function render()
    {  
        $this->calculateTotal();
        $SecondStepStatus = SecondStepStatu::all(); 
        $products = Product::where('store_id', Auth::user()->store_id)->latest()
       ->get();  

       $orders = Order::query()->where('sid',Auth::user()->id)
        // 1. Filter by Status (Table: order_Indeliverys)
        // We use whereHas because every order in this view MUST have a confirmation record
        ->whereHas('Indelivery', function ($query) {
            if ($this->statufilter) {
                $query->where('ssid', $this->statufilter);
            } else {
                // Default view logic: Exclude specific statuses
                $query->whereNotIn('ssid', [2, 3]);
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
            'Indelivery.SecondStepStatu', 
            'items'
        ])
        ->latest()
        ->paginate(10, ['*'], 'inPage');
        $orders->withQueryString();
        $willayas = Willaya::all();
      
        return view('livewire.v2.order.indelivery', ['orders' => $orders,'wilayas'=>$willayas, 'SecondStepStatus'=>$SecondStepStatus ,'products'=>$products]);
    }
    
    public function proposeStatus($orderId, $statusId)
    {
        // If status is 2 (No Answer) or 3 (Postponed), show popup
        if (in_array((int)$statusId, [2, 3])) {
            $this->tempOrderId = $orderId;
            $this->tempStatusId = $statusId;
            $this->scheduleTime = now()->addHours(2)->format('Y-m-d\TH:i'); // Default 2 hours later
            $this->showTimerModal = true;
        } else {
            // Otherwise, update immediately
            $this->updateStatus($orderId, $statusId);
            }
    }
    public function confirmStatusWithTimer(){
        // Ensure we have an order ID and a time
        if (!$this->tempOrderId || !$this->scheduleTime) return;

        $order = Order::find($this->tempOrderId);

        if ($order && $order->Indelivery) {
            // 1. Capture the CURRENT status before we change it (for the logs)
            $oldStatusId = $order->Indelivery->ssid;

            // 2. Format the full Date and Time
            $formattedTime = \Carbon\Carbon::parse($this->scheduleTime)->format('Y-m-d H:i:s');

            // 3. Update the Order Status
            $order->Indelivery->update(['ssid' => $this->tempStatusId]);

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
            $this->activeOrder = $order->load(['Indelivery.SecondStepStatu', 'logs.user']);
            $this->showTimerModal = false;
            $this->selectedStatu = $this->tempStatusId;
            
            session()->flash('success', 'Status updated and follow-up scheduled for ' . $formattedTime);
        }
    }
public function updateStatus($orderId, $statusId)
{
    $orderId = (int) $orderId;
    $statusId = (int) $statusId;
    $order = \App\Models\Order::find($orderId);
    
    if ($order && $order->Indelivery) {
        // FIX: Get the actual current status from the relationship
        $oldStatusId = $order->Indelivery->ssid;

        // Don't log if there's no change
        if ($oldStatusId == $statusId) return;

        // 1. Update the status
        $order->Indelivery->update([
            'ssid' => $statusId 
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

        $this->activeOrder = $order->load(['Indelivery.SecondStepStatu', 'logs.user']);
        session()->flash('success', 'Status updated and history logged.');
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
    }
  
}