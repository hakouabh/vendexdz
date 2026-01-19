<?php

namespace App\Livewire\V2\Order\Templates;

use Livewire\Component;
use App\Models\Order;
use App\Models\order_Comments;

class OrderChat extends Component
{
    public Order $activeOrder;
    public string $newMessage = '';

    public function mount(Order $order)
    {
        $this->activeOrder = $order;
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
    public function render()
    {
        return view('livewire.v2.order.templates.order-chat');
    }
}
