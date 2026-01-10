<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-sans">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Link Companies</h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">Supercharge your Vendex store with official integrations.</p>
        </div>
        
        <div class="relative w-full md:w-96">
            <input type="text" wire:model.live="search" class="block w-full pl-4 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 shadow-sm transition" placeholder="Search apps...">
        </div>
    </div>

    @if($installed->count() > 0)
        <div class="mb-12">
            <h2 class="text-lg font-bold text-slate-900 mb-5 flex items-center gap-2"><i class="ri-check-double-line text-green-500"></i> Installed & Active</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($installed as $app)
                    <div class="bg-white rounded-[24px] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-md transition">
                        <div class="absolute top-0 right-0 p-4">
                            <span class="flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span></span>
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-800 text-3xl"><img src="{{$app->icon}}" class="w-10" alt=""></div>
                            <div><h3 class="font-bold text-slate-900 text-lg capitalize">{{ $app->name }}</h3></div>
                        </div>
                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-50">
                            <div class="flex items-center gap-1 text-xs font-bold text-green-600">Active</div>
                            <button wire:click="uninstall('{{ $app->app_id }}')" wire:confirm="Uninstall?" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-lg transition">Uninstall</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div>
        <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2"><i class="ri-apps-2-line text-indigo-500"></i> Explore Companies</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($available as $app)
                <div class="bg-white rounded-[24px] p-1 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col h-full">
                    <div class="p-5 flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl"><img src="{{$app->icon}}" class="w-10"alt=""></div>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-1  capitalize">{{ $app->name }}</h3>
                        <p class="text-xs text-slate-500">Connect {{ $app->name }} to sync data.</p>
                    </div>
                    <div class="p-2 mt-auto">
                        <button wire:click="openInstallModal('{{ $app->app_id }}', '{{ $app->name }}')" 
                                class="w-full py-2.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2">
                            <i class="ri-download-cloud-2-line text-lg"></i> Install
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <x-dialog-modal wire:model.live="confirmingInstallation">
        <x-slot name="title">
            Configure {{ $this->selectedAppName }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-4 text-sm text-slate-600">
                Please enter your API credentials to connect <strong>{{ $this->selectedAppName }}</strong> to Vendex.
            </div>

            <div class="mt-4">
                <x-label for="apiKey" value="{{ __('API Key') }}" />
                <x-input id="apiKey" type="text" class="mt-1 block w-full" wire:model="apiKey" placeholder="Enter API Key" />
                <x-input-error for="apiKey" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label for="apiToken" value="{{ __('Access Token / Secret') }}" />
                <x-input id="apiToken" type="password" class="mt-1 block w-full" wire:model="apiToken" placeholder="Enter Token or Secret" />
                <x-input-error for="apiToken" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingInstallation')" wire:loading.attr="disabled">
                Cancel
            </x-secondary-button>

            <x-button class="ms-3 !bg-indigo-600" wire:click="saveInstallation" wire:loading.attr="disabled">
                Connect & Install
            </x-button>
        </x-slot>
    </x-dialog-modal>

</div>