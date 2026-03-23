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

    public function setDefault($appId)
    {
        installedApps::where('sid', $this->sid)->update(['is_default' => false]);

        $app = installedApps::find($appId);
        if ($app && $app->sid == $this->sid) {
            $app->is_default = true;
            $app->save();
        }
    }

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
        $is_default = installedApps::where('sid', $this->sid)->where('is_default', true)->exists();

        if (!$exists) {
            installedApps::create([
                'sid' => $this->sid,
                'app_id' => $this->selectedAppId,
                'key' => $this->apiKey,
                'token' => $this->apiToken,
                'is_active' => true,
                'is_default' => !$is_default
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
        $installedApp = installedApps::find($appId);
        if($installedApp->is_default){
            $nextApp = installedApps::where('sid', $this->sid)->where('app_id', '!=', $appId)->first();
            if ($nextApp) {
                $nextApp->is_default = true;
                $nextApp->save();
            }
        }
        $installedApp->delete();
    }

    public function render()
    {
        $this->sid = auth()->user()->userStore->store_id;
        $query = SupportedApps::query();
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $allApps = $query->get();
        $installedApps = installedApps::with('supportedApp')->where('sid', $this->sid)->get();

        $installedIds = installedApps::where('sid', $this->sid)->pluck('app_id')->toArray();
        return view('livewire.store.companies-manager', [
            'installed' => $installedApps,
            'available' => $allApps->whereNotIn('app_id', $installedIds),
        ]);
    }
}
