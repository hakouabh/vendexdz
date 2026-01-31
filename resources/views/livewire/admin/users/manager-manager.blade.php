<div>
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-900">@lang('Managers')</h2>
                <p class="text-sm text-slate-500 mt-1">@lang('Manage your Managers.')</p>
            </div>
            <div class="flex gap-3">
                <div class="relative w-96 group hidden md:block">
                    <i
                        class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500"></i>
                    <input type="text" placeholder="@lang('Search Manager...')" wire:model="search"
                        class="w-full pl-10 pr-12 py-2 bg-gray-50 border border-transparent focus:bg-white focus:border-indigo-200 focus:ring-2 focus:ring-indigo-50 rounded-lg outline-none text-sm transition text-slate-700 placeholder-gray-400">
                    <div
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 border border-gray-200 px-1.5 py-0.5 rounded bg-white">
                        <i class="ri-search-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 text-slate-500 text-xs uppercase font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-lg">
                            <input type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-4">@lang('Manager')</th>
                        <th class="px-6 py-4">@lang('Whatsapp')</th>
                        <th class="px-6 py-4">@lang('Create_Date')</th>
                        <th class="px-6 py-4">@lang('Update_Date')</th>

                        <th class="px-6 py-4">@lang('Status')</th>
                        <th class="px-6 py-4 text-right">@lang('Actions')</th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($managers as $manager)
                    <tr class="hover:bg-gray-50/80 transition duration-150 group">
                        <td class="px-6 py-4">
                            <input type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                        {{ $manager->short_name}}
                                </div>
                                <div>
                                    <div class="font-medium text-slate-900">{{$manager->name}}</div>
                                    <div class="text-xs text-slate-400">{{$manager->email}}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{$manager->phone}}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $manager->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $manager->updated_at->format('d M Y') }}</td>

                        <td class="px-6 py-4">
                            @if($manager->is_active)
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> @lang('Active')
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> @lang('Inactive')
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="openEditModal({{ $manager->id }})" wire:loading.attr="disabled"
                                class="text-slate-400 hover:text-indigo-600 transition p-1">
                                <i class="ri-edit-line text-lg"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $managers->links() }}
        </div>
    </div>
    <x-dialog-modal wire:model="isEditModalOpen" maxWidth="lg">
        <x-slot name="title">
            <div class="px-2 pt-2 flex items-start justify-between">
                <div class="flex gap-4">
                    <div
                        class="h-12 w-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                        <i class="ri-user-settings-line text-2xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ __('Update Manager') }}</h2>
                        <p class="text-sm text-slate-500 mt-1">Manage Manager details, contact info, and visibility.</p>
                    </div>
                </div>
                <button wire:click="$set('isEditModalOpen', false)"
                    class="text-slate-400 hover:text-slate-600 transition">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6 px-2">
                <div>
                    <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Manager
                        Information</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i
                                class="ri-user-settings-line text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input type="text" wire:model="name"
                            class="pl-10 w-full rounded-lg border-slate-200 bg-slate-50/50 text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition-all duration-200 placeholder-slate-400"
                            placeholder="e.g. Vendex Mega Manager">
                    </div>
                    <x-input-error for="name" class="mt-1" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">
                            Assign Role
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i
                                    class="ri-shield-keyhole-line text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            </div>
                            <select wire:model="role"
                                class="pl-10 w-full rounded-lg border-slate-200 bg-slate-50/50 text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition-all duration-200 appearance-none cursor-pointer">
                                <option value="" disabled>Select Role</option>
                                <option value="1">pending</option>
                                <option value="3">Manager</option>
                                <option value="4">Agent</option>
                                <option value="5">Store</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <i class="ri-arrow-down-s-line text-lg"></i>
                            </div>
                        </div>
                        <x-input-error for="role" class="mt-1" />
                    </div>
                    <div>
                        <label
                            class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Contact
                            Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i
                                    class="ri-mail-send-line text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            </div>
                            <input type="email" wire:model="email"
                                class="pl-10 w-full rounded-lg border-slate-200 bg-slate-50/50 text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition-all duration-200"
                                placeholder="manager@Manager.com">
                        </div>
                        <x-input-error for="email" class="mt-1" />
                    </div>

                    <div>
                        <label
                            class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Whatsapp</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i
                                    class="ri-whatsapp-line text-slate-400 group-focus-within:text-green-500 transition-colors"></i>
                            </div>
                            <input type="text" wire:model="phone"
                                class="pl-10 w-full rounded-lg border-slate-200 bg-slate-50/50 text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition-all duration-200"
                                placeholder="+213 555...">
                        </div>
                        <x-input-error for="phone" class="mt-1" />
                    </div>
                </div>

                <div>
                    <label class="text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1.5 block">Manager
                        Status</label>

                    <label
                        class="relative flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group
                    {{ $is_active ? 'border-indigo-600 bg-indigo-50/30' : 'border-slate-200 bg-white hover:border-slate-300' }}">

                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-bold {{ $is_active ? 'text-indigo-700' : 'text-slate-700' }}">
                                    {{ $is_active ? 'Active Manager' : 'Inactive Manager' }}
                                </span>
                                @if($is_active)
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wide">Live</span>
                                @else
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wide">Hidden</span>
                                @endif
                            </div>
                            <p class="text-xs {{ $is_active ? 'text-indigo-600/80' : 'text-slate-500' }}">
                                {{ $is_active ? 'This Manager is currently visible to all users and accepting orders.' : 'This Manager is hidden from the public and cannot accept orders.' }}
                            </p>
                        </div>

                        <div class="relative ml-4">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div
                                class="w-12 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner">
                            </div>
                        </div>
                    </label>
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="border-t border-slate-100 pt-4 w-full flex justify-between items-center">

                <button type="button"
                    class="text-xs font-medium text-red-500 hover:text-red-700 hover:underline transition">
                    
                </button>

                <div class="flex items-center gap-3">
                    <x-secondary-button wire:click="$set('isEditModalOpen', false)" wire:loading.attr="disabled"
                        class="!rounded-lg border-slate-200">
                        Cancel
                    </x-secondary-button>

                    <x-button wire:click="updateManager" wire:loading.attr="disabled"
                        class="bg-slate-900 hover:bg-slate-800 text-white !rounded-lg px-6 shadow-lg shadow-slate-200">
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Saving...</span>
                    </x-button>
                </div>
            </div>
        </x-slot>

    </x-dialog-modal>
</div>