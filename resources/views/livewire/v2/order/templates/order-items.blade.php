<div class="space-y-4">
    <h5 class="text-[11px] font-bold uppercase text-gray-400">@lang('Order Items')</h5>
    @error('items') <p class=" text-[15px] text-red-600"> {{ $message }}</p> @enderror
    @error('sku') <p class=" text-[15px] text-red-600"> {{ $message }}</p> @enderror
    @foreach($items as $index => $item)
        <div class="group relative p-2.5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-emerald-500 transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex h-8 w-8 shrink-0 rounded-lg bg-slate-50 items-center justify-center text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors">
                    <i class="ri-archive-line text-base"></i>
                </div>
                <div>
                    <div class="flex-1 min-w-[120px]">
                        <select wire:model.live="items.{{ $index }}.product_id"
                            {{ $canUpdate ? '' : 'disabled' }}
                            class="w-full rounded-xl border-none bg-slate-50 p-2 text-[11px] font-bold text-slate-700 focus:ring-1 focus:ring-emerald-500 outline-none transition-all">
                            <option value="">-- @lang('Products') --</option>
                            @foreach($this->availableProducts as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[120px]">
                        <select wire:model.live="items.{{ $index }}.vid"
                            {{ $canUpdate ? '' : 'disabled' }}
                            {{ empty($item['product_id']) ? 'disabled' : '' }}
                            class="w-full rounded-xl border-none bg-slate-50 p-2 text-[11px] font-bold {{ empty($item['product_id']) ? 'text-slate-300' : 'text-slate-700' }} focus:ring-1 focus:ring-emerald-500 outline-none transition-all">
                            <option value="">-- @lang('Variant') --</option>
                            @if(!empty($item['product_id']))
                                @foreach($this->getVariants($item['product_id']) as $v)
                                    <option value="{{ $v->id }}">{{ $v->var_1 }} ({{ $v->var_2 }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div>
                    <div class="w-16 flex items-center bg-slate-900 rounded-lg px-2 py-1 shadow-sm">
                        <span class="text-[9px] font-black text-slate-400 uppercase mr-1">Q:</span>
                        <input type="number" wire:model.live="items.{{ $index }}.quantity" {{ $canUpdate ? '' : 'disabled' }}
                            class="w-10 border-none bg-transparent text-center text-[11px] font-black text-white focus:ring-0 p-0">
                    </div>
                    @if($canUpdate)
                        <button wire:click="deleteItem({{ $item['id'] }}, {{ $index }})"
                            class="h-8 w-8 shrink-0 flex items-center justify-center rounded-lg text-slate-300 hover:bg-rose-50 hover:text-rose-500 transition-all">
                            <i class="ri-delete-bin-line text-base"></i>
                        </button>
                    @endif
                </div>
            </div>
            @if(!empty($item['sku']))
                <div class="mt-1.5 flex items-center gap-2 px-1 animate-in fade-in">
                    <span
                        class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded uppercase tracking-tighter">
                        {{ $item['product_name'] }}
                    </span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase italic truncate">
                        {{ $item['variant_info'] }}
                    </span>
                </div>
            @endif
        </div>
    @endforeach
    @if($canUpdate)
        <button wire:click="addItem"
            class="group/add flex w-full items-center justify-center gap-3 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-3 transition-all duration-300 hover:border-blue-500 hover:bg-blue-50/50">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-gray-200 transition-all group-hover/add:bg-blue-500 group-hover/add:ring-blue-500">
                <i class="ri-add-line text-lg text-gray-500 transition-colors group-hover/add:text-white"></i>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 transition-colors group-hover/add:text-blue-600">
                @lang('Add New Item')
            </span>
        </button>
    @endif
</div>