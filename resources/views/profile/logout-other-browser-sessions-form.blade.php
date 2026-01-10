<div class="space-y-6">
    <div class="text-sm text-slate-600">
        {{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive.') }}
    </div>

    @if (count($this->sessions) > 0)
        <div class="space-y-3">
            @foreach ($this->sessions as $session)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-indigo-100 transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-400 shadow-sm border border-slate-100 group-hover:text-indigo-500 transition">
                            @if ($session->agent->isDesktop())
                                <i class="ri-macbook-line text-xl"></i>
                            @else
                                <i class="ri-smartphone-line text-xl"></i>
                            @endif
                        </div>

                        <div>
                            <div class="text-sm font-bold text-slate-900">
                                {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} 
                                <span class="text-slate-300 font-normal mx-1">|</span> 
                                {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                {{ $session->ip_address }}
                            </div>
                        </div>
                    </div>

                    <div class="text-xs font-bold">
                        @if ($session->is_current_device)
                            <span class="text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-100">{{ __('This device') }}</span>
                        @else
                            <span class="text-slate-400">{{ __('Last active') }} {{ $session->last_active }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex items-center mt-5">
        <x-button wire:click="confirmLogout" wire:loading.attr="disabled" class="!bg-slate-900 !rounded-xl !py-2.5">
            {{ __('Log Out Other Browser Sessions') }}
        </x-button>

        <x-action-message class="ms-3" on="loggedOut">
            <span class="text-green-600 text-sm font-bold">{{ __('Done.') }}</span>
        </x-action-message>
    </div>

    <x-dialog-modal wire:model.live="confirmingLogout">
        <x-slot name="title">
            {{ __('Log Out Other Browser Sessions') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}

            <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                <x-input type="password" class="mt-1 block w-3/4 !rounded-xl"
                            autocomplete="current-password"
                            placeholder="{{ __('Password') }}"
                            x-ref="password"
                            wire:model="password"
                            wire:keydown.enter="logoutOtherBrowserSessions" />

                <x-input-error for="password" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled" class="!rounded-xl">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 !rounded-xl !bg-red-600 hover:!bg-red-700"
                        wire:click="logoutOtherBrowserSessions"
                        wire:loading.attr="disabled">
                {{ __('Log Out Other Sessions') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>