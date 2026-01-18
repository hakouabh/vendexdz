<?php

namespace App\Livewire\V2;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\OrderItems;

class Products extends Component
{
    use WithPagination;

    // UI state
    public $isEditMode = false;
    public $showForm = false;

    // Product fields
    public $productId;
    public $name;
    public $sku;
    public $price;
    public $url;
    public $category_id;

    // Variants
    public $variants = [];

    public function mount()
    {
        
    }

    protected function rules()
    {
        return Product::$rules;
        
    }

    public function create()
    {
        $this->resetFields();
        $this->isEditMode = false;
        $this->addVariant();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $product = app(ProductRepository::class)->findWithVariants($id);

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->price = $product->price;
        $this->url = $product->url;
        $this->category_id = $product->category_id;

        $this->variants = $product->variants->map(fn($v) => [
            'id' => $v->id,
            'product_id' => $product->id,
            'var_1' => $v->var_1,
            'var_2' => $v->var_2,
            'var_3' => $v->var_3,
            'quantity' => $v->quantity,
            'discount' => $v->discount,
        ])->toArray();

        $this->isEditMode = true;
        $this->showForm = true;
    }

    public function addVariant()
    {
        $this->variants[] = [
            'var_1' => null, 'var_2' => null, 'var_3' => null,
            'product_id' => $this->productId ?? null,
            'discount' => 0, 'quantity' => 0
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function destroyVariant(ProductVariant $variant, $index)
    {
        $Models = [
            OrderItems::class
        ];
        $result = canDelete($Models, 'vid', $variant->id);
        if ($result) {
            $this->dispatch('notify', message: __('Cannot delete variant because it is associated with existing orders.'), type: 'error');
            return;
        }
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
        $variant->delete();
        $this->dispatch('notify', message: __('Variant deleted successfully') , type: 'success');
    }

    public function save()
    {
        $validated = $this->validate();
        $productRepository = app(ProductRepository::class);
        $user = Auth::user();

        $data = [
            'created_by' => $user->id,
            'store_id' => $user->store_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'url' => $this->url,
            'category_id' => $this->category_id,
        ];

        if ($this->isEditMode) {
            $product = $productRepository->findWithVariants($this->productId);
            $data['variants'] = $this->variants;
            $productRepository->update($data, $product);
        } else {
            $productRepository->store($data, $this->variants);
        }

        $this->resetFields();
        session()->flash('message', 'Product saved successfully');
    }

    public function resetFields()
    {
        $this->reset([
            'productId', 'name', 'sku', 'price', 'url', 'category_id', 'variants', 'isEditMode', 'showForm'
        ]);
    }

    public function render()
    {
        return view('livewire.v2.products', [
            'products' => app(ProductRepository::class)->paginateByStore(Auth::user()->store_id, 10),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function delete(Product $product)
    {
        $Models = [
            OrderItems::class
        ];
        $result = canDelete($Models, 'product_id', $product->id);
        if ($result) {
            $this->dispatch('notify', message:  __('Cannot delete product because it is associated with existing orders.'), type: 'error');
            return;
        }
        $product->delete();
        $this->dispatch('notify', message: __('Product deleted successfully') , type: 'success');
    }
}
