<div>
  <div class="min-h-screen bg-slate-50 pb-12">
    <div class="bg-white border-b sticky top-0 z-10 shadow-sm">
        <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button type="button"
                        class="inline-flex w-48  items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        {{ __('Filter Store') }}
                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="block px-4 py-2 text-xs text-gray-400 uppercase tracking-widest">
                        {{ __('Stores') }}
                    </div>
                    <x-dropdown-link wire:click="" class="text-[]">
                        <i class=""></i> All
                    </x-dropdown-link>
                  
                </x-slot>
            </x-dropdown>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Product Inventory</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage and market your assigned products</p>
                </div>

                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input wire:model.live="search" type="text" 
                        class="block w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-2xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm outline-none" 
                        placeholder="Search by name or SKU...">
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        @if($products->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="bg-slate-50 p-6 rounded-full mb-4">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">No products found</h3>
                <p class="text-slate-400 mt-2">Try adjusting your search or contact support for assignments.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($products as $product)
                    @php $assignment = $product->agentAssignments->first(); @endphp
                    
                    <div class="group bg-white rounded-[2rem] shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 border border-slate-100 flex flex-col overflow-hidden">
                        
                        <div class="p-6 pb-0 flex justify-between items-start">
                            <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                                {{ $product->sku }}
                            </span>
                            @if($assignment)
                                <div class="text-right">
                                    <p class="text-[9px] text-slate-400 uppercase font-black tracking-tighter">Comm.</p>
                                    <p class="text-green-500 font-bold text-sm leading-none">{{ $assignment->portion }}%</p>
                                </div>
                            @endif
                        </div>

                        <div class="p-6 flex-1">
                            <h3 class="text-3xl font-bold text-slate-800 line-clamp-2 group-hover:text-blue-600 transition-colors leading-tight min-h-[3rem]">
                                {{ $product->name }}
                            </h3>
                            
                            <div class="mt-2 flex items-baseline gap-1">
                                <span class="text-3xl font-black text-slate-900">{{ number_format($product->price, 0) }}</span>
                                <span class="text-xs font-bold text-slate-400 uppercase">DA</span>
                            </div>

                            <div class="mt-6">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Stock & Variants</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($product->variants as $variant)
                                        <div class="flex items-center bg-slate-50 border border-slate-100 rounded-xl px-2.5 py-1.5 shadow-inner">
                                            <span class="text-[10px] text-slate-700 font-bold">{{ $variant->var_1 }}</span>
                                            <span class="mx-2 w-1 h-1 bg-slate-300 rounded-full"></span>
                                            <span class="text-[10px] font-black {{ $variant->quantity > 0 ? 'text-blue-600' : 'text-red-400' }}">
                                                {{ $variant->quantity }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0 mt-auto">
                            <div class="flex gap-2 border-t border-slate-50 pt-4">
                                <a href="{{ $product->url }}" target="_blank" class="flex-1 bg-slate-900 text-white text-center text-xs font-bold py-3 rounded-2xl hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                                    View Details
                                </a>
                                <button onclick="copyLink('{{ $product->url }}')" class="p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-all" title="Copy Marketing Link">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    function copyLink(url) {
        navigator.clipboard.writeText(url);
        alert('Marketing link copied to clipboard!');
    }
</script>
</div>
