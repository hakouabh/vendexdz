<?php

namespace App\Livewire\V2\Order\Templates;

use Livewire\Component;
use App\Livewire\V2\Order\Traits\OrderTrait;
use App\Models\Order;
use App\Models\OrderItems as OrderItem;
use App\Models\ProductVariant;

class OrderItems extends Component
{
    use OrderTrait;

    public Order $activeOrder;
    public $items = [];
    public $canUpdate;
    
    protected $listeners = [
        'orderItemsUpdated' => 'syncItems',
    ];

    public function mount(Order $activeOrder, $availableProducts, $canUpdate = true)
    {
        $this->activeOrder = $activeOrder;
        $this->canUpdate = $canUpdate;
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
        
        $this->availableProducts = $availableProducts;
    }

    public function syncItems($items)
    {
        $this->items = $items;
    }

    public function addItem()
    {
        if (!$this->activeOrder) return;

        $item = OrderItem::create([
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
            'discount' => 0,
            'quantity' => 1,
            'original' => 0,
            'product_name' => '',
            'variant_info' => '',
        ];
    }

    public function updatedItems($value, $key)
    {
        if (str_contains($key, '.vid')){
            $index = explode('.', $key)[0];
            $variant = ProductVariant::with('product')->find($value);

            if (!$variant) return;

            $orderItem = OrderItem::where('id', $this->activeOrder->items[$index]['id'])->update([
                'vid' => $variant->id,
                'product_id' => $variant->product_id,
                'quantity' => $this->items[$index]['quantity'] ?? 1,
            ]);
            if ($this->activeOrder->Waiting || $this->activeOrder->Inconfirmation->fsid == 2){
                $variant->decrement('quantity', 1);
            }
        }
        else if( str_contains($key, '.quantity')){
            $index = explode('.', $key)[0];
            $orderItem = OrderItem::where('id', $this->activeOrder->items[$index]['id'])->first();
            if($this->activeOrder->Waiting || $this->activeOrder->Inconfirmation->fsid == 2){
                if($orderItem->variant && $orderItem->variant->quantity == 0) return;
                    $orderItem->variant->decrement('quantity', 1);
            }
            $orderItem->update([
                'quantity' => $value
            ]);
        }
        $this->calculateTotal();
    }

    public function deleteItem($itemId, $index)
    {
        if(count($this->items) == 1) return;
        $orderItem = OrderItem::where('id', $itemId)->first();
        if($this->activeOrder->Waiting || $this->activeOrder->Inconfirmation->fsid == 2){
            $orderItem->variant->increment('quantity', $orderItem->quantity);
        }
        $orderItem->delete();
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function render()
    {
        return view('livewire.v2.order.templates.order-items');
    }
}
