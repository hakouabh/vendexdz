<?php

namespace App\Livewire\V2\Order\Templates;

use Livewire\Component;
use App\Models\Order;

class OrderPrice extends Component
{
    public $price = 0;
    public $delivery_price = 0;
    public $discount = 0;
    public $total = 0;
    public $totalDiscount = 0;
    public Order $activeOrder;

    protected $listeners = [
        'orderTotalsUpdated' => 'syncTotals',
    ];

    public function mount(Order $activeOrder){
        $this->activeOrder = $activeOrder;
    }
    
    public function syncTotals($data)
    {
        $this->price          = $data['price'];
        $this->delivery_price = $data['delivery_price'];
        $this->discount       = $data['discount'];
        $this->total          = $data['total'];
        $this->totalDiscount  = $data['totalDiscount'];
    }

    public function updatedDiscount($value){
        $this->activeOrder->details->update([
            'discount' => $value
        ]);
    }

    public function render()
    {
        return view('livewire.v2.order.templates.order-price');
    }
}
