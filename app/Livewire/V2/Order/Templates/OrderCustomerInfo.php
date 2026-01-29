<?php

namespace App\Livewire\V2\Order\Templates;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderWaiting;
use App\Models\OrderInconfirmation;
use App\Models\Willaya;
use App\Models\Client;
use App\Models\Fees;
use App\Models\InstalledApps;
use App\Livewire\V2\Order\Traits\OrderTrait;
use App\Services\TerritoryServices\ZRTerritoryService;
use App\Services\TerritoryServices\AndersonTerritoryService;
use App\Services\EditeOrderSwitcher;

class OrderCustomerInfo extends Component
{
    use OrderTrait;
    
    public Client $client;
    public Order $activeOrder;
    public $wilaya;
    public $city;
    public $communes = [];
    public $client_name;
    public $phone1;
    public $phone2;
    public $address;
    public $Comment;
    public $can_use_stopdesk = true;
    public $type = 'normal';
    public $canUpdate;

    protected $listeners = [
        'customerInfoUpdated' => 'syncCustomerData',
        'deliveryTypeUpdated' => 'setDelivery',
        'setUpDelivery' => 'sendToShipping',
        'updateDeliveryInfo' => 'updateDelivery'
    ];

    public function mount(Order $activeOrder, $canUpdate = true){

        $this->activeOrder = $activeOrder;
        $this->client = $activeOrder->client;
        $this->client_name = $this->client->full_name ?? '';
        $this->phone1 = $this->client->phone_number_1 ?? '';
        $this->phone2 = $this->client->phone_number_2 ?? '';
        $this->address = $this->client->address ?? '';
        $this->wilaya = $this->client->wilaya ?? '';
        $this->type = $activeOrder->type;
        $this->Comment = $activeOrder->details->commenter ?? '';
        $this->delivery_type = $activeOrder->details->stopdesk;
        $this->updatedWilaya($this->wilaya);
        $this->city = $this->client->town ?? '';
        $this->updatedCity($this->city);
        $this->canUpdate = $canUpdate;
    }

    public function syncCustomerData()
    {
        $this->validate([
            'client_name'   => 'required|string|min:3',
            'phone1'        => ['required','regex:/^((05|06|07)[0-9]{8})$/'],
            'address'       => 'required|string|min:5',
        ]);

        $this->client->full_name = $this->client_name;
        $this->client->phone_number_1 = $this->phone1;
        $this->client->phone_number_2 = $this->phone2;
        $this->client->address = $this->address;
        $this->client->save();

        $this->activeOrder->details->update([
            'commenter' => $this->Comment
        ]);
        $this->dispatch('orderSaved');
    }

    public function updateDelivery(){
        
        $this->validate([
            'client_name'   => 'required|string|min:3',
            'phone1'        => ['required','regex:/^((05|06|07)[0-9]{8})$/'],
            'address'       => 'required|string|min:5',
        ]);

        $this->client->full_name = $this->client_name;
        $this->client->phone_number_1 = $this->phone1;
        $this->client->phone_number_2 = $this->phone2;
        $this->client->address = $this->address;
        $this->client->save();

        $this->activeOrder->details->update([
            'commenter' => $this->Comment,
        ]);
        
        if (!$this->activeOrder) return;

        $standardOrder = $this->getStandardizedData();
   
        try {
            $switcher = new EditeOrderSwitcher();
        
            $result = $switcher->dispatch($this->activeOrder, $standardOrder);
            if ($result['success']) {
                $this->dispatch('notify', type: 'success', message: $result['message']);
                $this->dispatch('orderSaved');
            } else {
                $this->dispatch('notify', type: 'error', message: $result['message']);
            }
        } catch (\Exception $e) {
                $this->dispatch('notify', type:'error', message: "Error: " . $e->getMessage());
        }
    }

    public function sendToShipping()
    {
        $this->validate([
            'client_name'   => 'required|string|min:3',
            'phone1'        => ['required','regex:/^((05|06|07)[0-9]{8})$/'],
            'address'       => 'required|string|min:5',
        ]);

        $this->client->full_name = $this->client_name;
        $this->client->phone_number_1 = $this->phone1;
        $this->client->phone_number_2 = $this->phone2;
        $this->client->address = $this->address;
        $this->client->save();

        $this->activeOrder->details->update([
            'commenter' => $this->Comment,
        ]);
        
        if (!$this->activeOrder) return;

        // This creates the stdClass (Standardized Object)
        $standardOrder = $this->getStandardizedData();

        try {
            $switcher = new \App\Services\ShippingSwitcher();
            
            // This now sends the stdClass to the updated dispatch method
            $result = $switcher->dispatch($standardOrder, $this->activeOrder);
            if ($result['success']) {
                $this->activeOrder->update([
                    'tracking' => $result['tracking'],
                    'custom_id' => $result['parcelId']
                ]);
                OrderInconfirmation::where('oid', $this->activeOrder->oid)->delete();
                OrderWaiting::create(['oid'=>$this->activeOrder->oid,'asid'=>1]);
                $this->dispatch('notify', type: 'success', message: "Dispatched! Tracking: " . $result['tracking']);
                $this->dispatch('orderSaved');
            } else {
                $this->dispatch('notify', type: 'error', message: $result['message']);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', type:'error', message: "Error: " . $e->getMessage());
        }
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
        $this->client->town = $value;
        $this->client->save();
        $this->calculateTotal();
    }

    public function updatedWilaya($value)
    {
        if (!$value) {
            $this->reset(['communes', 'selectedWilayaId', 'city', 'can_use_stopdesk']);
            return;
        }
        $firstItemSku = $this->activeOrder->items[0]['product_id'] ?? null;

        $app_id = Fees::where('sid',$this->activeOrder->sid)
            ->where('product_id', $firstItemSku)
            ->where('wid',$this->wilaya)->first()->app_id;
        $this->companie= $app_id;
        $installedApp = InstalledApps::where('sid',$this->activeOrder->sid)->where('app_id', $this->companie)->first();
        switch ($this->companie){
            case 1001:
            case 1002:
            case 1003:
                $service = new AndersonTerritoryService($installedApp);
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
        $this->client->wilaya = $value;
        $this->client->save();
        $this->reset(['city', 'can_use_stopdesk']);
    }

    public function setDelivery($type)
    {
        if ($type == '1' && !$this->can_use_stopdesk) {
            return;
        }
        $this->delivery_type = $type;
        $this->calculateTotal();
    }

    public function setType($type){
        $this->type = $type;
        $this->activeOrder->update([
            'type' => $type
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
            'note'          => $this->Comment,
            'product_name'  => collect($this->activeOrder->items)->map(function($item) {
                $name = $item->product->name;
                $variant = $item->variant ? $item->variant->label : '';
                $qty = " x" . ($item['quantity'] ?? 1);
                
                return $name . $variant . $qty;
            })->implode(' + '),
            'quantity'      => collect($this->activeOrder->items)->sum('quantity'),
        ];
    }

    public function render()
    {
        $willayas = Willaya::all();
        return view('livewire.v2.order.templates.order-customer-info',['wilayas'=>$willayas]);
    }
}
