<?php

namespace App\Livewire\Admin\Link;

use Livewire\Component;
use App\Models\Store;

class StoreManager extends Component
{  
    //varibles
    public Store $store;
    
    
    public function SelectStore($id){
        $this->store = Store::find($id);
    }

    public function render()
    {
        $stores = Store::whereHas('shops')
        ->latest()
        ->paginate(10);
        $stores->withQueryString();
        return view('livewire.admin.link.store-manager',['stores'=>$stores] );
    }
}
