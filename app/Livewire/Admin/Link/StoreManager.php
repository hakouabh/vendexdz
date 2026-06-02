<?php

namespace App\Livewire\Admin\Link;

use Livewire\Component;
use App\Models\Store;
use App\Models\User;
use App\Models\UserStore;
use Livewire\WithPagination;

class StoreManager extends Component
{  
    use WithPagination;
    protected $pageName = 'inPage';
    public $store_id = null;
    public $selectedAgentId = '';
    public $selectedManagerId = '';
    public Store $selectedStore;

    public function SelectStore($id){
        $this->selectedStore = Store::find($id);
        $this->store_id = $id;
    }

    public function addAgent(){
        if(!$this->selectedAgentId){
            return;
        }
        UserStore::updateOrCreate([
            'user_id' => $this->selectedAgentId,
            'store_id' => $this->store_id,
        ]);
        $this->reset('selectedAgentId');
    }

    public function removeLink($user_id){
        UserStore::where('store_id', $this->store_id)
        ->where('user_id', $user_id)
        ->delete();
    }

    public function setUserStoreStatus($userStoreId){
        $userStore = UserStore::find($userStoreId);
        if($userStore){
            $userStore->is_active = !$userStore->is_active;
            $userStore->save();
        }
    }
    public function addManager(){
        if(!$this->selectedManagerId){
            return;
        }
        UserStore::updateOrCreate([
            'user_id' => $this->selectedManagerId,
            'store_id' => $this->store_id,
        ]);
        $this->reset('selectedManagerId');
    }

    public function render()
    {
        $availableManagers = User::whereHas('roles', function ($q) {
        $q->where('roles.rid', 3); 
        })
        ->when($this->store_id, function ($query) {
            $query->whereDoesntHave('stores', function ($q) {
                $q->where('store_id', $this->store_id);
            });
        })->get();
        $availableAgents = User::whereHas('roles', function ($q) {
            $q->where('roles.rid', 4); 
        })
        ->when($this->store_id, function ($query) {
            $query->whereDoesntHave('stores', function ($q) {
                $q->where('store_id', $this->store_id);
            });
        })->get();
        $stores = Store::whereHas('shops')
        ->latest()
        ->paginate(10);
        $stores->withQueryString();
        return view('livewire.admin.link.store-manager',['stores'=>$stores, 'availableManagers' => $availableManagers, 'availableAgents' => $availableAgents] );
    }
}
