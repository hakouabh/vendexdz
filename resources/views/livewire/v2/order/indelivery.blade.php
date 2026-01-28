<div class="w-full space-y-4">
    <div x-data="{ isExpanded: false }" class="w-full space-y-4">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#10F0B2]/10 flex items-center justify-center">
                    <i class="ri-dashboard-3-line text-[#10F0B2] text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 tracking-tight">@lang('Orders Overview')</h3>
                    <p class="text-[10px] text-slate-400 font-medium">@lang('Real-time status tracking')</p>
                </div>
            </div>
            <button @click="isExpanded = !isExpanded"
                class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                <span class="text-[11px] font-bold" x-text="isExpanded ? 'Collapse' : 'Expand'"></span>
                <i :class="isExpanded ? 'ri-subtract-line' : 'ri-add-line'" class="text-sm"></i>
            </button>
        </div>

        <div x-show="isExpanded" x-collapse x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            class="flex overflow-x-auto pb-4 gap-4 custom-scrollbar snap-x">

            @foreach($SecondStepStatus as $status)
            @if(in_array($status->ssid, [2, 3])) @continue @endif
            @php
            $count = $orders->where('Indelivery.SecondStepStatu.ssid', $status->ssid)->count();
            @endphp
            <div class="flex-none w-44 snap-start group">
                <div
                    class="relative bg-white border border-slate-100 rounded-[24px] px-4 py-2 shadow-sm group-hover:shadow-md group-hover:border-[#10F0B2]/30 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1" style="background-color: {{ $status->color }}"></div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="px-2.5 py-0.5 rounded-lg text-[10px] text-white font-bold uppercase tracking-wider"
                            style="background-color: {{ $status->color }}">
                            {{ __($status->name) }}
                        </div>
                        <div
                            class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-slate-600 group-hover:bg-slate-100 transition-colors">
                            <i class="{{ $status->icon }} text-base"></i>
                        </div>
                    </div>

                    <div class="flex gap-2 items-baseline">
                        <span class="text-3xl font-black text-slate-900 leading-none">{{ $count }} </span>
                        <p class="text-gray-400 font-bold">@lang('Orders')</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <style>
    /* Sleek Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        height: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 20px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 20px;
        transition: background 0.3s;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #979797ff;
    }
    </style>
    <div class="flex justify-between w-full font-bold ">
        <div class="flex gap-2 w-3/5 ">
            @if($context =='admin')
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button type="button"
                        class="inline-flex  items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        {{ __('Filter Store') }}
                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="block px-4 py-2 text-xs text-gray-400 uppercase tracking-widest">
                        {{ __('Stores') }}
                    </div>
                    <x-dropdown-link wire:click="Storefilter(null)" class="text-[]">
                        <i class=""></i> All
                    </x-dropdown-link>
                    @foreach($stores as $store)
                    <x-dropdown-link wire:click="Storefilter({{$store->id}})" class="text-[]">
                        <i class=""></i> {{$store->name}}
                    </x-dropdown-link>
                    @endforeach
                </x-slot>
            </x-dropdown>
            @endif
            <x-dropdown align="center" width="48">
                <x-slot name="trigger">
                    <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        {{ __('Filter Product') }}
                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="block px-4 py-2 text-xs text-gray-400 uppercase tracking-widest">
                        {{ __('Products') }}
                    </div>
                    <x-dropdown-link wire:click="Productfilter(null)" class="text-[]">
                        <i class=""></i> @lang('All')
                    </x-dropdown-link>
                    @foreach($products as $product)
                    <x-dropdown-link wire:click="Productfilter('{{$product->id}}')" class="text-[]">
                        <i class=""></i> {{$product->name}}
                    </x-dropdown-link>
                    @endforeach
                </x-slot>
            </x-dropdown>
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        {{ __('Filter Statu') }}
                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="block px-4 py-2 text-xs text-gray-400 uppercase tracking-widest">
                        {{ __('Status') }}
                    </div>
                    <div class="h-44 overflow-auto">
                        <x-dropdown-link wire:click="Statufilter(null)" class="text-[]">
                            <i class=""></i> @lang('All')
                        </x-dropdown-link>
                        @foreach($SecondStepStatus as $status)
                        <x-dropdown-link wire:click="Statufilter({{$status->ssid}})" class="text-[{{$status->color}}]">
                            <i class="{{$status->icon}}"></i> {{__($status->name)}}
                        </x-dropdown-link>
                        @endforeach
                    </div>
                </x-slot>
            </x-dropdown>
        </div>
        <div class="h-full flex gap-2 justify-end w-2/5 ">
            <div
                class="inline-flex items-center bg-white border border-gray-300 rounded-md shadow-sm divide-x divide-gray-300 focus-within:ring-1 focus-within:ring-indigo-500">

                <div class="flex items-center px-2 py-1">
                    <span class="text-[8px] uppercase font-bold text-gray-400 mr-2">@lang('From')</span>
                    <input type="date" wire:model.live="start_date"
                        class="border-none p-0 text-xs focus:ring-0 w-24 text-gray-700" />
                </div>

                <div class="flex items-center px-2 py-1">
                    <span class="text-[8px] uppercase font-bold text-gray-400 mr-2">@lang('To')</span>
                    <input type="date" wire:model.live="end_date"
                        class="border-none p-0 text-xs focus:ring-0 w-24 text-gray-700" />
                </div>
                @if($start_date || $end_date)
                <button wire:click="$set('start_date', null); $set('end_date', null);"
                    class="px-2 text-gray-400 hover:text-red-500 transition">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                @endif
            </div>
        </div>

    </div>
    <div class="hidden grid-cols-12 gap-4 px-6 text-[11px] font-bold uppercase tracking-widest text-gray-400 sm:grid">
        <div class="col-span-2">@lang('Order / Shop')</div>
        <div class="col-span-3">@lang('Customer')</div>
        <div class="col-span-3">@lang('Destination')</div>
        <div class="col-span-2">@lang('Status')</div>
        <div class="col-span-1 text-right">@lang('Pricing')</div>
        <div class="col-span-1"></div>
    </div>
    @foreach($orders as $order)
    <div class="space-y-3">
        <div class="group relative overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-[2px] hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-blue-600 to-indigo-600"></div>
            <div class="relative z-10 grid cursor-pointer grid-cols-12 items-center gap-4 py-4 pl-6 pr-4 transition-colors hover:bg-gray-50/50">
                <div class="col-span-12 flex flex-col justify-center sm:col-span-2">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-sm font-black text-gray-800">#{{ $order->id }}</span>
                        <span
                            class="flex items-center gap-1 rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-bold text-gray-600 border border-gray-200">
                            <i class="ri-store-2-line"></i> @lang('Shop')
                        </span>
                    </div>
                    <span class="mt-1 flex w-fit items-center gap-1 text-[10px] text-gray-400">
                        <i class="ri-calendar-line"></i> {{ $order->created_at?->format('d M, H:i') ?? 'N/A' }}
                    </span>
                </div>
                <div class="col-span-12 sm:col-span-3">
                    <div class="flex items-center gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span
                                    class="font-mono text-sm font-black text-gray-800">{{ $order->client->full_name ?? __('Unknown') }}</span>
                                @if($order->duplicated)
                                <span class="flex items-center gap-1 rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-bold text-gray-600 border border-gray-200">
                                    <i class="ri-user-3-line"></i>@lang('Double')
                                </span>
                                @endif
                            </div>
                            <a href="tel:0550123456"
                                class="mt-0.5 inline-flex items-center gap-1 text-xs text-gray-500 hover:text-green-600 transition">
                                <i class="ri-phone-fill"></i> {{ $order->client->phone_number_1 ?? __('Unknown') }}
                                /{{ $order->client->phone_number_2 ?? __('Unknown') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-3">
                    <div class="flex items-start gap-2">
                        <div class="mt-0.5 text-red-500">
                            <i class="ri-map-pin-2-fill text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="truncate font-medium text-gray-900 text-sm">
                                {{ $order->client->willaya->name ?? __('Unknown') }},
                                {{ $order->client->town ?? __('Unknown') }}</div>
                            <div class="text-[10px] text-gray-500 truncate"
                                title="{{ $order->client->town ?? __('Unknown') }}">
                                {{ $order->client->address ?? __('Unknown') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <div x-data="{
            open: false,
            buttonRect: null
        }" class="relative inline-block text-left">

                        <button @click="
                open = !open;
                buttonRect = $event.target.closest('button').getBoundingClientRect();
            " type="button" class="focus:outline-none">
                            @if($order->Indelivery?->SecondStepStatu)
                            <div class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset cursor-pointer hover:opacity-80 transition"
                                style="
                        background-color: {{ $order->Indelivery->SecondStepStatu->color }}15;
                        color: {{ $order->Indelivery->SecondStepStatu->color }};
                        ring-color: {{ $order->Indelivery->SecondStepStatu->color }}30;
                    ">

                                <div class="h-1.5 w-1.5 rounded-full animate-pulse"
                                    style="background-color: {{ $order->Indelivery->SecondStepStatu->color }}"></div>

                                @if($order->Indelivery->SecondStepStatu->icon)
                                <i class="{{ $order->Indelivery->SecondStepStatu->icon }}"></i>
                                @endif

                                {{ $order->Indelivery->SecondStepStatu->name }}

                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            @else
                            <span
                                class="text-gray-400 text-xs cursor-pointer border border-dashed border-gray-300 rounded-full px-2 py-1">
                                @lang('Select Status')
                            </span>
                            @endif
                        </button>
                        <template x-teleport="body">
                            <div x-show="open" x-cloak x-transition @click.away="open = false"
                                class="fixed z-[9999] w-48 rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 p-1"
                                :style="`
                    top: ${buttonRect ? buttonRect.bottom + window.scrollY + 6 : 0}px;
                    left: ${buttonRect ? buttonRect.left + window.scrollX : 0}px;
                `">
                                <div class="py-1">
                                    <p
                                        class="px-3 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest bg-white sticky top-0 z-10">
                                        @lang('Change Status')
                                    </p>
                                    <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                        @foreach($SecondStepStatus as $statu)
                                        <button wire:click="proposeStatus({{ $order->id }}, {{ $statu->ssid }})"
                                            @click="open = false"
                                            class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                            <div class="h-2 w-2 rounded-full"
                                                style="background-color: {{ $statu->color }}"></div>
                                            <i class="{{$statu->icon}}"></i>
                                            {{ __($statu->name) }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="col-span-6 text-cnter sm:col-span-1">
                    <div class="flex  items-baseline  justify-center">
                        <span class="text-sm font-black text-gray-900 whitespace-nowrap">{{$order->details?->total}}
                        </span><span class="text-[10px] font-extrabold text-gray-400">DZD</span>
                    </div>
                </div>
                <div class="col-span-12 flex justify-end sm:col-span-1">
                    <button wire:click="toggleExpand('{{ $order->oid }}')"
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-gray-50 text-gray-400 transition-all group-hover:bg-blue-50 group-hover:text-blue-600"
                        :class="{{$expandedOrderId == $order->oid}} ? 'rotate-180 bg-blue-100 text-blue-600' : ''">
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                </div>
            </div>

            @if($expandedOrderId == $order->oid)
            <div class="border-t border-gray-100 bg-gray-50/50">

                <div class="flex  items-center justify-between gap-4 border-b border-gray-200 bg-white px-6 py-3">
                    <livewire:v2.order.templates.order-customer-info :activeOrder="$activeOrder" :canUpdate="false" :key="'order-customer-info-'.$activeOrder->id" />    
                </div>

                <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-4">
                    <livewire:v2.order.templates.order-items :canUpdate="false" :activeOrder="$activeOrder" :availableProducts="$availableProducts" :key="'order-items-'.$activeOrder->id" />
                    <livewire:v2.order.templates.order-price :key="'order-price-'.$activeOrder->id" />
                    <livewire:v2.order.templates.order-logs :order="$activeOrder" :key="'order-logs-'.$activeOrder->id" />
                    <style>
                    .custom-scrollbar::-webkit-scrollbar {
                        width: 4px;
                    }

                    .custom-scrollbar::-webkit-scrollbar-track {
                        background: transparent;
                    }

                    .custom-scrollbar::-webkit-scrollbar-thumb {
                        background: #e2e8f0;
                        border-radius: 10px;
                    }

                    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                        background: #cbd5e1;
                    }
                    </style>
                    <livewire:v2.order.templates.order-chat :order="$activeOrder" :key="'order-chat-'.$activeOrder->id" />
                </div>
                <div
                    class="flex flex-col items-center justify-between gap-4 border-t border-gray-100 bg-gray-50 px-6 py-4 sm:flex-row rounded-b-xl">
                    <button
                        wire:click="toggleExpand('{{ $order->oid }}')"
                        class="group flex items-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2 text-xs font-bold text-red-500 transition-all hover:bg-red-50 hover:border-red-300">
                        <i class="ri-close-circle-line text-sm transition-transform group-hover:rotate-90"></i>
                        @lang('Back')
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach
    <div class="mt-4">
      {{ $orders->links(data: ['pageName' => 'inPage']) }}
    </div>
    @if($showTimerModal)
    <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                wire:click="$set('showTimerModal', false)"></div>

            <div
                class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="ri-calendar-todo-line text-green-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Schedule Follow-up
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Please select the time for the next attempt/follow-up
                                    for this order.</p>
                            </div>

                            <div class="mt-4" wire:ignore>
                                <label class="block text-sm font-medium text-gray-700">Next Call Time
                                </label>
                                <input x-data x-init="flatpickr($el, {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',       // Internal format for Livewire
            altInput: true,                // Enables a second input for display
            altFormat: 'd/m/Y H:i',        // THE DISPLAY FORMAT YOU WANT
            minDate: 'today',              // Cannot select past days
            minTime: '{{ now()->format('H:i') }}', // Limit starting from current hour
            time_24hr: true,
            // LIMIT HOURS: Only allow 08:00 to 20:00
            onOpen: function(selectedDates, dateStr, instance) {
                instance.set('minTime', new Date().getHours() + ':' + new Date().getMinutes());
            }
        })" type="text" wire:model="scheduleTime"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" wire:click="confirmStatusWithTimer"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm & Save
                    </button>
                    <button type="button" wire:click="$set('showTimerModal', false)"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>