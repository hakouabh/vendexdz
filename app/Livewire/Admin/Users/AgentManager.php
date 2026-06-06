<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Livewire\WithPagination;

class AgentManager extends Component
{
    use WithPagination;
    protected $pageName = 'inPage';
    public $isEditModalOpen = false;
    public $isCreateModalOpen = false;
    public $editingAgentId = null;
    
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $is_active = false;
    public $role = Role::AGENT;
    public $search = '';
    protected $listeners = ['createAgentClick' => 'openCreateModal'];

    public function openEditModal($id)
    {
        $Agent = User::find($id);
        
        $this->editingAgentId = $id;
        $this->name = $Agent->name;
        $this->email = $Agent->email;
        $this->phone = $Agent->phone; // أو $Agent->whatsapp حسب التسمية عندك
        $this->is_active = $Agent->is_active;
        $this->isEditModalOpen = true;
    }

    public function openCreateModal()
    {
        $this->reset(['editingAgentId', 'name', 'email', 'phone', 'is_active', 'role']);
        $this->isCreateModalOpen = true;
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
        $q->where('roles.rid', $this->role); 
        })
        ->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->paginate(10);
        $agents->withQueryString();
        $roles = Role::all();
        return view('livewire.admin.users.agent-manager',['agents'=>$agents, 'roles'=>$roles]);
    }
    public function createAgent()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|exists:roles,rid'
        ]);

        $Agent = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => bcrypt($this->password),
            'is_active' => 1,
        ]);
        $Agent->roles()->sync([$this->role]);
        $this->isCreateModalOpen = false;
        
        session()->flash('message', 'Agent created successfully.');
    }

    public function deleteAgent()
    {
        $Agent = User::find($this->editingAgentId);
        if ($Agent) {
            $Agent->delete();
            $this->isEditModalOpen = false;
            session()->flash('message', 'Agent deleted successfully.');
        }
    }
}
