<div class="space-y-6">
    @if(!$selectedSku)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Select Product for Shipping Setup</h3>
                <div class="mt-4 relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" wire:model.live="search" placeholder="Search by product name or SKU..." 
                           class="w-full pl-10 pr-4 py-2 text-sm border-slate-200 rounded-xl focus:ring-indigo-500">
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($products as $product)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition cursor-pointer" 
                         wire:click="selectProduct('{{ $product->sku }}')">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold">
                                {{ substr($product->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700">{{ $product->name }}</p>
                                <p class="text-[10px] text-slate-400 font-black uppercase">SKU: {{ $product->sku }}</p>
                            </div>
                        </div>
                        <i class="ri-arrow-right-s-line text-slate-300 text-xl"></i>
                    </div>
                @endforeach
            </div>
            
            <div class="p-4 bg-slate-50">
                {{ $products->links() }}
            </div>
        </div>
    @else
        <div class="mb-4">
            <button wire:click="clearSelection" class="flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                <i class="ri-arrow-left-line"></i> Back to Product List
            </button>
        </div>
        
        @livewire('store.territories.territories-product', ['product' => $selectedProduct], key($selectedSku))
    @endif
</div>