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
use App\Services\ShippingSwitcher;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule; 
use App\Livewire\V2\Order\Traits\OrderTrait;    
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
    public $stores;

    public $activeTab = 'chat'; 


    public string $context;
    
    public function mount($context){
        $this->context = $context;
        $this->initializeStore();
    }

    public function render()
    { 
        $SecondStepStatus = SecondStepStatu::all(); 
        $products = Product::where('store_id', $this->store_id)->latest()->get();  
       $orders = Order::query()->whereIn('sid',$this->stores->pluck('id'))
        ->whereHas('Indelivery', function ($query) {
            if ($this->statufilter) {
                $query->where('ssid', $this->statufilter);
            } else {
                // Default view logic: Exclude specific statuses
                $query->whereNotIn('ssid', [3, 4]);
            }
        })
        ->when($this->storefilter, function ($query) {
            $query->where('sid', $this->storefilter);
        })

        // 3. Product SKU Filter (Table: order_items)
        ->when($this->productfilter, function ($query) {
            $query->whereHas('items', function ($q) {
                $q->where('product_id',$this->productfilter);
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
      
        return view('livewire.v2.order.indelivery', ['orders' => $orders, 'SecondStepStatus'=>$SecondStepStatus ,'products'=>$products]);
    }
}