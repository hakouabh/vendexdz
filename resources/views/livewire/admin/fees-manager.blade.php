<div>
    <div class="p-6">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Pricing Strategy</h2>
                <p class="text-sm text-gray-500">Manage quantity-based pricing and extra fees.</p>
            </div>
            <x-button wire:click="create" class="bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200">
                <i class="ri-add-line mr-2"></i> Add Pricing Rule
            </x-button>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Qty Range</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bonus (QB)</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User Sell</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cost Sell</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Extra Fees</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pricings as $price)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                            <i class="ri-store-2-fill"></i>
                                        </div>
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $price->category->name ?? 'Unknown' }}
                                            
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $price->qmin }} - {{ $price->qmax }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    {{ number_format($price->qb, 2) }} <span class="text-xs text-gray-400 font-normal">DZD</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                    {{ number_format($price->usell, 2) }} <span class="text-xs text-gray-400 font-normal">DZD</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                    {{ number_format($price->csell, 2) }} <span class="text-xs text-gray-400 font-normal">DZD</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-500">
                                    {{ number_format($price->msg, 2) }} <span class="text-xs text-red-300 font-normal">DZD</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $price->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3 transition">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button wire:click="deleteId({{ $price->id }})" class="text-red-400 hover:text-red-600 transition">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    No pricing rules found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $pricings->links() }}
            </div>
        </div>

        <x-dialog-modal wire:model="isModalOpen" maxWidth="lg">
            <x-slot name="title">
                <div class="px-2 pt-2 flex items-start justify-between">
                    <div class="flex gap-4">
                        <div class="h-12 w-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                            <i class="ri-price-tag-3-line text-2xl text-indigo-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight">
                                {{ $itemId ? 'Update Pricing' : 'New Pricing Rule' }}
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">Set quantity limits and fees.</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
            </x-slot>

            <x-slot name="content">
                <div class="space-y-5 px-2">
                    
                    <div>
                        <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Category</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ri-layout-grid-line text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            </div>
                            <select wire:model="cid" class="pl-10 w-full rounded-lg border-slate-200 bg-slate-50/50 text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition-all appearance-none h-10 cursor-pointer">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->cid }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <i class="ri-arrow-down-s-line"></i>
                            </div>
                        </div>
                        <x-input-error for="cid" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Min Qty</label>
                            <x-input type="number" wire:model="qmin" class="w-full bg-slate-50/50" placeholder="0" />
                            <x-input-error for="qmin" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Max Qty</label>
                            <x-input type="number" wire:model="qmax" class="w-full bg-slate-50/50" placeholder="100" />
                            <x-input-error for="qmax" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">User Sell (Price)</label>
                            <div class="relative">
                                <x-input type="number" step="0.01" wire:model="usell" class="w-full pr-12 bg-slate-50/50" placeholder="0.00" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-400 text-xs font-bold">DZD</span>
                                </div>
                            </div>
                            <x-input-error for="usell" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Cost Sell (Cost)</label>
                            <div class="relative">
                                <x-input type="number" step="0.01" wire:model="csell" class="w-full pr-12 bg-slate-50/50" placeholder="0.00" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-400 text-xs font-bold">DZD</span>
                                </div>
                            </div>
                            <x-input-error for="csell" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Extra Fees (Msg)</label>
                            <div class="relative">
                                <x-input type="number" step="0.01" wire:model="msg" class="w-full pr-12 bg-slate-50/50" placeholder="0.00" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-400 text-xs font-bold">DZD</span>
                                </div>
                            </div>
                            <x-input-error for="msg" class="mt-1" />
                        </div>

                        <div>
                            <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Bonus (QB)</label>
                            <x-input type="number" wire:model="qb" class="w-full bg-slate-50/50" placeholder="0" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center cursor-pointer p-3 border border-slate-100 rounded-lg hover:bg-slate-50 transition">
                            <x-checkbox wire:model="ab_o" class="text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" />
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-slate-700">Abandoned Order Pricing?</span>
                                <span class="block text-xs text-slate-500">Enable this rule specifically for recovered orders.</span>
                            </div>
                        </label>
                    </div>

                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="border-t border-slate-100 pt-4 w-full flex justify-end items-center gap-3">
                    <x-secondary-button wire:click="closeModal" class="!rounded-lg border-slate-200">
                        Cancel
                    </x-secondary-button>
                    <x-button wire:click="store" wire:loading.attr="disabled" class="bg-slate-900 hover:bg-slate-800 text-white !rounded-lg px-6 shadow-lg">
                        {{ $itemId ? 'Update Rule' : 'Save Rule' }}
                    </x-button>
                </div>
            </x-slot>
        </x-dialog-modal>

        <x-confirmation-modal wire:model="confirmingDeletion">
            <x-slot name="title">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                    <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                        <i class="ri-alarm-warning-line text-red-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Delete Rule?</h3>
                    </div>
                </div>
            </x-slot>

            <x-slot name="content">
                <div class="py-2">
                    <p class="text-sm text-slate-500 mb-4">
                        Are you sure you want to delete this pricing rule? This might affect calculations for existing orders.
                    </p>
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="flex items-center gap-3">
                    <x-secondary-button wire:click="$set('confirmingDeletion', false)">Cancel</x-secondary-button>
                    
                    <button wire:click="delete" 
                        class="px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-md shadow-red-200 transition flex items-center gap-2">
                        <span wire:loading.remove wire:target="delete">Yes, Delete it</span>
                        <span wire:loading wire:target="delete">Deleting...</span>
                    </button>
                </div>
            </x-slot>
        </x-confirmation-modal>
    </div>
</div>