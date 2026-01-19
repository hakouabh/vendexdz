<?php

namespace App\Livewire\V2\Order\Templates;

use Livewire\Component;
use App\Models\Order;
use App\Models\Willaya;
use App\Models\Client;
use App\Models\Fees;
use App\Livewire\V2\Order\Traits\OrderTrait;
use App\Services\TerritoryServices\ZRTerritoryService;
use App\Services\TerritoryServices\AndersonTerritoryService;

class OrderCustomerInfo extends Component
{
    use OrderTrait;
    public Client $client;
    public $wilaya;
    public $city;
    public $communes = [];

    public function mount(Order $activeOrder){
        $this->client = $activeOrder->client;
        $this->client_name = $this->client->full_name ?? '';
        $this->phone1 = $this->client->phone_number_1 ?? '';
        $this->phone2 = $this->client->phone_number_2 ?? '';
        $this->address = $this->client->address ?? '';
        $this->wilaya = $this->client->wilaya ?? '';
        $this->updatedWilaya($this->wilaya);
        $this->city = $this->client->town ?? '';  
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

    public function render()
    {
        $willayas = Willaya::all();
        return view('livewire.v2.order.templates.order-customer-info',['wilayas'=>$willayas]);
    }
}
