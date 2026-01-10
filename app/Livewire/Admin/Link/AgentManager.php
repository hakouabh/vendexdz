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
    public $portions = []; // لتخزين قيم العمولات لكل SKU في الواجهة

    public function selectAgent($id)
    {
        $this->selectedAgent = User::find($id);
        // تحميل العمولات الموجودة مسبقاً في المصفوفة
        $this->portions = ProductAgent::where('aid', $id)
            ->pluck('portion', 'sku')
            ->toArray();
    }

    public function linkProduct($sku)
    {
        if (!$this->selectedAgent) return;

        // الحصول على القيمة المدخلة أو تعيين 0 كافتراضي
        $amount = $this->portions[$sku] ?? 0;

        ProductAgent::updateOrCreate(
            ['aid' => $this->selectedAgent->id, 'sku' => $sku],
            ['portion' => $amount, 'is_active' => true]
        );

        $this->dispatch('notify', type: 'success', message: 'Assignment Updated');
    }

    public function unlinkProduct($sku)
    {
        ProductAgent::where('aid', $this->selectedAgent->id)->where('sku', $sku)->delete();
        unset($this->portions[$sku]);
        $this->dispatch('notify', type: 'warning', message: 'Product Unlinked');
    }
    public function render()
    {
        $agents =  $stores = User::whereHas('roles', function ($q) {
        $q->where('roles.rid', 4); 
        })->get();

        $products = Product::all(); // Or filter by store
        return view('livewire.admin.link.agent-manager', [
            'agents' => $agents,
            'products' => $products,
            'linkedProducts' => $this->selectedAgent 
                ? ProductAgent::where('aid', $this->selectedAgent->id)->pluck('sku')->toArray() 
                : []
        ]);
    }
}
