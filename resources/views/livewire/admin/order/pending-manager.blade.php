<div class="w-full space-y-4">
    <div x-data="{ isExpanded: false }" class="w-full space-y-4">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#10F0B2]/10 flex items-center justify-center">
                    <i class="ri-dashboard-3-line text-[#10F0B2] text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 tracking-tight">Orders Overview</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Real-time status tracking</p>
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

            @foreach($AcceptStepStatus as $status)

            @php
            $count = $orders->where('Waiting.AcceptStepStatu.asid', $status->asid)->count();
            @endphp
           
 
            <div class="flex-none w-44 snap-start group">
                <div
                    class="relative bg-white border border-slate-100 rounded-[24px] px-4 py-2 shadow-sm group-hover:shadow-md group-hover:border-[#10F0B2]/30 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1" style="background-color: {{ $status->color }}"></div>

                    <div class="flex items-center justify-between mb-2">
                        <div class="px-2.5 py-0.5 rounded-lg text-[10px] text-white font-bold uppercase tracking-wider"
                            style="background-color: {{ $status->color }}">
                            {{ $status->name }}
                        </div>
                        <div
                            class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-slate-600 group-hover:bg-slate-100 transition-colors">
                            <i class="{{ $status->icon }} text-base"></i>
                        </div>
                    </div>

                    <div class="flex gap-2 items-baseline">
                        <span class="text-3xl font-black text-slate-900 leading-none">{{ $count }} </span>
                        <p class="text-gray-400 font-bold">order</p>

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
                        <i class=""></i> All
                    </x-dropdown-link>
                    @foreach($products as $product)
                    <x-dropdown-link wire:click="Productfilter('{{$product->sku}}')" class="text-[]">
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
                            <i class=""></i> All
                        </x-dropdown-link>
                        @foreach($AcceptStepStatus as $status)
                        <x-dropdown-link wire:click="Statufilter({{$status->asid}})" class="text-[{{$status->color}}]">
                            <i class="{{$status->icon}}"></i> {{$status->name}}
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
                    <span class="text-[8px] uppercase font-bold text-gray-400 mr-2">From</span>
                    <input type="date" wire:model.live="start_date"
                        class="border-none p-0 text-xs focus:ring-0 w-24 text-gray-700" />
                </div>

                <div class="flex items-center px-2 py-1">
                    <span class="text-[8px] uppercase font-bold text-gray-400 mr-2">To</span>
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
        <div class="col-span-2">Order / Shop</div>

        <div class="col-span-3">Customer</div>

        <div class="col-span-3">Destination</div>

        <div class="col-span-2">Status</div>

        <div class="col-span-1 text-right">Pricing</div>

        <div class="col-span-1"></div>
    </div>
    @foreach($orders as $order)
    <div class="space-y-3">

        <div
            class="group relative overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-[2px] hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)]">

            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-blue-600 to-indigo-600"></div>

            <div
                class="relative z-10 grid cursor-pointer grid-cols-12 items-center gap-4 py-4 pl-6 pr-4 transition-colors hover:bg-gray-50/50">

                <div class="col-span-12 flex flex-col justify-center sm:col-span-2">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-sm font-black text-gray-800">#{{ $order->id }}</span>
                        <span
                            class="flex items-center gap-1 rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-bold text-gray-600 border border-gray-200">
                            <i class="ri-store-2-line"></i> Tech
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
                                    class="font-mono text-sm font-black text-gray-800">{{ $order->client->full_name ?? 'Unknown' }}</span>
                                <span
                                    class="flex items-center gap-1 rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-bold text-gray-600 border border-gray-200">
                                    <i class="ri-user-3-line"></i>Double
                                </span>
                            </div>

                            <a href="tel:0550123456"
                                class="mt-0.5 inline-flex items-center gap-1 text-xs text-gray-500 hover:text-green-600 transition">
                                <i class="ri-phone-fill"></i> {{ $order->client->phone_number_1 ?? 'Unknown' }}
                                /{{ $order->client->phone_number_2 ?? 'Unknown' }}

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
                                {{ $order->client->willaya->name ?? 'Unknown' }},
                                {{ $order->client->town ?? 'Unknown' }}</div>
                            <div class="text-[10px] text-gray-500 truncate"
                                title="{{ $order->client->town ?? 'Unknown' }}">
                                {{ $order->client->address ?? 'Unknown' }}
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
                            @if($order->Waiting?->AcceptStepStatu)
                            <div class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset cursor-pointer hover:opacity-80 transition"
                                style="
                        background-color: {{ $order->Waiting->AcceptStepStatu->color }}15;
                        color: {{ $order->Waiting->AcceptStepStatu->color }};
                        ring-color: {{ $order->Waiting->AcceptStepStatu->color }}30;
                    ">

                                <div class="h-1.5 w-1.5 rounded-full animate-pulse"
                                    style="background-color: {{ $order->Waiting->AcceptStepStatu->color }}"></div>

                                @if($order->Waiting->AcceptStepStatu->icon)
                                <i class="{{ $order->Waiting->AcceptStepStatu->icon }}"></i>
                                @endif

                                {{ $order->Waiting->AcceptStepStatu->name }}

                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            @else
                            <span
                                class="text-gray-400 text-xs cursor-pointer border border-dashed border-gray-300 rounded-full px-2 py-1">
                                Select Status
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
                                        Change Status
                                    </p>

                                    <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                        @foreach($AcceptStepStatus as $statu)
                                        <button wire:click="proposeStatus({{ $order->id }}, {{ $statu->asid }})"
                                            @click="open = false"
                                            class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                            <div class="h-2 w-2 rounded-full"
                                                style="background-color: {{ $statu->color }}"></div>
                                            <i class="{{$statu->icon}}"></i>
                                            {{ $statu->name }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>


                <div class="col-span-6 text-cnter sm:col-span-1">
                    <div class="flex gap-2 items-center  justify-center">
                        <button wire:click="shipNow('{{$order->tracking}}')" class="bg-gray-200 w-8 h-8 text-gray-600 hover:scale-105 hover:text-green-600 hover:bg-green-200 rounded-lg "><i class="ri-send-plane-line  text-sm"></i></button>
                        <button wire:click="RemoveNow('{{$order->tracking}}')" class="bg-gray-200 w-8 h-8 text-gray-600 hover:scale-105 hover:text-red-600 hover:bg-red-200 rounded-lg "><i class="ri-delete-bin-line  text-sm"></i></button>
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
                    <div class="grid grid-cols-1 gap-4 w-full sm:grid-cols-3">
                        <div class="sm:col-span-1">
                            <label
                                class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-gray-400">Full
                                Name @error('client_name') <p class=" text-[8px] text-red-600"> {{ $message }}</p>
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
                                class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-gray-400">Phone
                                Number @error('phone1') <p class=" text-[8px] text-red-600"> {{ $message }}</p>
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
                            <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">Phone Number
                                2</label>
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
                                class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-gray-400">Wilaya
                                @error('wilaya') <p class=" text-[8px] text-red-600"> {{ $message }}</p>
                                @enderror</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="ri-map-pin-line text-gray-400"></i>
                                </div>
                                <select wire:model.live="wilaya"
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
                                class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-gray-400">City
                                @error('city') <p class=" text-[8px] text-red-600"> {{ $message }}</p> @enderror</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="ri-map-pin-line text-gray-400"></i>
                                </div>
                                <select wire:model.live="city"
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
                                class="flex gap-1 items-center mb-1 block text-[10px] font-bold uppercase text-gray-400">Address
                                /
                                Commune @error('address') <p class=" text-[8px] text-red-600"> {{ $message }}</p>
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
                            <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">Delivery
                                Note</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="ri-sticky-note-line text-gray-400"></i>
                                </div>
                                <input type="text" wire:model="Comment" placeholder="send it fast"
                                    class="block w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-xs font-bold text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">Delivery
                                Type</label>
                            <div class="flex rounded-lg bg-gray-100 p-1">
                                <button wire:click="$set('delivery_type', '1')"
                                    {{ !$can_use_stopdesk ? 'disabled' : '' }}
                                    class="flex-1 rounded py-1.5 text-[10px] font-bold  {{ $delivery_type=='1'?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}">
                                    <span>Stopdesk</span>
                                    @if(!$can_use_stopdesk)
                                    <span class="text-[8px] text-red-500">Not Available</span>
                                    @endif
                                </button>
                                <button wire:click="$set('delivery_type', '0')"
                                    class="flex-1 rounded py-1.5 text-[10px] font-bold {{ $delivery_type=='0'?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}">Domicile</button>
                            </div>
                        </div>
                        <div class="sm:col-span-1" x-data="{ type: 'Normal' }">
                            <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">Order Type</label>
                            <div class="flex rounded-lg bg-gray-100 p-1">
                                <button @click="type = 'Normal'"
                                    :class="type === 'Normal' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                                    class="flex-1 rounded-md py-1.5 text-[10px] font-bold transition-all">
                                    Normal
                                </button>
                                <button @click="type = 'quantity_break'"
                                    :class="type === 'quantity_break' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                                    class="flex-1 rounded-md py-1.5 text-[10px] font-bold transition-all">
                                    Quantity Break
                                </button>
                                <button @click="type = 'upsell'"
                                    :class="type === 'upsell' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                                    class="flex-1 rounded-md py-1.5 text-[10px] font-bold transition-all">
                                    Up Sell
                                </button>
                                <button @click="type = 'crossell'"
                                    :class="type === 'crossell' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                                    class="flex-1 rounded-md py-1.5 text-[10px] font-bold transition-all">
                                    Cross Sell
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-4">
                    <div class="space-y-4">
                        <h5 class="text-[11px] font-bold uppercase text-gray-400">Order Items</h5>
                        @error('items') <p class=" text-[15px] text-red-600"> {{ $message }}</p> @enderror
                        @error('sku') <p class=" text-[15px] text-red-600"> {{ $message }}</p> @enderror

                        @foreach($items as $index => $item)
                        <div
                            class="group relative p-2.5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-emerald-500 transition-all duration-300">

                            <div class="flex items-center gap-3">
                                <div
                                    class="hidden sm:flex h-8 w-8 shrink-0 rounded-lg bg-slate-50 items-center justify-center text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors">
                                    <i class="ri-archive-line text-base"></i>
                                </div>
                                <div>
                                    <div class="flex-1 min-w-[120px]">
                                        <select wire:model.live="items.{{ $index }}.sku"
                                            class="w-full rounded-xl border-none bg-slate-50 p-2 text-[11px] font-bold text-slate-700 focus:ring-1 focus:ring-emerald-500 outline-none transition-all">
                                            <option value="">-- Product --</option>
                                            @foreach($this->availableProducts as $prod)
                                            <option value="{{ $prod->sku }}">{{ $prod->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="flex-1 min-w-[120px]">
                                        <select wire:model.live="items.{{ $index }}.vid"
                                            {{ empty($item['sku']) ? 'disabled' : '' }}
                                            class="w-full rounded-xl border-none bg-slate-50 p-2 text-[11px] font-bold {{ empty($item['product_id']) ? 'text-slate-300' : 'text-slate-700' }} focus:ring-1 focus:ring-emerald-500 outline-none transition-all">
                                            <option value="">-- Variant --</option>
                                            @if(!empty($item['sku']))
                                            @foreach($this->getVariants($item['sku']) as $v)
                                            <option value="{{ $v->id }}">{{ $v->var_1 }} ({{ $v->var_2 }})</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-16 flex items-center bg-slate-900 rounded-lg px-2 py-1 shadow-sm">
                                        <span class="text-[9px] font-black text-slate-400 uppercase mr-1">Q:</span>
                                        <input type="number" wire:model.live="items.{{ $index }}.quantity"
                                            class="w-10 border-none bg-transparent text-center text-[11px] font-black text-white focus:ring-0 p-0">
                                    </div>

                                    <button wire:click="deleteItem({{ $item['id'] }}, {{ $index }})"
                                        class="h-8 w-8 shrink-0 flex items-center justify-center rounded-lg text-slate-300 hover:bg-rose-50 hover:text-rose-500 transition-all">
                                        <i class="ri-delete-bin-line text-base"></i>
                                    </button>
                                </div>
                            </div>

                            @if(!empty($item['sku']))
                            <div class="mt-1.5 flex items-center gap-2 px-1 animate-in fade-in">
                                <span
                                    class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded uppercase tracking-tighter">
                                    {{ $item['product_name'] }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase italic truncate">
                                    {{ $item['variant_info'] }}
                                </span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                        <button wire:click="addItem"
                            class="group/add flex w-full items-center justify-center gap-3 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-3 transition-all duration-300 hover:border-blue-500 hover:bg-blue-50/50">

                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-gray-200 transition-all group-hover/add:bg-blue-500 group-hover/add:ring-blue-500">
                                <i
                                    class="ri-add-line text-lg text-gray-500 transition-colors group-hover/add:text-white"></i>
                            </div>

                            <span
                                class="text-xs font-bold uppercase tracking-wider text-gray-500 transition-colors group-hover/add:text-blue-600">
                                Add New Item
                            </span>

                        </button>
                    </div>

                    <div class="space-y-4">
                        <h5 class="text-[11px] font-bold uppercase text-gray-400">Order Price</h5>
                        <div class="sm:col-span-1">
                            <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">Discount <br>(you
                                can discount enter 100 and 300 da )</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="ri-price-tag-3-line text-gray-400"></i>
                                </div>
                                <input type="text" value="0" wire:model.live="discount"
                                    class="block w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-xs font-bold text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-white p-3 text-xs">
                            <div class="flex justify-between py-1 text-gray-500"><span>Subtotal</span>
                                <span>{{$this->price}}
                                    DA</span>
                            </div>
                            <div class="flex justify-between py-1 text-gray-500"><span>Delivery</span>
                                <span>+{{$delivery_price}}
                                    DA</span>
                            </div>
                            <div class="flex justify-between py-1 text-red-600 font-medium"><span>Discount</span>
                                <span>-{{$discount}} DA</span>
                            </div>
                            <div
                                class="border-t border-gray-100 mt-2 pt-2 flex justify-between font-bold text-gray-900 text-sm">
                                <span>Total</span> <span>{{$total}} DA</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col h-full max-h-[300px]">
                        <div
                            class="flex items-center justify-between mb-4 sticky top-0 bg-white z-10 pb-2 border-b border-slate-50">
                            <h5
                                class="text-[11px] font-black uppercase text-slate-400 tracking-widest flex items-center gap-2">
                                <i class="ri-history-line"></i> Order Activity
                            </h5>
                            <span class="text-[9px] bg-indigo-50 px-2 py-0.5 rounded-full text-indigo-600 font-bold">
                                {{ $activeOrder->logs->count() ?? 0}} Actions
                            </span>
                        </div>

                        <div class="overflow-y-auto pr-3 custom-scrollbar">
                            @if($activeOrder && $activeOrder->logs->count() > 0)
                            <div class="relative border-l-2 border-slate-100 ml-3 space-y-8 pl-6 py-2">

                                @foreach($activeOrder->logs as $log)
                                <div class="relative group">
                                    @php $color = $log->statusNew?->color ?? '#cbd5e1'; @endphp
                                    <div class="absolute -left-[31px] top-1 h-4 w-4 rounded-full border-4 border-white shadow-sm z-10"
                                        style="background-color: {{ $color }};">
                                        @if($loop->first)
                                        <div class="absolute inset-0 rounded-full animate-ping opacity-40"
                                            style="background-color: {{ $color }};"></div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-between items-start">
                                            <p class="text-xs font-bold text-slate-800">
                                                {{ $log->statusNew?->name ?? 'Status #' . $log->statu_new }}
                                            </p>
                                            <span class="text-[9px] font-medium text-slate-400">
                                                {{ $log->created_at }}
                                            </span>
                                        </div>

                                        <p class="text-[10px] text-slate-500 mt-0.5">
                                            by <span
                                                class="font-semibold text-slate-700">{{ $log->user?->name ?? 'System' }}</span>
                                            @if($log->statu_new != $log->statu_old)
                                            <span class="opacity-50 italic ml-1">
                                                (from {{ $log->statusOld?->name ?? 'Initial' }} to
                                                {{ $log->statusNew?->name }})
                                            </span>
                                            @endif
                                        </p>

                                        @if($log->text)
                                        <div
                                            class="mt-2 text-[10px] text-slate-500 bg-slate-50 border-l-2 border-slate-200 pl-2 py-1 italic rounded-r">
                                            "{{ $log->text }}"
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                <div class="relative group">

                                    <div class="absolute -left-[31px] top-1 h-4 w-4 rounded-full border-4 border-white shadow-sm z-10"
                                        style="background-color: #fff;">

                                        <div class="absolute inset-0 rounded-full animate-ping opacity-40"
                                            style="background-color: #fff;;"></div>

                                    </div>

                                    <div class="flex flex-col">
                                        <div class="flex justify-between items-start">
                                            <p class="text-xs font-bold text-slate-800">
                                                Created
                                            </p>
                                            <span class="text-[9px] font-medium text-slate-400">
                                                {{ $activeOrder->created_at }}
                                            </span>
                                        </div>

                                        <p class="text-[10px] text-slate-500 mt-0.5">
                                            by <span class="font-semibold text-slate-700">System</span>


                                        </p>


                                        <div
                                            class="mt-2 text-[10px] text-slate-500 bg-slate-50 border-l-2 border-slate-200 pl-2 py-1 italic rounded-r">
                                            This order has been created
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="relative group">

                                <div class="absolute -left-[31px] top-1 h-4 w-4 rounded-full border-4 border-white shadow-sm z-10"
                                    style="background-color: #fff;">

                                    <div class="absolute inset-0 rounded-full animate-ping opacity-40"
                                        style="background-color: #fff;;"></div>

                                </div>

                                <div class="flex flex-col">
                                    <div class="flex justify-between items-start">
                                        <p class="text-xs font-bold text-slate-800">
                                            {{$activeOrder->created_at}}
                                        </p>
                                        <span class="text-[9px] font-medium text-slate-400">
                                            {{ $activeOrder->created_at }}
                                        </span>
                                    </div>

                                    <p class="text-[10px] text-slate-500 mt-0.5">
                                        by <span class="font-semibold text-slate-700">System</span>


                                    </p>


                                    <div
                                        class="mt-2 text-[10px] text-slate-500 bg-slate-50 border-l-2 border-slate-200 pl-2 py-1 italic rounded-r">
                                        This order has been created
                                    </div>

                                </div>
                            </div>
                            <div class="text-center py-10 opacity-30">
                                <i class="ri-inbox-line text-3xl"></i>
                                <p class="text-xs mt-2">No history available</p>
                            </div>

                            @endif
                        </div>
                    </div>

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

                    <div
                        class="flex flex-col rounded-xl border border-green-200 bg-white shadow-sm overflow-hidden h-full max-h-[300px]">
                        <div
                            class="flex items-center justify-between px-3 py-2 border-b border-slate-100 bg-slate-50/50">
                            <h5
                                class="text-[11px] font-black uppercase text-slate-500 tracking-widest flex items-center gap-2">
                                <i class="ri-discuss-line text-blue-500"></i> Internal Communication
                            </h5>
                            <span
                                class="text-[9px] font-bold px-2 py-0.5 bg-white border border-slate-200 rounded-full text-slate-400">
                                Private
                            </span>
                        </div>

                        <div class="flex-1 p-3 bg-white overflow-y-auto max-h-[250px] custom-scrollbar space-y-4"
                            id="chat-container">
                            @forelse($activeOrder->logs->sortBy('created_at') as $log)
                            @php $isMe = $log->aid === auth()->id(); @endphp

                            <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                                <span class="text-[9px] text-slate-400 mb-1 px-1">
                                    {{ $log->user?->name }} • {{ $log->created_at->format('H:i') }}
                                </span>

                                <div class="max-w-[90%] px-3 py-2 rounded-2xl text-xs shadow-sm
                    {{ $isMe 
                        ? 'bg-blue-600 text-white rounded-tr-none' 
                        : 'bg-slate-100 text-slate-700 rounded-tl-none border border-slate-200' 
                    }}">
                                    {{ $log->text }}
                                </div>
                            </div>
                            @empty
                            <div class="h-full flex flex-col items-center justify-center opacity-30 py-10">
                                <i class="ri-chat-history-line text-3xl"></i>
                                <p class="text-[10px] mt-2 italic">No internal messages yet.</p>
                            </div>
                            @endforelse
                        </div>

                        <div class="p-3 bg-slate-50 border-t border-slate-100">
                            <div class="relative flex items-center">
                                <input type="text" wire:model.defer="internal_message"
                                    wire:keydown.enter="addInternalNote" placeholder="Type an internal note..."
                                    class="w-full rounded-xl border border-slate-200 bg-white pl-4 pr-12 py-2 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all">

                                <button wire:click="addInternalNote"
                                    class="absolute right-1.5 p-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-sm">
                                    <i class="ri-send-plane-2-fill text-sm"></i>
                                </button>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 ml-1 italic">
                                <i class="ri-information-line"></i> Press Enter to save note
                            </p>
                        </div>
                    </div>

                </div>
                <div
                    class="flex flex-col items-center justify-between gap-4 border-t border-gray-100 bg-gray-50 px-6 py-4 sm:flex-row rounded-b-xl">

                    <button
                        class="group flex items-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2 text-xs font-bold text-red-500 transition-all hover:bg-red-50 hover:border-red-300">
                        <i class="ri-close-circle-line text-sm transition-transform group-hover:rotate-90"></i>
                        Back
                    </button>

                    <div class="flex flex-wrap gap-3">

                        <button wire:click="sendAllToShipping()" wire:loading.attr="disabled"
                            class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-700 transition-all hover:bg-gray-100 hover:text-blue-600 hover:border-gray-300 shadow-sm">
                            <i class="ri-truck-line text-sm"></i>
                            Setup Delivery
                        </button>

                        <button wire:click="saveOrder"
                            class="flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2 text-xs font-bold text-white shadow-md shadow-blue-100 transition-all hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5">
                            <i class="ri-save-3-line text-sm"></i>
                            Save
                        </button>
                    </div>
                </div>
            </div>
            @endif


        </div>
    </div>
    @endforeach
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