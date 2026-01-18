<?php

namespace App\Livewire\V2\Order\Traits;

use Carbon\Carbon;
use App\Models\OrderItems;
use App\Models\ProductVariant;
use App\Models\fees;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderNots;
use App\Models\order_Comments;
use App\Services\TerritoryServices\ZRTerritoryService;
use App\Services\TerritoryServices\AndersonTerritoryService;

trait OrderTrait
{
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
        $firstItemSku = $this->items[0]['product_id'] ?? null;

        $app_id = fees::where('sid',$this->activeOrder->sid)
            ->where('product_id', $firstItemSku)
            ->where('wid',$this->wilaya)->first()->app_id;
        $this->companie= $app_id;
        switch ($this->companie){
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

    public function Storefilter($id){
        $this->storefilter=$id;
    }

    public function Productfilter($id){
        $this->productfilter = $id;
    }

    public function Statufilter($id){
        $this->statufilter = $id;
    }

    public function toggleExpand($id)
    {
        if ($this->expandedOrderId === $id) {
            $this->resetActiveOrder();
            return;
        }

        $this->expandedOrderId = $id;
        $this->loadOrderData($id, $this->relationsToLoad);
    }
    
    public function addItem()
    {
        if (!$this->activeOrder) return;

        $item = OrderItems::create([
            'oid' => $this->activeOrder->oid,
            'sku' => 0,
            'vid' => 0,
            'product_id' => null,
            'quantity' => 1
        ]);

        $this->items[] = [
            'id' => $item->id,
            'vid' => '',
            'sku' => '',
            'product_id' => null,
            'quantity' => 1,
            'original' => 0,
            'product_name' => '',
            'variant_info' => '',
        ];
    }

    public function updatedItems($value, $key)
    {
        if (!str_contains($key, '.vid')) return;

        $index = explode('.', $key)[0];
        $variant = ProductVariant::with('product')->find($value);

        if (!$variant) return;

        $this->items[$index]['vid'] = $variant->id;
        $this->items[$index]['sku'] = $variant->sku;
        $this->items[$index]['product_id'] = $variant->product_id;
        $this->items[$index]['original'] = $variant->product->price;
        $this->items[$index]['product_name'] = $variant->product->name;
        $this->items[$index]['variant_info'] = trim($variant->var_1.' '.$variant->var_2);

        $this->calculateTotal();
    }

    public function deleteItem($itemId, $index)
    {
        OrderItems::where('id', $itemId)->delete();
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function calculateTotal()
    {
        $this->price = collect($this->items)->sum(fn ($i) =>
            ((float)($i['original'] ?? 0)) * ((int)($i['quantity'] ?? 1))
        );

        if (!empty($this->items) && $this->wilaya) {
            $fee = Fees::where('product_id', $this->items[0]['product_id'])
                ->where('wid', $this->wilaya)
                ->first();

            $this->delivery_price = $fee
                ? ($this->delivery_type ? $fee->c_s_p : $fee->c_d_p)
                : 0;
        }

        $this->total = ($this->price + $this->delivery_price) - ($this->discount ?? 0);
    }
    protected function loadOrderData($oid, array $relations)
    {
        $this->activeOrder = Order::with($relations)
            ->where('oid', $oid)
            ->first();

        if (!$this->activeOrder) return;

        $client = $this->activeOrder->client;

        $this->client_name = $client->full_name ?? '';
        $this->phone1 = $client->phone_number_1 ?? '';
        $this->phone2 = $client->phone_number_2 ?? '';
        $this->wilaya = $client->wilaya ?? '';
        $this->address = $client->address ?? '';

        $this->items = $this->activeOrder->items->map(fn ($item) => [
            'id' => $item->id,
            'vid' => $item->vid,
            'sku' => $item->sku,
            'quantity' => $item->quantity,
            'product_id' => $item->product_id,
            'original' => $item->variant?->product->price ?? 0,
            'product_name' => $item->variant?->product->name ?? '',
            'variant_info' => trim(($item->variant?->var_1 ?? '').' '.($item->variant?->var_2 ?? '')),
        ])->toArray();

        $this->availableProducts = Product::where('created_by', $this->activeOrder->sid)
            ->with('variants')
            ->get();

        $this->calculateTotal();
        $this->updatedWilaya($this->wilaya);
        $this->city = $client->town ?? '';
    }
    // public function calculateTotal()
    // {
    //     $computedPrice = 0; 
    //     $delivery=null;
    //     foreach ($this->items as $item) {
            
    //         $price = (float) ($item['original'] ?? 0);
    //         $qty = (int) ($item['quantity'] ?? 1);
    //         $computedPrice += ($price * $qty); 
    //         if($delivery===null){
    //         if($this->delivery_type==1){
    //             $this->delivery_price = fees::where('product_id',$item['product_id'])->where('wid',$this->wilaya)->first()->c_s_p;
    //         }else{
    //             $this->delivery_price = fees::where('product_id',$item['product_id'])->where('wid',$this->wilaya)->first()->c_d_p  ?? 0;
    //         }
            
    //         $delivery=$this->delivery_price;
    //         }

    //     }

    //     $this->price = $computedPrice;

   
    //     $d = (float) ($this->delivery_price ?? 0);
    //     $dis = (float) ($this->discount ?? 0);

    //     $this->total = ($this->price + $d) - $dis;
    // }
    public function getVariants($id)
    {
        $product = Product::find($id);
        return $product ? $product->variants : [];
    }

    public function saveOrder()
    {   
        if (!$this->activeOrder) return;
        $this->validate([
        'client_name'   => 'required|string|min:3',
        'phone1'        => ['required','regex:/^((05|06|07)[0-9]{8})$/'],
        'wilaya'        => 'required',
        'city'          => 'required',
        'address'          => 'required',
        'items'         => 'required|array|min:1',
        'items.*.vid'   => 'required|exists:product_variants,id',
        'items.*.quantity' => 'required|integer|min:1',
        ]);
        if ($this->activeOrder->client) {
            $this->activeOrder->client->update([
                'full_name' => $this->client_name,
                'phone_number_1' => $this->phone1,
                'phone_number_2' => $this->phone2,
                'wilaya' => $this->wilaya,
                'town' => $this->city,
                'address' => $this->address,
            ]);
        }
        if ($this->activeOrder->details) {
            $this->activeOrder->details->update([
                'commenter' => $this->Comment,
                'stopdesk' => $this->delivery_type,
                'price' => $this->total,
            ]);
        }
        foreach ($this->items as $itemData) {
            if (isset($itemData['id'])) {
                OrderItems::where('id', $itemData['id'])->update([
                    'vid' => $itemData['vid'],
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'] ?? 1,
                ]);
            }
        }
        $firstItemSku = $this->items[0]['product_id'] ?? null;
        $app_id = fees::where('sid',$this->activeOrder->sid)
            ->where('product_id', $firstItemSku)
            ->where('wid',$this->wilaya)->first()->app_id;
        $this->activeOrder->update([
            'app_id' => $app_id
        ]);
        $this->loadOrderData($this->activeOrder->oid, $this->relationsToLoad);
        $this->expandedOrderId =null;
    }

    public function proposeStatus($orderId, $statusId){
       if (in_array((int)$statusId, [2, 3])) {
           $this->tempOrderId = $orderId;
           $this->tempStatusId = $statusId;
           $this->scheduleTime = now()->addHours(2)->format('Y-m-d\TH:i'); // Default 2 hours later
           $this->showTimerModal = true;
       } else {
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
            $formattedTime = Carbon::parse($this->scheduleTime)->format('Y-m-d H:i:s');

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
            
            session()->flash('success', 'Status updated and follow-up scheduled for ' . $formattedTime);
        }
    }
    public function updateStatus($orderId, $statusId)
    {
        $orderId = (int) $orderId;
        $statusId = (int) $statusId;
        $order = Order::find($orderId);
        
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
    public function updated($propertyName)
    {
        
       if (in_array($propertyName, ['price', 'delivery_price', 'discount']) || strpos($propertyName, 'items') !== false) {
        $this->calculateTotal();
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
    }
    private function getStandardizedData()
    {
        return (object) [
            'ref'           => 'VN-'.$this->activeOrder->id,
            'name'          => $this->client_name,
            'phone'         => $this->phone1,
            'phone2'        => $this->phone2,
            'address'       => $this->address,
            'city'          => $this->city,
            'wilaya'        => $this->wilaya,
            'total_price'   => $this->total,
            'delivery_type' => $this->delivery_type,
            'note'          => $this->newNote ?: ($this->activeOrder->details->note ?? ''),
            'product_name'  => collect($this->items)->map(function($item) {
                $name = $item['product_name'];
                $variant = !empty($item['variant_info']) ? " ({$item['variant_info']})" : "";
                $qty = " x" . ($item['quantity'] ?? 1);
                
                return $name . $variant . $qty;
            })->implode(' + '),
            'quantity'      => collect($this->items)->sum('quantity'),
        ];
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
