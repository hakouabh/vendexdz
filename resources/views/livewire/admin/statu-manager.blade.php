<div class="p-6">
    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Status Management</h2>
            <p class="text-sm text-gray-500">Configure status steps for your workflow.</p>
        </div>
        
        <div class="bg-gray-100 p-1 rounded-lg flex flex-wrap gap-1">
            @foreach($availableTabs as $key => $tab)
                <button wire:click="setTab('{{ $key }}')" 
                    class="px-4 py-2 text-sm font-medium rounded-md transition whitespace-nowrap {{ $activeTab === $key ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="flex justify-between items-center mb-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-700">{{ $tabLabel }} List</h3>
        <x-button wire:click="create" class="bg-indigo-600 hover:bg-indigo-700">
            + Add New
        </x-button>
    </div>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            {{ strtoupper($currentCodeColumn) }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50 transition">
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                {{ $item->{$currentCodeColumn} }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item->name }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($item->icon)
                                    <div class="flex items-center gap-2">
                                        <i class="{{ $item->icon }} text-gray-400"></i>
                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded border border-gray-200">{{ $item->icon }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">No icon</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="h-6 w-6 rounded-full border border-gray-200 shadow-sm" style="background-color: {{ $item->color }}"></span>
                                    <span class="ml-2 text-sm text-gray-500">{{ $item->color }}</span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit('{{ $item->{$currentCodeColumn} }}')" class="text-indigo-600 hover:text-indigo-900 mr-3 transition">Edit</button>
                                <button wire:click="deleteId('{{ $item->{$currentCodeColumn} }}')" class="text-red-600 hover:text-red-900 transition">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p>No records found in {{ $tabLabel }}.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $items->links() }}
        </div>
    </div>

    <x-dialog-modal wire:model="isModalOpen" maxWidth="lg">
        <x-slot name="title">
            <div class="px-2 pt-2 flex items-start justify-between">
                <div class="flex gap-4">
                    <div class="h-12 w-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                        <i class="{{ $itemId ? 'ri-edit-line' : 'ri-add-line' }} text-2xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">
                            {{ $itemId ? 'Update Status' : 'New Status' }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            {{ $itemId ? 'Modify status details and appearance.' : 'Define a new status step for your workflow.' }}
                        </p>
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
                    <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">
                        {{ strtoupper($currentCodeColumn ?? 'CODE') }} (Unique ID)
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ri-qr-code-line text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input type="text" wire:model="asid"
                            class="pl-10 w-full rounded-lg border-slate-200 bg-slate-50/50 text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition-all duration-200 placeholder-slate-400 font-mono"
                            placeholder="e.g. STEP-01">
                    </div>
                    <x-input-error for="asid" class="mt-1" />
                </div>

                <div>
                    <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">
                        Display Name
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ri-text text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input type="text" wire:model="name"
                            class="pl-10 w-full rounded-lg border-slate-200 bg-slate-50/50 text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition-all duration-200 placeholder-slate-400"
                            placeholder="e.g. Pending Approval">
                    </div>
                    <x-input-error for="name" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <div>
                        <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">
                            Icon Class
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ri-star-line text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            </div>
                            <input type="text" wire:model.live="icon"
                                class="pl-10 w-full rounded-lg border-slate-200 bg-slate-50/50 text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition-all duration-200 placeholder-slate-400"
                                placeholder="fa-solid fa-check">
                            
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                @if($icon) <i class="{{ $icon }} text-indigo-500"></i> @endif
                            </div>
                        </div>
                        <x-input-error for="icon" class="mt-1" />
                    </div>

                    <div>
                        <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">
                            Status Color
                        </label>
                        <div class="flex items-center gap-2">
                            <div class="relative h-10 w-12 rounded-lg overflow-hidden shadow-sm ring-1 ring-slate-200 cursor-pointer hover:ring-indigo-500 transition">
                                <input type="color" wire:model.live="color" 
                                    class="absolute -top-2 -left-2 w-16 h-16 cursor-pointer border-none p-0">
                            </div>
                            <input type="text" wire:model.live="color" maxlength="7"
                                class="w-full rounded-lg border-slate-200 bg-slate-50/50 text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition-all duration-200 uppercase font-mono"
                                placeholder="#000000">
                        </div>
                        <x-input-error for="color" class="mt-1" />
                    </div>
                </div>

                @if($name)
                <div class="mt-2">
                    <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">
                        Live Preview
                    </label>
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center justify-center gap-4 border-dashed">
                        <span class="px-2.5 py-1 rounded text-xs font-semibold shadow-sm text-white flex items-center gap-1.5" 
                              style="background-color: {{ $color ?? '#000000' }}">
                            @if($icon) <i class="{{ $icon }}"></i> @endif
                            {{ $name }}
                        </span>
                        
                        <span class="px-4 py-1.5 rounded-full text-sm font-medium shadow-md text-white flex items-center gap-2" 
                              style="background-color: {{ $color ?? '#000000' }}">
                            @if($icon) <i class="{{ $icon }}"></i> @endif
                            {{ $name }}
                        </span>
                    </div>
                </div>
                @endif

            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="border-t border-slate-100 pt-4 w-full flex justify-between items-center">
                
                <div>
                    @if($itemId)
                    <button type="button" wire:click="deleteId('{{ $itemId }}')"
                        class="text-xs font-medium text-red-500 hover:text-red-700 hover:underline transition flex items-center gap-1">
                        <i class="ri-delete-bin-line"></i> Delete Status
                    </button>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <x-secondary-button wire:click="closeModal" class="!rounded-lg border-slate-200 shadow-sm text-slate-600 hover:bg-slate-50">
                        Cancel
                    </x-secondary-button>
                    
                    <x-button wire:click="store" wire:loading.attr="disabled"
                        class="bg-slate-900 hover:bg-slate-800 text-white !rounded-lg px-6 shadow-lg shadow-slate-200 transition-all">
                        <span wire:loading.remove>{{ $itemId ? 'Update Status' : 'Create Status' }}</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </x-button>
                </div>
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
                    <h3 class="text-lg font-bold text-slate-900">Delete Status?</h3>
                </div>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="py-2">
                <p class="text-sm text-slate-500 mb-4">
                    Are you sure you want to permanently delete the status <span class="font-bold text-slate-900">"{{ $item->name ?? 'Unnamed' }}"</span>?
                </p>
                
                <div class="bg-red-50 border border-red-100 rounded-lg p-3 flex gap-3">
                    <i class="ri-information-line text-red-500 mt-0.5"></i>
                    <p class="text-xs text-red-600 font-medium">
                        This action cannot be undone. Any orders currently assigned to this status may need to be reassigned manually.
                    </p>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex items-center gap-3">
                <button wire:click="$set('confirmingDeletion', false)" 
                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    Cancel
                </button>

                <button wire:click="delete" 
                    class="px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-md shadow-red-200 transition flex items-center gap-2">
                    <span wire:loading.remove wire:target="delete">Yes, Delete it</span>
                    <span wire:loading wire:target="delete">Deleting...</span>
                </button>
            </div>
        </x-slot>
    </x-confirmation-modal>
</div>