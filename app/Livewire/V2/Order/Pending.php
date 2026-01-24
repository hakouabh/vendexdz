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
use App\Models\AcceptStepStatu;
use App\Models\willaya;
use App\Models\OrderWaiting;
use App\Models\OrderInconfirmation;
use App\Models\fees;
use App\Models\User;
use App\Services\TerritoryServices\ZRTerritoryService;
use App\Services\TerritoryServices\AndersonTerritoryService;
use App\Services\ShippingSwitcher;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;     
use Illuminate\Support\Facades\Auth;
use App\Services\RemoveOrderSwitcher;

class Pending extends Component
{
    use \App\Livewire\V2\Order\Traits\OrderTrait;

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

    protected $listeners = ['orderSaved' => 'syncOrder'];
    
    public function render()
    {
        $AcceptStepStatus = AcceptStepStatu::all(); 
        $products = Product::where('store_id', Auth::user()->store_id)->latest()
            ->get();         
        $orders = Order::query()->where('sid',Auth::user()->id)
        // 1. Filter by Status (Table: order_Waitings)
        // We use whereHas because every order in this view MUST have a confirmation record
        ->whereHas('Waiting', function ($query) {
            if ($this->statufilter) {
                $query->where('asid', $this->statufilter);
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
        ->when($this->start_date, fn($q) => $q->whereDate('created_at', '>=', $this->start_date))
        ->when($this->end_date, fn($q) => $q->whereDate('created_at', '<=', $this->end_date))
        ->with([
            'client', 
            'details', 
            'Waiting.AcceptStepStatu', 
            'items'
        ])
        ->latest()
        ->paginate(10, ['*'], 'inPage');
        $orders->withQueryString();
        return view('livewire.v2.order.pending', ['orders' => $orders, 'AcceptStepStatus'=>$AcceptStepStatus ,'products'=>$products]);
    }
    public function RemoveNow()
    {  
        $switcher = new RemoveOrderSwitcher();
        $result = $switcher->validate($this->activeOrder);
        if (isset($result['delete']) && $result['delete'] == 'success') {
            OrderWaiting::where('oid', $this->activeOrder->oid)->delete();
            OrderInconfirmation::create(['oid'=>$this->activeOrder->oid,'fsid'=>1]);
            $this->activeOrder->update(['tracking' => null]);
            $this->dispatch('notify', type: 'success', message: 'Order is Removed !');
        } else {
            $this->dispatch('notify', type: 'error', message: $result['message'] ?? 'Carrier refused validation.');
        }
    }
    public function shipNow()
    {  
        $switcher = new ShipOrderSwitcher();
        $result = $switcher->validate($this->activeOrder);
        \Log::alert($result);
        if (isset($result['success']) && $result['success']) {

            $this->dispatch('notify', type: 'success', message: 'Order is ready for pickup!');
        } else {
            $this->dispatch('notify', type: 'error', message: $result['message'] ?? 'Carrier refused validation.');
        }
    }
    public function syncOrder(){
        $this->loadOrderData($this->activeOrder->oid);
        $this->expandedOrderId =null;
    }
}