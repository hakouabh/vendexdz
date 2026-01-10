<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pricing;
use App\Models\Category;

class FeesManager extends Component
{
    use WithPagination;

    // Form Properties
    public $itemId;
    public $cid;
    public $qmin;
    public $qmax;
    public $qb = 0;
    public $usell;
    public $csell;
    public $ab_o = false; // Assuming boolean for "Abandoned Option"
    public $msg;

    public $isModalOpen = false;
    public $confirmingDeletion = false;

    public function render()
    {
        return view('livewire.admin.fees-manager', [
            'pricings' => Pricing::with('category')->orderBy('cid')->paginate(10),
            'categories' => Category::orderBy('name')->get()
        ]);
    }
    public function create()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $item = Pricing::findOrFail($id);

        $this->itemId = $id;
        $this->cid = $item->cid;
        $this->qmin = $item->qmin;
        $this->qmax = $item->qmax;
        $this->qb = $item->qb;
        $this->usell = $item->usell;
        $this->csell = $item->csell;
        $this->ab_o = $item->ab_o;
        $this->msg = $item->msg;

        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'cid' => 'required',
            'qmin' => 'required|integer',
            'qmax' => 'required|integer|gte:qmin', // Max must be >= Min
            'usell' => 'required|numeric',
            'csell' => 'required|numeric',
            'qb' => 'nullable|numeric',
            'msg' => 'nullable|numeric',
        ]);

        Pricing::updateOrCreate(
            ['id' => $this->itemId],
            [
                'cid' => $this->cid,
                'qmin' => $this->qmin,
                'qmax' => $this->qmax,
                'qb' => $this->qb,
                'usell' => $this->usell,
                'csell' => $this->csell,
                'ab_o' => $this->ab_o ? 1 : 0,
                'msg' => $this->msg,
            ]
        );

        session()->flash('message', 'Pricing rule saved successfully.');
        $this->closeModal();
    }

    public function deleteId($id)
    {
        $this->itemId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        Pricing::find($this->itemId)->delete();
        $this->confirmingDeletion = false;
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->itemId = null;
        $this->cid = '';
        $this->qmin = '';
        $this->qmax = '';
        $this->qb = 0;
        $this->usell = '';
        $this->csell = '';
        $this->ab_o = false;
        $this->msg = '';
    }
}
