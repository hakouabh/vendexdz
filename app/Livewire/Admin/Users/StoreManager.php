<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
class StoreManager extends Component
{
    public $isEditModalOpen = false;
    public $editingStoreId = null;
    
    public $name;
    public $email;
    public $phone;
    public $is_active = false;
    public $role;
    public $search = ''; 

    public function openEditModal($id)
    {
        $store = User::find($id);
        
        $this->editingStoreId = $id;
        $this->name = $store->name;
        $this->email = $store->email;
        $this->phone = $store->phone; 
        $this->is_active = $store->is_active;
        $this->role = $store->roles->first()?->rid;
        $this->isEditModalOpen = true;
        
    }

    public function updateStore()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
            'role' => 'required|exists:roles,rid'
        ]);
        
        $store = User::find($this->editingStoreId);
        
        $store->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ]);
        $store->roles()->sync([$this->role]);
        $this->isEditModalOpen = false;
    
    }

    public function render()
    {
        $stores = User::whereHas('roles', function ($q) {
        $q->where('roles.rid', 5); 
        })
        ->where(function($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->paginate(10);
        $stores->withQueryString();
        return view('livewire.admin.users.store-manager',['stores'=>$stores]);
    }

    public function deleteStore($id)
    {
        $Store = User::find($id);
        $Models = [
            Order::class,
        ];
        $result = canDelete($Models, 'sid', $Store->userStore->store_id);
        if ($result) {
            $this->dispatch('notify', message: __('Cannot delete user because it is associated with existing orders.'), type: 'error');
            return;
        }
        $Models = [
            Store::class,
        ];
        $result = canDelete($Models, 'created_by', $Store->id);
        if ($result) {
            $this->dispatch('notify', message: __('Cannot delete user because it is associated with existing store.'), type: 'error');
            return;
        }
        $Store->delete();
    }
}
