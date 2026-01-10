<div>
   <div class="p-6 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto grid grid-cols-12 gap-6">
        
        <div class="col-span-12 lg:col-span-4 space-y-4">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-4 italic uppercase tracking-wider">Agents Directory</h3>
                <input wire:model.live="searchAgent" type="text" placeholder="Search agent name..." 
                    class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 mb-4">
                
                <div class="space-y-2 max-h-[600px] overflow-y-auto pr-2">
                    @foreach($agents as $agent)
                        <button wire:click="selectAgent({{ $agent->id }})" 
                            class="w-full flex items-center gap-3 p-4 rounded-2xl transition-all {{ $selectedAgent?->id == $agent->id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white hover:bg-slate-50 text-slate-600' }}">
                            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold">
                                {{ substr($agent->name, 0, 1) }}
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-bold">{{ $agent->name }}</p>
                                <p class="text-[10px] opacity-70 italic">ID: #{{ $agent->id }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-8">
            @if($selectedAgent)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200 animate-in fade-in slide-in-from-right duration-500">
                    <div class="flex items-center justify-between mb-8 border-b pb-6">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Linking Products to <span class="text-indigo-600">{{ $selectedAgent->name }}</span></h2>
                            <p class="text-xs text-slate-400 font-medium">Assign products and set commission portions below.</p>
                        </div>
                        <div class="bg-indigo-50 px-4 py-2 rounded-2xl border border-indigo-100 text-center">
                            <label class="block text-[10px] font-black text-indigo-400 uppercase">Default Portion</label>
                            <input type="number" wire:model="portion" class="w-16 bg-transparent border-none text-center font-black text-indigo-600 p-0 focus:ring-0">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($products as $product)
    @php $isLinked = in_array($product->sku, $linkedProducts); @endphp
    
    <div class="relative group bg-white border {{ $isLinked ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-slate-200' }} rounded-[32px] p-5 transition-all hover:shadow-xl">
        
        <div class="flex items-center gap-4 mb-4">
            <div class="h-16 w-16 rounded-[24px] bg-slate-50 text-slate-300 flex items-center justify-center group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-all duration-500 shadow-inner">
                        <i class="ri-archive-line text-3xl"></i>
            </div>
            <div class="overflow-hidden">
                <h4 class="font-black text-slate-800 truncate">{{ $product->name }}</h4>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $product->sku }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-2xl">
            <div class="flex-1">
                <label class="block text-[9px] font-black text-slate-400 uppercase ml-2">Portion (DZD)</label>
                <input type="number" 
                    wire:model.defer="portions.{{ $product->sku }}" 
                    placeholder="0.00"
                    class="w-full bg-transparent border-none font-black text-indigo-600 focus:ring-0 p-0 ml-2">
            </div>

            @if($isLinked)
                <button wire:click="unlinkProduct('{{ $product->sku }}')" class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all">
                    <i class="ri-delete-bin-line"></i>
                </button>
                <button wire:click="linkProduct('{{ $product->sku }}')" class="w-10 h-10 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-200">
                    <i class="ri-save-line"></i>
                </button>
            @else
                <button wire:click="linkProduct('{{ $product->sku }}')" class="flex-1 py-3 bg-slate-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 transition-all">
                    Link Product
                </button>
            @endif
        </div>

        @if($isLinked)
            <div class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[10px] font-black px-3 py-1 rounded-full shadow-lg">
                ACTIVE
            </div>
        @endif
    </div>
@endforeach
                    </div>
                </div>
            @else
                <div class="h-full flex flex-col items-center justify-center bg-white border-2 border-dashed border-slate-200 rounded-[40px] p-20 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                        <i class="ri-user-follow-line text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-400">Select an agent from the left to start linking products</h3>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
