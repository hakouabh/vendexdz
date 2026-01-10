<form wire:submit.prevent="updatePassword" class="space-y-5">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="col-span-1 md:col-span-2">
            <x-label for="current_password" value="{{ __('Current Password') }}" class="mb-2 !text-slate-500 !font-bold" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="ri-lock-unlock-line"></i>
                </div>
                <x-input id="current_password" type="password" class="pl-10 w-full md:w-2/3 !bg-slate-50 !border-slate-200 !rounded-xl focus:!ring-indigo-500 focus:!border-indigo-500 !py-2.5" wire:model="state.current_password" autocomplete="current-password" />
            </div>
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div>
            <x-label for="password" value="{{ __('New Password') }}" class="mb-2 !text-slate-500 !font-bold" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="ri-key-2-line"></i>
                </div>
                <x-input id="password" type="password" class="pl-10 w-full !bg-slate-50 !border-slate-200 !rounded-xl focus:!ring-indigo-500 focus:!border-indigo-500 !py-2.5" wire:model="state.password" autocomplete="new-password" />
            </div>
            <x-input-error for="password" class="mt-2" />
        </div>

        <div>
            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="mb-2 !text-slate-500 !font-bold" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="ri-shield-check-line"></i>
                </div>
                <x-input id="password_confirmation" type="password" class="pl-10 w-full !bg-slate-50 !border-slate-200 !rounded-xl focus:!ring-indigo-500 focus:!border-indigo-500 !py-2.5" wire:model="state.password_confirmation" autocomplete="new-password" />
            </div>
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </div>

    <div class="flex items-center justify-end pt-2">
        <x-action-message class="me-3" on="saved">
             <span class="text-green-600 font-bold text-sm flex items-center gap-1"><i class="ri-check-line"></i> {{ __('Updated') }}</span>
        </x-action-message>

        <x-button class="!bg-slate-900 hover:!bg-slate-800 !rounded-xl !py-2.5 !px-6 !font-bold shadow-lg">
            {{ __('Update Password') }}
        </x-button>
    </div>
</form>