<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Livewire\WithPagination;

class ManagerManager extends Component
{
    use WithPagination;
    protected $pageName = 'inPage';
    public $isEditModalOpen = false;
    public $isCreateModalOpen = false;
    public $editingManagerId = null;
    
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $is_active = false;
    public $role = Role::MANAGER;
    public $search = '';
    protected $listeners = ['createManagerClick' => 'openCreateModal'];

    public function openEditModal($id)
    {
        $Manager = User::find($id);
        
        $this->editingManagerId = $id;
        $this->name = $Manager->name;
        $this->email = $Manager->email;
        $this->phone = $Manager->phone;
        $this->is_active = $Manager->is_active;
        $this->isEditModalOpen = true;
    }

    public function openCreateModal()
    {
        $this->reset(['editingManagerId', 'name', 'email', 'phone', 'is_active', 'role']);
        $this->isCreateModalOpen = true;
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
        $q->where('roles.rid', $this->role); 
        })
        ->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->paginate(10);
        $managers->withQueryString();
        $roles = Role::all();
        return view('livewire.admin.users.manager-manager',['managers'=>$managers, 'roles'=>$roles]);
    }

    public function createManager()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|exists:roles,rid'
        ]);

        $Manager = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => bcrypt($this->password),
            'is_active' => 1,
        ]);
        $Manager->roles()->sync([$this->role]);
        $this->isCreateModalOpen = false;
        
        session()->flash('message', 'Manager created successfully.');
    }

    public function deleteManager()
    {
        $Manager = User::find($this->editingManagerId);
        if ($Manager) {
            $Manager->delete();
            $this->isEditModalOpen = false;
            session()->flash('message', 'Manager deleted successfully.');
        }
    }
}
