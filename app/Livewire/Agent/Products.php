<?php

namespace App\Livewire\Agent;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Products extends Component
{
    public $search = '';

    public function render()
    {
        $products = Product::query()
        
            ->whereHas('agentAssignments', function($query) {
                $query->where('aid', Auth::id())
                      ->where('is_active', true);
            })
           
            ->with(['variants', 'agentAssignments' => function($query) {
                $query->where('aid', Auth::id());
            }])
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->get();

        return view('livewire.agent.products', [
            'products' => $products
        ]);
    }
    
}
