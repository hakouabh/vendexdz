<?php

namespace App\Livewire\Store\Territories;

use Livewire\Component;
use App\Models\Product;
use App\Models\willaya;
use App\Models\fees;
use App\Models\installedApps;

class TerritoriesProduct extends Component
{
     public $product; // Holds the Product model
    public $product_id;
    public $feesData = [];
    public $installedApps = [];

    /**
     * Mount the component using the Product model
     */
    public function mount(Product $product)
    {
        $this->product = $product;
        $this->product_id = $product->id;
        $sid = auth()->user()->userStore->store_id;

        // Get shipping providers installed by this store
        $this->installedApps = installedApps::where('sid', $sid)
            ->where('is_active', 1)
            ->get();

        $willayas = willaya::all();
        
        // Get existing fees for this Store + Product SKU
        $existingFees = fees::where('sid', $sid)
            ->where('product_id', $this->product_id)
            ->get()
            ->keyBy('wid');

        foreach ($willayas as $w) {
            $fee = $existingFees->get($w->wid);
            $this->feesData[$w->wid] = [
                'name'   => $w->name,
                'app_id' => $fee->app_id ?? '',
                'o_s_p'  => $fee->o_s_p ?? 0,
                'o_d_p'  => $fee->o_d_p ?? 0,
                'c_s_p'  => $fee->c_s_p ?? ($fee->o_s_p ?? 0),
                'c_d_p'  => $fee->c_d_p ?? ($fee->o_d_p ?? 0),
            ];
        }
    }

    public function syncAll()
    {
        $first = reset($this->feesData);
        foreach ($this->feesData as $wid => $data) {
            $this->feesData[$wid]['app_id'] = $first['app_id'];
            $this->feesData[$wid]['c_s_p']  = $first['c_s_p'];
            $this->feesData[$wid]['c_d_p']  = $first['c_d_p'];
        }
    }

    public function save()
    {
        $sid = auth()->user()->userStore->store_id;

        foreach ($this->feesData as $wid => $data) {
            if(!empty($data['app_id']))
            fees::updateOrCreate(
                ['sid' => $sid, 'wid' => $wid, 'product_id' => $this->product_id],
                [
                    'app_id' => $data['app_id'],
                    'o_s_p'  => $data['o_s_p'],
                    'o_d_p'  => $data['o_d_p'],
                    'c_s_p'  => $data['c_s_p'],
                    'c_d_p'  => $data['c_d_p'],
                ]
            );
        }

        session()->flash('success', "Shipping rates for {$this->product_id} saved.");
    }
    public function render()
    {
        return view('livewire.store.territories.territories-product');
    }
}
