<?php

namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class ProductsManager extends Component
{

    public $showForm = false;
    public $isEditMode = false;
    public $productId;

    
    public $sid, $name, $nickname, $url, $sku, $cid, $price;
    
   
    public $variants = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'sku' => 'required|string',
        'price' => 'required|numeric|min:0',
        'variants.*.quantity' => 'required|integer|min:0',
        'variants.*.discount' => 'required|integer|min:0',
        'variants.*.sku' => 'nullable|string',
    ];

    public function create()
    {
        $this->resetFields();
        $this->isEditMode = false;
        $this->addVariant(); 
        $this->showForm = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $this->isEditMode = true;
        $product = Product::with('variants')->findOrFail($id);
        
        $this->productId = $id;
        $this->sid = $product->sid;
        $this->name = $product->name;
        $this->nickname = $product->nickname;
        $this->url = $product->url;
        $this->sku = $product->sku;
        $this->cid = $product->cid;
        $this->price = $product->price;

        $this->variants = $product->variants->map(function ($v) {
            return [
                'id' => $v->id,
                'sku' => $v->sku,
                'var_1' => $v->var_1,
                'var_2' => $v->var_2,
                'var_3' => $v->var_3,
                'discount' => $v->discount,
                'quantity' => $v->quantity,
            ];
        })->toArray();

        $this->showForm = true;
    }

    public function addVariant()
    {
        $this->variants[] = [
            'sku' => $this->sku ?? '',
            'var_1' => '', 'var_2' => '', 'var_3' => '',
            'discount' => 0, 'quantity' => 0
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'sid' => $this->sid,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'url' => $this->url,
            'sku' => $this->sku,
            'cid' => $this->cid,
            'price' => $this->price,
        ];

        if ($this->isEditMode) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            
            $product->variants()->delete();
        } else {
            $product = Product::create($data);
        }

        foreach ($this->variants as $variant) {
            $product->variants()->create($variant);
        }

        $this->showForm = false;
        $this->resetFields();
        session()->flash('message', $this->isEditMode ? 'Product Updated!' : 'Product Created!');
    }

    public function resetFields()
    {
        $this->reset(['sid', 'name', 'nickname', 'url', 'sku', 'cid', 'price', 'variants', 'productId', 'isEditMode']);
    }
    public function render()
    {
        $this->sid = Auth::user()->id;
        return view('livewire.store.products-manager', [
            'products' => Product::with('variants')->where('sid',$this->sid)->latest()->paginate(10)
        ]);
    }
}
