<div>
    <!-- Generate API Token -->
    <x-form-section submit="createApiToken">
        <x-slot name="title">
            @lang('Create API Token')
        </x-slot>

        <x-slot name="description">
            @lang('API tokens allow third-party services to authenticate with our application on your behalf.')
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{__('Token Name')}}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="createApiTokenForm.name" autofocus />
                <x-input-error for="name" class="mt-2" />
            </div>

            <!-- Token Permissions -->
            @if (Laravel\Jetstream\Jetstream::hasPermissions())
            <div class="col-span-6">
                <x-label for="permissions" value="{{__('Permissions')}}" />

                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                    <label class="flex items-center">
                        <x-checkbox wire:model="createApiTokenForm.permissions" :value="$permission" />
                        <span class="ms-2 text-sm text-gray-600">{{ $permission }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="created">
                @lang('Created.')
            </x-action-message>

            <x-button>
                @lang('Create')
            </x-button>
        </x-slot>
    </x-form-section>

    @if ($this->user->tokens->isNotEmpty())
    <x-section-border />

    <!-- Manage API Tokens -->
    <div class="mt-10 sm:mt-0">
        <x-action-section>
            <x-slot name="title">
                @lang('Manage API Tokens')
            </x-slot>

            <x-slot name="description">
                @lang('You may delete any of your existing tokens if they are no longer needed.')
            </x-slot>

            <!-- API Token List -->
            <x-slot name="content">
                <div class="space-y-6">
                    @foreach ($this->user->tokens->sortBy('name') as $token)
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-white p-4 rounded-lg shadow-sm border border-slate-100 space-y-2 md:space-y-0">

                        <!-- Token info -->
                        <div class="break-all text-sm text-gray-700">
                            <div class="font-bold">{{ $token->name }}</div>
                            <div class="text-xs text-gray-500">
                                URL: {{ url('/') }}/api/webhook/{platform}/created?token={your_token}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 mt-2 md:mt-0">

                            <!-- Last used -->
                            @if ($token->last_used_at)
                            <div class="text-xs text-gray-400 whitespace-nowrap">
                                @lang('Last used') {{ $token->last_used_at->diffForHumans() }}
                            </div>
                            @endif

                            <!-- Permissions -->
                            @if (Laravel\Jetstream\Jetstream::hasPermissions())
                            <button class="cursor-pointer text-xs text-gray-400 underline whitespace-nowrap"
                                wire:click="manageApiTokenPermissions({{ $token->id }})">
                                @lang('Permissions')
                            </button>
                            @endif

                            <!-- Delete -->
                            <button class="cursor-pointer text-xs text-red-500 whitespace-nowrap"
                                wire:click="confirmApiTokenDeletion({{ $token->id }})">
                                @lang('Delete')
                            </button>
                        </div>

                    </div>
                    @endforeach
                </div>
            </x-slot>
        </x-action-section>
    </div>
    @endif

    <!-- Token Value Modal -->
    <x-dialog-modal wire:model.live="displayingToken">
        <x-slot name="title">
            @lang('API Token')
        </x-slot>

        <x-slot name="content">
            <div>
                @lang('Please copy your new API token. For your security, it won\'t be shown again.')
            </div>

            <x-input x-ref="plaintextToken" type="text" readonly :value="$plainTextToken"
                class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full break-all"
                autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                @showing-token-modal.window="setTimeout(() => $refs.plaintextToken.select(), 250)" />
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('displayingToken', false)" wire:loading.attr="disabled">
                @lang('Close')
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- API Token Permissions Modal -->
    <x-dialog-modal wire:model.live="managingApiTokenPermissions">
        <x-slot name="title">
            @lang('API Token Permissions')
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                <label class="flex items-center">
                    <x-checkbox wire:model="updateApiTokenForm.permissions" :value="$permission" />
                    <span class="ms-2 text-sm text-gray-600">{{ $permission }}</span>
                </label>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('managingApiTokenPermissions', false)" wire:loading.attr="disabled">
                @lang('Cancel')
            </x-secondary-button>

            <x-button class="ms-3" wire:click="updateApiToken" wire:loading.attr="disabled">
                @lang('Save')
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Token Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingApiTokenDeletion">
        <x-slot name="title">
            @lang('Delete API Token')
        </x-slot>

        <x-slot name="content">
            @lang('Are you sure you would like to delete this API token?')
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingApiTokenDeletion')" wire:loading.attr="disabled">
                @lang('Cancel')
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteApiToken" wire:loading.attr="disabled">
                @lang('Delete')
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>