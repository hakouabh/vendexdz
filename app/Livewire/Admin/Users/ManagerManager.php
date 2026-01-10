<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
class ManagerManager extends Component
{
    public $isEditModalOpen = false;
    public $editingManagerId = null;
    
    public $name;
    public $email;
    public $phone;
    public $is_active = false;
    public $role;

    public function openEditModal($id)
    {
        $Manager = User::find($id);
        
        $this->editingManagerId = $id;
        $this->name = $Manager->name;
        $this->email = $Manager->email;
        $this->phone = $Manager->phone; // أو $Manager->whatsapp حسب التسمية عندك
        $this->is_active = $Manager->is_active;
        $this->role = $Manager->roles->first()?->rid;
        $this->isEditModalOpen = true;
    }

    public function updateManager()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
            'role' => 'required|exists:roles,rid'
        ]);

        $Manager = User::find($this->editingManagerId);
        
        $Manager->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ]);
        $Manager->roles()->sync([$this->role]);
        $this->isEditModalOpen = false;
        
        // رسالة نجاح (اختياري)
        // session()->flash('message', 'Manager updated successfully.');
    }
    public function render()
    {
        $managers = User::whereHas('roles', function ($q) {
        $q->where('roles.rid', 3); 
        })->paginate(10);
        return view('livewire.admin.users.manager-manager',['managers'=>$managers]);
    }
}
