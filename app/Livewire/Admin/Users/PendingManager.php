<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
class PendingManager extends Component
{
    public $isEditModalOpen = false;
    public $editingPendingId = null;
    
    public $name;
    public $email;
    public $phone;
    public $is_active = false;
    public $role;
    
    public function openEditModal($id)
    {
        $Pending = User::find($id);
        
        $this->editingPendingId = $id;
        $this->name = $Pending->name;
        $this->email = $Pending->email;
        $this->phone = $Pending->phone; // أو $Pending->whatsapp حسب التسمية عندك
        $this->is_active = $Pending->is_active;
        $this->role = $Pending->roles->first()?->rid;
        $this->isEditModalOpen = true;
    }

    public function updatePending()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
            'role' => 'required|exists:roles,rid'
        ]);
        
        $Pending = User::find($this->editingPendingId);
        
        $Pending->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ]);
        $Pending->roles()->sync([$this->role]);
        $this->isEditModalOpen = false;
        
        // رسالة نجاح (اختياري)
        // session()->flash('message', 'Pending updated successfully.');
    }

    public function render()
    {
        $pendings = User::whereHas('roles', function ($q) {
        $q->where('roles.rid', 1); 
        })->paginate(10);
        return view('livewire.admin.users.pending-manager',['pendings'=>$pendings]);
    }
}
