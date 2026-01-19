<?php

namespace App\Livewire\V2\Order\Templates;

use Livewire\Component;

class OrderPrice extends Component
{
    public $price = 0;
    public $delivery_price = 0;
    public $discount = 0;
    public $total = 0;
    protected $listeners = [
        'orderTotalsUpdated' => 'syncTotals',
    ];
    public function syncTotals($data)
    {
        $this->price          = $data['price'];
        $this->delivery_price = $data['delivery_price'];
        $this->discount       = $data['discount'];
        $this->total          = $data['total'];
    }

    public function render()
    {
        return view('livewire.v2.order.templates.order-price');
    }
}
