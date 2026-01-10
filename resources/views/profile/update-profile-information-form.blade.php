<form wire:submit.prevent="updateProfileInformation" class="space-y-6">
    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
        <div x-data="{photoName: null, photoPreview: null}">
            <input type="file" id="photo" class="hidden"
                        wire:model.live="photo"
                        x-ref="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " />

            <div class="flex items-center gap-6">
                <div class="relative" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-2xl w-20 h-20 object-cover border-4 border-slate-50 shadow-sm">
                </div>

                <div class="relative" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-2xl w-20 h-20 bg-cover bg-no-repeat bg-center border-4 border-slate-50 shadow-sm"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <div class="flex flex-col gap-2">
                    <h4 class="text-sm font-bold text-slate-900">Profile Picture</h4>
                    <div class="flex gap-2">
                        <x-secondary-button class="!rounded-xl !text-xs !py-2" type="button" x-on:click.prevent="$refs.photo.click()">
                            {{ __('Change') }}
                        </x-secondary-button>

                        @if ($this->user->profile_photo_path)
                            <x-secondary-button type="button" class="!rounded-xl !text-xs !py-2 !text-red-600 hover:!bg-red-50" wire:click="deleteProfilePhoto">
                                {{ __('Remove') }}
                            </x-secondary-button>
                        @endif
                    </div>
                    <x-input-error for="photo" class="mt-2" />
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="col-span-1">
            <x-label for="name" value="{{ __('Display Name') }}" class="mb-2 !text-slate-500 !font-bold" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="ri-user-smile-line"></i>
                </div>
                <x-input id="name" type="text" class="pl-10 w-full !bg-slate-50 !border-slate-200 !rounded-xl focus:!ring-indigo-500 focus:!border-indigo-500 !py-2.5" wire:model="state.name" required autocomplete="name" />
            </div>
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-1">
            <x-label for="email" value="{{ __('Email Address') }}" class="mb-2 !text-slate-500 !font-bold" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="ri-mail-line"></i>
                </div>
                <x-input id="email" type="email" class="pl-10 w-full !bg-slate-50 !border-slate-200 !rounded-xl focus:!ring-indigo-500 focus:!border-indigo-500 !py-2.5" wire:model="state.email" required autocomplete="username" />
            </div>
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-yellow-50 rounded-xl border border-yellow-100 text-sm text-yellow-800">
                    {{ __('Your email is unverified.') }}
                    <button type="button" class="underline font-bold hover:text-yellow-900 ms-1" wire:click.prevent="sendEmailVerification">
                        {{ __('Resend Link') }}
                    </button>
                </div>

                @if ($this->verificationLinkSent)
                    <div class="mt-2 text-sm font-medium text-green-600">
                        {{ __('A new verification link has been sent.') }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="flex items-center justify-end pt-4 border-t border-slate-50">
        <x-action-message class="me-3" on="saved">
            <span class="text-green-600 font-bold text-sm flex items-center gap-1"><i class="ri-check-line"></i> {{ __('Saved') }}</span>
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo" class="!bg-indigo-600 hover:!bg-indigo-700 !rounded-xl !py-2.5 !px-6 !font-bold shadow-lg shadow-indigo-200">
            {{ __('Save Changes') }}
        </x-button>
    </div>
</form>