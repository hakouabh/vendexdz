<div class="space-y-6">
    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
        <div>
            <h3 class="text-sm font-bold text-slate-900">
                @if ($this->enabled)
                    @if ($showingConfirmation)
                        {{ __('Finish enabling 2FA') }}
                    @else
                        {{ __('You have enabled 2FA') }}
                    @endif
                @else
                    {{ __('You have not enabled 2FA') }}
                @endif
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                {{ __('Secure your account with an extra layer of protection.') }}
            </p>
        </div>
        
        @if ($this->enabled)
             <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                <i class="ri-shield-check-fill"></i> Enabled
            </span>
        @else
            <span class="bg-slate-200 text-slate-600 text-xs font-bold px-3 py-1 rounded-full">
                Disabled
            </span>
        @endif
    </div>

    <div class="text-sm text-slate-600 leading-relaxed">
        <p>
            {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
        </p>
    </div>

    @if ($this->enabled)
        @if ($showingQrCode)
            <div class="bg-white p-6 rounded-2xl border-2 border-dashed border-slate-200 text-center">
                <p class="font-bold text-slate-800 text-sm mb-4">
                    @if ($showingConfirmation)
                        {{ __('Scan this QR code to finish setup:') }}
                    @else
                        {{ __('Two factor authentication is enabled.') }}
                    @endif
                </p>

                <div class="inline-block p-2 bg-white rounded-lg shadow-sm border border-slate-100">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="mt-4">
                    <p class="text-xs text-slate-400 font-mono">Setup Key: <span class="font-bold text-slate-700 select-all">{{ decrypt($this->user->two_factor_secret) }}</span></p>
                </div>

                @if ($showingConfirmation)
                    <div class="mt-4 max-w-xs mx-auto">
                        <x-label for="code" value="{{ __('Enter Code from App') }}" class="!text-left mb-1" />
                        <div class="flex gap-2">
                             <x-input id="code" type="text" name="code" class="block w-full !rounded-xl !bg-slate-50" inputmode="numeric" autofocus autocomplete="one-time-code"
                                wire:model="code"
                                wire:keydown.enter="confirmTwoFactorAuthentication" />
                             <x-button class="!rounded-xl" wire:click="confirmTwoFactorAuthentication">{{ __('Confirm') }}</x-button>
                        </div>
                        <x-input-error for="code" class="mt-2 text-left" />
                    </div>
                @endif
            </div>
        @endif

        @if ($showingRecoveryCodes)
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center gap-2 mb-3 text-slate-800 font-bold text-sm">
                    <i class="ri-folder-keyhole-line text-indigo-500"></i> {{ __('Recovery Codes') }}
                </div>
                <p class="text-xs text-slate-500 mb-4">
                    {{ __('Store these codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                </p>

                <div class="grid grid-cols-2 gap-2 font-mono text-xs bg-white p-4 rounded-xl border border-slate-200 shadow-inner">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div class="select-all text-slate-600 hover:text-indigo-600 transition">{{ $code }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <div class="flex items-center gap-3 mt-5">
        @if (! $this->enabled)
            <x-confirms-password wire:then="enableTwoFactorAuthentication">
                <x-button type="button" wire:loading.attr="disabled" class="!bg-indigo-600 !rounded-xl !py-2.5">
                    {{ __('Enable 2FA') }}
                </x-button>
            </x-confirms-password>
        @else
            @if ($showingRecoveryCodes)
                <x-confirms-password wire:then="regenerateRecoveryCodes">
                    <x-secondary-button class="me-3 !rounded-xl">
                        {{ __('Regenerate Codes') }}
                    </x-secondary-button>
                </x-confirms-password>
            @elseif ($showingConfirmation)
                <x-secondary-button wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled" class="!rounded-xl">
                    {{ __('Cancel') }}
                </x-secondary-button>
            @else
                <x-confirms-password wire:then="showRecoveryCodes">
                    <x-secondary-button class="me-3 !rounded-xl">
                        {{ __('Show Recovery Codes') }}
                    </x-secondary-button>
                </x-confirms-password>
            @endif

            @if (! $showingConfirmation)
                <x-confirms-password wire:then="disableTwoFactorAuthentication">
                    <x-danger-button wire:loading.attr="disabled" class="!rounded-xl">
                        {{ __('Disable') }}
                    </x-danger-button>
                </x-confirms-password>
            @endif
        @endif
    </div>
</div>