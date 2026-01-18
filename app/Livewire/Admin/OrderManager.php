<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Url; 
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\firstStepStatu;
use App\Models\willaya;
use App\Models\fees;
use App\Services\TerritoryServices\ZRTerritoryService;
use App\Services\TerritoryServices\AndersonTerritoryService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderManager extends Component
{

     #[Url(keep: true)] 
    public $currentTab = 'inconfirmation';
    public $isCreating = false;
    // Customer Information
    public $client_name;
    public $phone1;
    public $phone2;
    public $wilaya;
    public $city;
    public $address;
    public $comment;

    // Order Configuration
    public $delivery_type = 1;
    public $order_type = 'Normal';
    public $companie = 0;
    public $communes = [];
    public $selectedWilayaId = null;

    // Pricing
    public $price = 0;
    public $delivery_price = 0;
    public $discount = 0;
    public $total = 0;

    // Order Items
    public $items = [];
    public $availableProducts;
    public $can_use_stopdesk = true;

    // UI State
   public $isSubmitting = false; // Controls button state
   public $showSuccessModal = false; // Controls visibility
   public $createdOrder = null; // Holds the order object after creation
   public $createNeworeder =false;
    protected $rules = [
        'client_name' => 'required|string|min:3',
        'phone1' => ['required','regex:/^((05|06|07)[0-9]{8})$/'],
        'wilaya' => 'required',
        'city' => 'required',
        'address' => 'required',
        'items' => 'required|array|min:1',
        'items.*.vid' => 'required|exists:product_variants,id',
        'items.*.quantity' => 'required|integer|min:1',
    ];

    protected $messages = [
        'client_name.required' => 'Customer name is required',
        'client_name.min' => 'Customer name must be at least 3 characters',
        'phone1.required' => 'Primary phone number is required',
        'phone1.digits' => 'Phone number must be exactly 10 digits',
        'wilaya.required' => 'Please select a wilaya',
        'city.required' => 'Please enter a city/town',
        'address.required' => 'Please enter delivery address',
        'items.required' => 'At least one item is required',
        'items.*.vid.required' => 'Please select a product variant for all items',
        'items.*.quantity.required' => 'Quantity is required for all items',
        'items.*.quantity.min' => 'Quantity must be at least 1',
    ];

    public function mount()
    {
        $this->initializeOrder();
        $this->loadAvailableProducts();
    }

    private function initializeOrder()
    {
        $this->items = [
            [
                'id' => null,
                'vid' => '',
                'product_id' => '',
                'sku' => '',
                'quantity' => 1,
                'original' => 0,
                'product_name' => 'Select Product',
                'variant_info' => 'Select Variant',
            ]
        ];
    }

    private function loadAvailableProducts()
    {
        $this->availableProducts = Product::with(['variants'])->get();
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

        $this->calculateTotal();
    }

    public function updatedWilaya($value)
    {
        if (!$value) {
            $this->reset(['communes', 'selectedWilayaId', 'city', 'can_use_stopdesk']);
            return;
        }

        $firstItemSku = $this->items[0]['sku'] ?? null;
        
        if ($firstItemSku) {
            $fee = fees::where('pid', $firstItemSku)
                ->where('wid', $value)
                ->first();
            
            if ($fee) {
                $this->companie = $fee->app_id;
            }
        }

        switch ($this->companie) {
            case 1001:
                $service = new AndersonTerritoryService();
                $data = $service->getEverythingCached();
                $this->communes = $data['communes'][$value] ?? [];
                break;
            case 1010:
                $service = new ZRTerritoryService();
                $data = $service->getEverythingCached();
                $zrWilaya = $data['wilayas'][$value] ?? null;
                if ($zrWilaya) {
                    $this->selectedWilayaId = $zrWilaya['id'];
                    $this->communes = $data['communes'][$this->selectedWilayaId] ?? [];
                } else {
                    $this->communes = [];
                }
                break;
            default:
                $this->communes = [];
                break;
        }

        $this->reset(['city', 'can_use_stopdesk']);
    }

    public function updatedItems($value, $key)
    {
        if (strpos($key, '.sku') !== false) {
            $index = explode('.', $key)[0];
            $this->items[$index]['vid'] = '';
        }

        if (strpos($key, '.vid') !== false) {
            $index = explode('.', $key)[0];
            
            $variant = ProductVariant::with('product')->find($value);

            if ($variant) {
                $this->items[$index]['original'] = $variant->product->price;
                $this->items[$index]['vid'] = $variant->id;
                $this->items[$index]['sku'] = $variant->sku;
                $this->items[$index]['product_name'] = $variant->product->name;
                $this->items[$index]['variant_info'] = $variant->var_1 . ' ' . $variant->var_2;
                
                $this->calculateTotal();
            }
        }

        if (in_array($key, ['price', 'delivery_price', 'discount']) || strpos($key, 'items') !== false) {
            $this->calculateTotal();
        }
    }

    public function getVariants($sku)
    {
        return ProductVariant::where('sku', $sku)->get();
    }

    public function addItem()
    {
        $this->items[] = [
            'id' => null,
            'vid' => '',
            'product_id' => '',
            'sku' => '',
            'quantity' => 1,
            'original' => 0,
            'product_name' => 'Select Product',
            'variant_info' => 'Select Variant',
        ];
    }

    public function deleteItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->calculateTotal();
        } else {
            $this->dispatch('showErrorToast', 'At least one item is required');
        }
    }

    public function calculateTotal()
    {
        $computedPrice = 0;
        $delivery = null;

        foreach ($this->items as $item) {
            $price = (float) ($item['original'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 1);
            $computedPrice += ($price * $qty);

            if ($delivery === null && !empty($item['sku']) && !empty($this->wilaya)) {
                $fee = fees::where('pid', $item['sku'])
                    ->where('wid', $this->wilaya)
                    ->first();
                
                if ($fee) {
                    if ($this->delivery_type == 1) {
                        $this->delivery_price = $fee->c_s_p;
                    } else {
                        $this->delivery_price = $fee->c_d_p ?? 0;
                    }
                    $delivery = $this->delivery_price;
                }
            }
        }

        $this->price = $computedPrice;
        $d = (float) ($this->delivery_price ?? 0);
        $dis = (float) ($this->discount ?? 0);
        $this->total = ($this->price + $d) - $dis;
    }

    public function createOrder()
{           
    $this->validate();
 
    \DB::beginTransaction();

    try {
       
        $client = Client::updateOrCreate(
            ['phone_number_1' => $this->phone1],
            [
                'full_name' => $this->client_name,
                'phone_number_2' => $this->phone2,
                'wilaya' => $this->wilaya,
                'town' => $this->city,
                'address' => $this->address,
            ]
        );
         
    
        $firstItemSku = $this->items[0]['sku'] ?? null;
        $app_id = 0;
        
        if ($firstItemSku) {
            $fee = fees::where('pid', $firstItemSku)
                ->where('wid', $this->wilaya)
                ->first();
            
            if ($fee) {
                $app_id = $fee->app_id;
            }
        }
      
     
        $order = Order::create([
            'oid' => time() . mt_rand(1000, 9999),
            'cid' => $client->id,
            'sid' => Auth::User()->id, 
            'app_id' => $app_id,
        ]);
        
        $details = $order->details()->create([
            'oid' => $order->oid,
            'price' => $this->total,
            'total' => $this->total,
            'delivery_price' => $this->delivery_price,
            'commenter' => $this->comment,
            'stopdesk' => $this->delivery_type,
        ]);
       
     
        foreach ($this->items as $item) {
            OrderItems::create([
                'oid' => $order->oid,
                'sku' => $item['sku'],
                'vid' => $item['vid'],
                'quantity' => $item['quantity'],
            ]);
        }

       
        $Inconfirmationd = $order->Inconfirmation()->create([
            'fsid' => 1,
            'aid' => Auth::id(),
        ]);
         
       
    
       
        \DB::commit();
        
        $this->createdOrder = $order;
        $this->showSuccessModal = true;
        $this->resetForm();
        $this->dispatch('showSuccessToast', 'Order created successfully!');
      
    } catch (\Exception $e) {
   
        \DB::rollBack();

        Log::error('Order creation failed and rolled back: ' . $e->getMessage());
        $this->dispatch('showErrorToast', 'Failed to create order. All changes reverted.');
    }
}

    public function resetForm()
    {
        $this->reset([
            'client_name', 'phone1', 'phone2', 'wilaya', 'city', 'address',
            'comment', 'delivery_type', 'order_type', 'companie', 'communes',
            'selectedWilayaId', 'price', 'delivery_price', 'discount', 'total'
        ]);
        $this->initializeOrder();
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->createdOrder = null;
    }

   

       
    public function setTab($tab)
    {
        $this->currentTab = $tab;
    }

    public function render()
    {
        $this->calculateTotal();
        $willayas = willaya::all();
        $firstStepStatus = firstStepStatu::all();

        return view('livewire.admin.order-manager', [
            'willayas' => $willayas,
            'firstStepStatus' => $firstStepStatus
        ]);
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
