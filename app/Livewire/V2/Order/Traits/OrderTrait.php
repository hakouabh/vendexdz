<?php

namespace App\Livewire\V2\Order\Traits;

use Carbon\Carbon;
use App\Models\OrderItems;
use App\Models\ProductVariant;
use App\Models\fees;
use App\Models\Order;
use App\Models\Store;
use App\Models\Client;
use App\Models\Product;
use App\Models\OrderNots;
use App\Models\order_Comments;

trait OrderTrait
{
    public $items = [];
    public $availableProducts;
    public $delivery_type;
    public $order_type = 'Normal';
    public $delivery_price = 0;
    public $price = 0;
    public $discount = 0;
    public $total = 0;
    public $companie = 0;
    public $selectedWilayaId = null;
    public $expandedOrderId = null;
    public $stores;
    public $store_id;

    public Order $activeOrder;

    public function initializeStore()
    {
        $user = auth()->user(); 
        $query = $user->stores();
        // if (request()->is('agent/orders') || request()->is('manager/orders')) {
        //     $query->where('created_by', '!=', $user->id);
        // }

        if (request()->is('admin/orders')) {
            $query = Store::query(); // all stores
        }
        $this->stores = $query->latest()->get();
        $this->store_id = $this->stores->first()->id ?? null;
    }

    public function Storefilter($id){
        $this->storefilter = $id;
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
        $this->loadOrderData($id);
    }

    public function calculateTotal()
    {
        $orderItems = OrderItems::where('oid', $this->activeOrder->oid)->get();
        $client = Client::find($this->activeOrder->cid);
        $this->price = $orderItems->sum(function ($item) {
            $price = $item->variant ? $item->variant->product->price : 0;
            return $price * $item['quantity'];
        });
        if ($client->wilaya) {
            $fee = fees::where('product_id', $orderItems[0]['product_id'])
                ->where('wid', $client->wilaya)
                ->first();
            $this->delivery_price = $fee
                ? ($this->delivery_type ? $fee->c_s_p : $fee->c_d_p)
                : 0;
        }
        $this->total = ($this->price + $this->delivery_price) - ($this->discount ?? 0);
        $this->activeOrder->details->update([
            'total' => $this->total,
            'price' => $this->price,
            'delivery_price' => $this->delivery_price,
            'stopdesk' => $this->delivery_type ?? $this->activeOrder->details->stopdesk,
        ]);
        $this->dispatch('orderTotalsUpdated', [
            'price'          => $this->price,
            'delivery_price' => $this->delivery_price,
            'discount'       => $this->discount,
            'total'          => $this->total,
        ]);
    }
    protected function loadOrderData($oid)
    {
        $this->activeOrder = Order::with([
            'client.willaya',
            'logs.user', 
            'logs.statusNew', 
            'logs.statusOld', 
            'Inconfirmation.firstStepStatu', 
            'Waiting.AcceptStepStatu',
            'Indelivery.SecondStepStatu', 
            'details', 
            'items', 
            'items.variant.product',
            'Notes.user', 
            'chats.user', 
            'histories'
        ])->where('oid', $oid)
            ->first();

        if (!$this->activeOrder) return;

        $this->availableProducts = Product::where('store_id', $this->activeOrder->sid)
            ->with('variants')
            ->get();
    }
    
    public function getVariants($id)
    {
        $product = Product::find($id);
        return $product ? $product->variants : [];
    }

    public function proposeStatus($orderId, $statusId){
       if (in_array((int)$statusId, [3, 4])) {
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

            $order->update([
                'aid' => auth()->id(),
            ]);

            // 4. Create the Timer entry with the FULL Date/Time
            \App\Models\Timer::updateOrCreate(
                ['oid' => $order->oid],
                ['time' => $formattedTime] 
            );

            // 5. IMPORTANT: Add the Log entry so you don't get the 'statu_old' error
            \App\Models\order_logs::create([
                'oid'       => $order->oid,
                'aid'       => auth()->id(),
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

            $order->update([
                'aid' => auth()->id(),
            ]);

            // 2. CREATE THE LOG ENTRY using the captured $oldStatusId
            \App\Models\order_logs::create([
                'oid'       => $order->oid,
                'aid'       => auth()->id(),
                'statu_old' => $oldStatusId, // Guaranteed not null now
                'statu_new' => $statusId,
                'text'      => 'Status updated via confirmation manager.',
            ]);

            $this->selectedStatu = $statusId;

            // 3. Cleanup Timer
            if (!in_array($statusId, [3, 4])) {
                \App\Models\Timer::where('oid', $order->oid)->delete();
            }

            $this->activeOrder = $order->load(['Inconfirmation.firstStepStatu', 'logs.user']);
            session()->flash('success', 'Status updated and history logged.');
        }
    }
    private function resetActiveOrder()
    {
        $this->expandedOrderId = null;
        $this->reset(['availableProducts', 'price', 'delivery_price', 'discount', 'total']);
        $this->dispatch('orderItemsUpdated', []);
        $this->dispatch('orderTotalsUpdated', [
            'price'          => 0,
            'delivery_price' => 0,
            'discount'       => 0,
            'total'          => 0,
        ]);
    }
    public function saveNote()
    {
        if (empty($this->newNote) || !$this->activeOrder) return;

        OrderNots::create([
            'oid' => $this->activeOrder->oid,
            'uid' => auth()->id(),
            'text' => $this->newNote
        ]);
    
        $this->newNote = '';
        $this->activeOrder->load('Notes.user');
    }
}
