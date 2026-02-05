<?php

namespace App\Livewire\V2\Order\Templates;

use Livewire\Component;
use App\Models\Order;
use App\Livewire\V2\Order\Traits\OrderTrait;

class OrderPrice extends Component
{
    use OrderTrait;
    public $order_price = 0;
    public $order_delivery_price = 0;
    public $order_discount = 0;
    public $order_total = 0;
    public $totalDiscount = 0;
    public Order $order;

    protected $listeners = [
        'orderTotalsUpdated' => 'syncTotals',
    ];

    public function mount(Order $activeOrder){
        $this->order = $activeOrder;
    }
    
    public function syncTotals($data)
    {
        $this->order_price          = $data['price'];
        $this->order_delivery_price = $data['delivery_price'];
        $this->order_discount       = $data['discount'];
        $this->order_total          = $data['total'];
        $this->totalDiscount  = $data['totalDiscount'];
    }

    public function updatedOrderDiscount($value){
        $this->order->details->update([
            'discount' => $value
        ]);
        $this->calculateTotal();
    }

    public function render()
    {
        return view('livewire.v2.order.templates.order-price');
    }
}
