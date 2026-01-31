<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
class AgentManager extends Component
{
    public $isEditModalOpen = false;
    public $editingAgentId = null;
    
    public $name;
    public $email;
    public $phone;
    public $is_active = false;
    public $role;
    public $search = '';

    public function openEditModal($id)
    {
        $Agent = User::find($id);
        
        $this->editingAgentId = $id;
        $this->name = $Agent->name;
        $this->email = $Agent->email;
        $this->phone = $Agent->phone; // أو $Agent->whatsapp حسب التسمية عندك
        $this->is_active = $Agent->is_active;
        $this->role = $Agent->roles->first()?->rid;
        $this->isEditModalOpen = true;
    }

    public function updateAgent()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
            'role' => 'required|exists:roles,rid'
        ]);

        $Agent = User::find($this->editingAgentId);
        
        $Agent->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ]);
        $Agent->roles()->sync([$this->role]);
        $this->isEditModalOpen = false;
        
    }
    public function render()
    {
        $agents = User::whereHas('roles', function ($q) {
        $q->where('roles.rid', 4); 
        })
        ->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->paginate(10);
        $agents->withQueryString();
        return view('livewire.admin.users.agent-manager',['agents'=>$agents]);
    }
}
