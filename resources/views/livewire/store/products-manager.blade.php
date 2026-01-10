<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-sans antialiased text-left" x-data="{ open: @entangle('showForm') }">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Inventory Stock</h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">Manage your products, variants, and stock levels across your store.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="relative w-full md:w-80">
                <input type="text" wire:model.live="search" class="block w-full pl-4 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 shadow-sm transition" placeholder="Search products SKU or name...">
            </div>
            <button wire:click="create" class="flex items-center justify-center gap-2 bg-indigo-600 px-6 py-3 rounded-xl text-sm font-bold text-white hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 active:scale-95">
                <i class="ri-add-line text-lg"></i> Register Product
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-xs uppercase tracking-widest border border-emerald-100 flex items-center gap-2">
            <i class="ri-checkbox-circle-fill"></i> {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $p)
            <div class="bg-white rounded-[24px] p-1 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col h-full relative overflow-hidden group">
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 bg-slate-50 text-slate-600 text-[10px] font-black rounded-full border border-slate-100 uppercase tracking-wider">
                        SKU: {{ $p->sku }}
                    </span>
                </div>

                <div class="p-6 flex-1">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                            <i class="ri-archive-line"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg leading-tight">{{ $p->name }}</h3>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">{{ $p->nickname ?? 'No Nickname' }}</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5 text-[10px] font-black uppercase tracking-wide">
                                <span class="text-slate-400">Inventory Level</span>
                                <span class="text-slate-900">{{ $p->variants->sum('quantity') }} Units</span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-indigo-500 transition-all duration-1000" style="width: {{ $p->variants->sum('quantity') > 0 ? min(($p->variants->sum('quantity') / 100) * 100, 100) : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <div class="flex flex-col">
                                <span class="text-xl font-black text-slate-900">{{ number_format($p->price) }} <small class="text-[10px] text-slate-400 uppercase">DA</small></span>
                            </div>
                            <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                <i class="ri-git-branch-line text-indigo-500"></i>
                                <span class="text-xs font-bold text-slate-700">{{ $p->variants->count() }} Variants</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-slate-50/50 mt-auto border-t border-slate-50 flex gap-2">
                    <button wire:click="edit({{ $p->id }})" class="flex-1 py-2.5 bg-white border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 text-slate-600 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                        <i class="ri-edit-box-line text-lg"></i> Edit Product
                    </button>
                    <a href="{{ $p->url }}" target="_blank" class="w-12 h-10 bg-white border border-slate-200 hover:bg-slate-900 hover:text-white text-slate-400 rounded-xl transition-all flex items-center justify-center">
                        <i class="ri-external-link-line"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-10">
        {{ $products->links() }}
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
        <div class="relative w-full max-w-5xl bg-white rounded-[32px] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $isEditMode ? 'Modify Product' : 'Register New Item' }}</h3>
                    <p class="text-slate-500 text-xs font-medium mt-1">Fill in the details to update your inventory database.</p>
                </div>
                <button wire:click="$set('showForm', false)" class="h-10 w-10 rounded-full bg-slate-100 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form wire:submit.prevent="save" class="overflow-y-auto p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Product Name</label>
                            <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Nickname</label>
                            <input type="text" wire:model="nickname" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Parent SKU</label>
                            <input type="text" wire:model="sku" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Base Price (DA)</label>
                            <input type="number" wire:model="price" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Category</label>
                            <select wire:model="cid" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none appearance-none">
                                <option value="">Select Category</option>
                               
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Landing URL</label>
                            <input type="url" wire:model="url" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm font-bold outline-none" placeholder="https://...">
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50/30 rounded-[24px] p-6 border border-indigo-100/50">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                                <i class="ri-stack-line"></i>
                            </div>
                            <h4 class="text-sm font-extrabold text-slate-800 uppercase tracking-tight">Product Variants</h4>
                        </div>
                        <button type="button" wire:click="addVariant" class="px-4 py-2 rounded-lg bg-white border border-indigo-200 text-xs font-bold text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                            + Add Variant
                        </button>
                    </div>

                    <div class="space-y-3">
                        @foreach($variants as $index => $v)
                        <div class="grid grid-cols-2 md:grid-cols-6 gap-3 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative items-end">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase">Color/Var 1</label>
                                <input type="text" wire:model="variants.{{$index}}.var_1" class="w-full rounded-lg border-none bg-slate-50 p-2.5 text-xs font-bold outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase">Size/Var 2</label>
                                <input type="text" wire:model="variants.{{$index}}.var_2" class="w-full rounded-lg border-none bg-slate-50 p-2.5 text-xs font-bold outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase">Other/Var 3</label>
                                <input type="text" wire:model="variants.{{$index}}.var_3" class="w-full rounded-lg border-none bg-slate-50 p-2.5 text-xs font-bold outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-rose-400 uppercase">Discount</label>
                                <input type="number" wire:model="variants.{{$index}}.discount" class="w-full rounded-lg border-none bg-rose-50 p-2.5 text-xs font-black text-rose-600 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-emerald-400 uppercase">Qty</label>
                                <input type="number" wire:model="variants.{{$index}}.quantity" class="w-full rounded-lg border-none bg-emerald-50 p-2.5 text-xs font-black text-emerald-600 outline-none">
                            </div>
                            <div class="flex justify-end">
                                <button type="button" wire:click="removeVariant({{$index}})" class="h-9 w-9 rounded-lg bg-slate-50 text-slate-300 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                    <i class="ri-delete-bin-line text-base"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-slate-900 py-4 rounded-2xl text-white font-bold uppercase tracking-widest text-sm shadow-xl hover:bg-indigo-600 transition-all duration-300">
                        {{ $isEditMode ? 'Update Product Information' : 'Confirm & Save Product' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>