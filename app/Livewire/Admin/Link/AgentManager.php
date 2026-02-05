<?php

namespace App\Livewire\Admin\Link;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductAgent;
use Livewire\Component;
use Livewire\WithPagination;

class AgentManager extends Component
{

   public $searchAgent = '';
    public $selectedAgent = null;
    public $portions = [];

    public function selectAgent($id)
    {
        $this->selectedAgent = User::find($id);
        $this->portions = ProductAgent::where('aid', $id)
            ->pluck('portion', 'product_id')
            ->toArray();
    }

    public function linkProduct($id)
    {
        if (!$this->selectedAgent) return;

        $amount = $this->portions[$id] ?? 0;

        ProductAgent::updateOrCreate(
            ['aid' => $this->selectedAgent->id, 'product_id' => $id],
            ['portion' => $amount, 'is_active' => true]
        );

        $this->dispatch('notify', type: 'success', message: 'Assignment Updated');
    }

    public function unlinkProduct($id)
    {
        ProductAgent::where('aid', $this->selectedAgent->id)->where('product_id', $id)->delete();
        unset($this->portions[$id]);
        $this->dispatch('notify', type: 'warning', message: 'Product Unlinked');
    }
    public function render()
    {
        $agents =  $stores = User::whereHas('roles', function ($q) {
        $q->where('roles.rid', 4); 
        })->get();

        $products = Product::when($this->selectedAgent, function ($query)  {
            $query->whereIn('store_id', $this->selectedAgent->stores->pluck('id'));
        })->get();
        return view('livewire.admin.link.agent-manager', [
            'agents' => $agents,
            'products' => $products,
            'linkedProducts' => $this->selectedAgent 
                ? ProductAgent::where('aid', $this->selectedAgent->id)->pluck('product_id')->toArray() 
                : []
        ]);
    }
}
