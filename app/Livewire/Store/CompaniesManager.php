<?php

namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\SupportedApps;
use App\Models\installedApps;
use Illuminate\Support\Facades\Auth;

class CompaniesManager extends Component
{

    public $search = '';
    public $sid ; // Replace with your dynamic Store ID logic

    // Modal State
    public $confirmingInstallation = false;
    public $selectedAppId = null;
    public $selectedAppName = '';
    
    // Form Inputs
    public $apiKey = '';
    public $apiToken = '';

    protected $rules = [
        'apiKey' => 'required|string',
        'apiToken' => 'required|string',
    ];

    // Step 1: Open the Modal
    public function openInstallModal($appId, $appName)
    {
        $this->selectedAppId = $appId;
        $this->selectedAppName = $appName;
        $this->apiKey = '';   // Reset input
        $this->apiToken = ''; // Reset input
        $this->confirmingInstallation = true;
    }

    // Step 2: Save to Database
    public function saveInstallation()
    {
        $this->validate();

        // Check duplicate
        $exists = installedApps::where('sid', $this->sid)
                               ->where('app_id', $this->selectedAppId)
                               ->exists();

        if (!$exists) {
            installedApps::create([
                'sid' => $this->sid,
                'app_id' => $this->selectedAppId,
                'key' => $this->apiKey,
                'token' => $this->apiToken,
                'is_active' => true,
            ]);

            // Close Modal & Reset
            $this->confirmingInstallation = false;
            $this->selectedAppId = null;
            
            // Dispatch success message (Toast)
            $this->dispatch('saved'); 
        }
    }

    public function uninstall($appId)
    {
        installedApps::where('sid', $this->sid)
                     ->where('app_id', $appId)
                     ->delete();
    }

    public function render()
    {
        $this->sid = Auth::user()->id;
        $query = SupportedApps::query();
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $allApps = $query->get();

        $installedIds = installedApps::where('sid', $this->sid)->pluck('app_id')->toArray();
        return view('livewire.store.companies-manager', [
            'installed' => $allApps->whereIn('app_id', $installedIds),
            'available' => $allApps->whereNotIn('app_id', $installedIds),
        ]);
    }
}
