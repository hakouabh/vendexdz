<?php

namespace App\Livewire\Agent;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Products extends Component
{
    public $search = '';
    public $storefilter = null;

    public function storeFilter($id){
        $this->storefilter = $id;
    }

    public function render()
    {
        $user = auth()->user();
        $stores = $user->stores;
        $products = Product::query()
            ->when($this->storefilter, function ($query) {
                $query->where('store_id', $this->storefilter);
            })
            ->when($this->storefilter == null, function ($query) use ($stores)  {
                $query->whereIn('store_id', $stores->pluck('id'));
            })
            ->with('variants')
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->get();

        return view('livewire.agent.products', [
            'products' => $products,
            'stores' => $stores
        ]);
    }
    
}
