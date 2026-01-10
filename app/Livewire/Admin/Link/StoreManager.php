<?php

namespace App\Livewire\Admin\Link;

use Livewire\Component;
use App\Models\User;

class StoreManager extends Component
{  
    //varibles
    public $store_id;
    
    //functions
    public function SelectStore($id){
     $this->store_id = $id;
    }
    public function render()
    {
        
        $stores = User::whereHas('roles', function ($q) {
        $q->where('roles.rid', 5); 
        })->paginate(10);

        $target_id = $this->store_id ?? $stores->first()?->id;
        if ($target_id) {
            $selectedStore = User::find($target_id);

            if ($selectedStore) {
                $managers = $selectedStore->managers()->get(); 
                $agents = $selectedStore->Agents()->get(); 
            }
        }
   

        return view('livewire.admin.link.store-manager',['stores'=>$stores,'managers'=>$managers ,'agents'=>$agents] );
    }
}
