<?php

namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class TerritoriesManager extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedSku = null; // Stores the chosen SKU
    public $selectedProduct = null;

    // Listen for a "back" action from the child component
    protected $listeners = ['resetSelection' => 'clearSelection'];

    public function selectProduct($sku)
    {
        $this->selectedSku = $sku;
        $this->selectedProduct = Product::where('sku', $sku)->first();
    }

    public function clearSelection()
    {
        $this->selectedSku = null;
        $this->selectedProduct = null;
    }
   
    public function render()
    {
        $products = Product::where('store_id', auth()->user()->userStore->id)
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
        return view('livewire.store.territories-manager', [
            'products' => $products
        ]);
    }
}
