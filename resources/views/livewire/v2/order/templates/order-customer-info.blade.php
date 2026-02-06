<div class="grid grid-cols-1 gap-4 w-full sm:grid-cols-3">
    <div class="sm:col-span-1">
        <label class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-gray-400">@lang('Full Name')
            @error('client_name') <p class=" text-[8px] text-red-600"> {{ $message }}</p>
            @enderror</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="ri-user-smile-line text-gray-400"></i>
            </div>
            <input type="text" wire:model="client_name"
                class="block w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-xs font-bold text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
        </div>
    </div>
    <div class="sm:col-span-1">
        <label
            class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-gray-400">@lang('Phone Number')
            @error('phone1') <p class=" text-[8px] text-red-600"> {{ $message }}</p>
            @enderror</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="ri-phone-line text-gray-400"></i>
            </div>
            <input type="text" wire:model="phone1"
                class="block w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-xs font-bold text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
            <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                <button class="rounded bg-green-100 p-1 text-green-600 hover:bg-green-200">
                    <i class="ri-phone-fill"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="sm:col-span-1">
        <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">@lang('Phone Number 2')
        </label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="ri-phone-line text-gray-400"></i>
            </div>
            <input type="text" wire:model="phone2"
                class="block w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-xs font-bold text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
            <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                <button class="rounded bg-green-100 p-1 text-green-600 hover:bg-green-200">
                    <i class="ri-phone-fill"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="sm:col-span-1">
        <label
            class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-gray-400">@lang('Wilaya')
            @error('wilaya') <p class=" text-[8px] text-red-600"> {{ $message }}</p>
            @enderror</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="ri-map-pin-line text-gray-400"></i>
            </div>
            <select wire:model.live="wilaya"
                {{ $canUpdate ? '' : 'disabled' }}
                class="block w-full appearance-none rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-8 text-xs font-bold text-gray-700 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                @foreach($wilayas as $w)
                <option value="{{$w->wid}}">{{$w->wid}} {{$w->name}}</option>
                @endforeach
            </select>
            <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                <i class="ri-arrow-down-s-fill"></i>
            </div>
        </div>
    </div>
    <div class="sm:col-span-1">
        <label
            class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-gray-400">@lang('City')
            @error('city') <p class=" text-[8px] text-red-600"> {{ $message }}</p> @enderror</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="ri-map-pin-line text-gray-400"></i>
            </div>
            <select wire:model.live="city"
                {{ $canUpdate ? '' : 'disabled' }}
                class="block w-full appearance-none rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-8 text-xs font-bold text-gray-700 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                @foreach($communes as $commune)
                <option value="{{ $commune['name'] }}">
                    {{ $commune['name'] }}
                </option>
                @endforeach
            </select>
            <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                <i class="ri-arrow-down-s-fill"></i>
            </div>
        </div>
    </div>

    <div class="sm:col-span-1">
        <label
            class="flex gap-1 items-center mb-1 block text-[10px] font-bold uppercase text-gray-400">@lang('Address / Commune')
            @error('address') <p class=" text-[8px] text-red-600"> {{ $message }}</p>
            @enderror</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="ri-home-4-line text-gray-400"></i>
            </div>
            <input type="text" wire:model="address"
                class="block w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-xs font-bold text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
        </div>
    </div>
    <div class="sm:col-span-1">
        <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">@lang('Delivery Note')</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="ri-sticky-note-line text-gray-400"></i>
            </div>
            <input type="text" wire:model="Comment" placeholder="{{__('Delivery Note')}}"
                class="block w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-xs font-bold text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
        </div>
    </div>
    <div class="sm:col-span-1">
        <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">@lang('Delivery Type')</label>
        <div class="flex rounded-lg bg-gray-100 p-1">
            <button wire:click="setDelivery('1')"
                {{ !$can_use_stopdesk || !$canUpdate ? 'disabled' : '' }}
                class="flex-1 rounded py-1.5 text-[10px] font-bold  {{ $delivery_type=='1'?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}">
                <span>@lang('Stopdesk')</span>
                @if(!$can_use_stopdesk)
                <span class="text-[8px] text-red-500">@lang('Not Available')</span>
                @endif
            </button>
            <button wire:click="setDelivery('0')"
                {{ $canUpdate ? '' : 'disabled' }}
                class="flex-1 rounded py-1.5 text-[10px] font-bold {{ $delivery_type=='0'?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}">@lang('Domicile')</button>
        </div>
    </div>
    <div class="sm:col-span-1" x-data="{ type: @entangle('type') }">
        <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">@lang('Order Type')</label>
        <div class="flex rounded-lg bg-gray-100 p-1">
            <button wire:click="setType('normal')"
                {{ $canUpdate ? '' : 'disabled' }}
                :class="type === 'normal' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                class="flex-1 rounded-md py-1.5 text-[10px] font-bold transition-all">
                @lang('Normal')
            </button>
            <button wire:click="setType('quantity_break')"
                {{ $canUpdate ? '' : 'disabled' }}
                :class="type === 'quantity_break' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                class="flex-1 rounded-md py-1.5 text-[10px] font-bold transition-all">
                @lang('Quantity Break')
            </button>
            <button wire:click="setType('upsell')"
                {{ $canUpdate ? '' : 'disabled' }}
                :class="type === 'upsell' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                class="flex-1 rounded-md py-1.5 text-[10px] font-bold transition-all">
                @lang('Up Sell')
            </button>
            <button wire:click="setType('crossell')"
                {{ $canUpdate ? '' : 'disabled' }}
                :class="type === 'crossell' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                class="flex-1 rounded-md py-1.5 text-[10px] font-bold transition-all">
                @lang('Cross Sell')
            </button>
        </div>
    </div>
    @if($setupShippingErrorModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="closeShippingErrorModal"></div>
                <div
                    class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ri-close-line text-red-600 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">{{ $error_shipping_messages }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" wire:click="closeShippingErrorModal"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            @lang('Confirm')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>