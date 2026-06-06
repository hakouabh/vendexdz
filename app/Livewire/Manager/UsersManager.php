<?php

namespace App\Livewire\Manager;

use Livewire\Component;
use Livewire\Attributes\Url; 

class UsersManager extends Component
{
    #[Url(keep: true)] 
    public $currentTab = 'stores';

    public function setTab($tab)
    {
        $this->currentTab = $tab;
    }

    public function render()
    {
        return view('livewire.manager.users-manager');
    }
}