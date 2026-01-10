<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

// Import Models
use App\Models\AcceptStepStatu;
use App\Models\firstStepStatu;
use App\Models\SecondStepStatu;
use App\Models\ThirdStepStatu;

class StatuManager extends Component
{
    use WithPagination;

    // --- Properties ---
    public $itemId; // Holds the OLD code during edit (e.g., "STEP-01")
    
    // This input field holds the NEW code value
    public $asid; 
    
    public $name;
    public $icon;
    public $color = '#000000';

    public $isModalOpen = false;
    public $confirmingDeletion = false;

    public $activeTab = 'accept_step';

    // --- Configuration ---
    protected $tabs = [
        'accept_step' => [
            'model' => AcceptStepStatu::class, 
            'label' => 'Accept Step Statuses',
            'code_column' => 'asid'
        ],
        'first_step' => [
            'model' => firstStepStatu::class,  
            'label' => 'First Step Statuses',
            'code_column' => 'fsid'
        ],
        'second_step' => [
            'model' => SecondStepStatu::class, 
            'label' => 'Second Step Statuses',
            'code_column' => 'ssid'
        ],
        'third_step' => [
            'model' => ThirdStepStatu::class,  
            'label' => 'Third Step Statuses',
            'code_column' => 'tsid'
        ],
    ];

    // --- Render ---
    public function render()
    {
        $tabConfig = $this->tabs[$this->activeTab];
        $modelClass = $tabConfig['model'];
        $codeColumn = $tabConfig['code_column'];

        // Order by the custom string code
        $items = $modelClass::orderBy($codeColumn, 'asc')->paginate(20);

        return view('livewire.admin.statu-manager', [
            'items' => $items,
            'tabLabel' => $tabConfig['label'],
            'availableTabs' => $this->tabs,
            'currentCodeColumn' => $codeColumn
        ]);
    }

    // --- Switch Tab ---
    public function setTab($tab)
    {
        if (array_key_exists($tab, $this->tabs)) {
            $this->activeTab = $tab;
            $this->resetInput();
            $this->resetPage();
        }
    }

    // --- Create ---
    public function create()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    // --- Edit ---
    public function edit($codeValue)
    {
        $tabConfig = $this->tabs[$this->activeTab];
        $modelClass = $tabConfig['model'];
        $codeColumn = $tabConfig['code_column'];

        // Find the record where custom_column = 'value'
        $item = $modelClass::where($codeColumn, $codeValue)->firstOrFail();

        // Store the ORIGINAL code in $itemId to identify the record later
        $this->itemId = $item->{$codeColumn};
        
        // Fill the form
        $this->asid = $item->{$codeColumn};
        $this->name = $item->name;
        $this->icon = $item->icon;
        $this->color = $item->color ?? '#000000';

        $this->isModalOpen = true;
    }

    // --- Store (Create or Update) ---
    public function store()
    {
        $tabConfig = $this->tabs[$this->activeTab];
        $modelClass = $tabConfig['model'];
        $codeColumn = $tabConfig['code_column'];
        $tableName = (new $modelClass)->getTable();

        // Dynamic Validation
        $this->validate([
            'asid' => [
                'required', 'string', 'max:255',
                // Unique check: ignore current record if editing
                Rule::unique($tableName, $codeColumn)->ignore($this->itemId, $codeColumn)
            ],
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:50',
        ]);

        if ($this->itemId) {
            // EDIT MODE: Find by the OLD key stored in $itemId
            $record = $modelClass::where($codeColumn, $this->itemId)->first();
            
            if ($record) {
                $record->update([
                    $codeColumn => $this->asid, // Update the primary key if user changed it
                    'name' => $this->name,
                    'icon' => $this->icon,
                    'color' => $this->color,
                ]);
            }
        } else {
            // CREATE MODE
            $modelClass::create([
                $codeColumn => $this->asid,
                'name' => $this->name,
                'icon' => $this->icon,
                'color' => $this->color,
            ]);
        }

        session()->flash('message', 'Item saved successfully.');
        $this->closeModal();
    }

    // --- Prepare Delete ---
    public function deleteId($codeValue)
    {
        $this->itemId = $codeValue;
        $this->confirmingDeletion = true;
    }

    // --- Execute Delete ---
    public function delete()
    {
        $tabConfig = $this->tabs[$this->activeTab];
        $modelClass = $tabConfig['model'];
        $codeColumn = $tabConfig['code_column'];

        // Delete using the custom key
        $modelClass::where($codeColumn, $this->itemId)->delete();
        
        $this->confirmingDeletion = false;
        $this->resetInput();
        session()->flash('message', 'Item deleted successfully.');
    }

    // --- Helpers ---
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->itemId = null;
        $this->asid = '';
        $this->name = '';
        $this->icon = '';
        $this->color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}