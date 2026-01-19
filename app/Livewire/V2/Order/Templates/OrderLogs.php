<?php

namespace App\Livewire\V2\Order\Templates;

use Livewire\Component;
use App\Models\Order;

class OrderLogs extends Component
{
    public Order $activeOrder;

    public function mount(Order $order)
    {
        $this->activeOrder = $order;
    }
    
    public function render()
    {
        return view('livewire.v2.order.templates.order-logs');
    }
}
