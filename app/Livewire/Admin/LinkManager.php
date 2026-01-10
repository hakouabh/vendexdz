<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Url; 

class LinkManager extends Component
{
    #[Url(keep: true)] 
    public $currentTab = 'stores';

    public function setTab($tab)
    {
        $this->currentTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.link-manager');
    }
}
