<div>
    <div>
        <!-- Header Section -->
        <div class="mb-5 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">@lang('Performance Dashboard')</h1>
                    <span
                        class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full border border-green-200 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> @lang('Online')
                    </span>
                </div>
                <p class="text-slate-500 text-sm font-medium">
                    @if($start_date && $end_date)
                    {{ \Carbon\Carbon::parse($start_date)->format('l, d M') }} • <span
                        class="text-indigo-600 font-bold"></span>
                    {{ \Carbon\Carbon::parse($end_date)->format('l, d M') }} <span
                        class="text-indigo-600 font-bold"></span>
                    @else
                    {{ \Carbon\Carbon::parse($selectedDate)->format('l, d M') }} • <span
                        class="text-indigo-600 font-bold"></span>
                    @endif
                </p>
            </div>
        </div>
        <div class="mb-8 flex flex-col lg:flex-row lg:items-center gap-3 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm w-full">
            <!-- Date Range -->
            <div class="w-full lg:w-auto">
                <div class="flex flex-col sm:flex-row sm:items-center bg-white border border-gray-300 rounded-md shadow-sm divide-y sm:divide-y-0 sm:divide-x divide-gray-300 focus-within:ring-1 focus-within:ring-indigo-500">
                    <div class="flex items-center px-2 py-1 w-full">
                        <span class="text-[8px] uppercase font-bold text-gray-400 mr-2">@lang('From')</span>
                        <input type="date" wire:model.live="start_date"
                            class="border-none p-0 text-xs focus:ring-0 w-full text-gray-700" />
                    </div>
                    <div class="flex items-center px-2 py-1 w-full">
                        <span class="text-[8px] uppercase font-bold text-gray-400 mr-2">@lang('To')</span>
                        <input type="date" wire:model.live="end_date"
                            class="border-none p-0 text-xs focus:ring-0 w-full text-gray-700" />
                    </div>

                    @if($start_date || $end_date)
                    <button wire:click="$set('start_date', null); $set('end_date', null);"
                        class="px-3 py-1 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-col md:flex-row gap-3 w-full">

                <!-- Product Dropdown -->
                <div class="flex-1" x-data="{ open: false }">
                    <div class="relative rounded-md shadow-sm">

                        <button @click="open = !open" type="button"
                            class="w-full text-xs pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white text-left flex items-center justify-between">

                            <span class="block truncate">{{ $selectedProductDisplayName }}</span>

                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-3 w-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                </svg>
                            </div>

                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 transform transition-transform"
                                    :class="{'rotate-180': open}" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>

                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open=false"
                            class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md overflow-auto">

                            <div @click="$wire.set('selectedProduct', ''); open=false"
                                class="cursor-pointer py-2 pl-10 pr-4 hover:bg-gray-100 text-xs">
                                @lang('All Products')
                            </div>

                            @foreach($products as $product)
                            <div @click="$wire.set('selectedProduct', '{{ $product->id }}'); open=false"
                                class="cursor-pointer py-2 pl-10 pr-4 hover:bg-gray-100 text-xs">
                                {{ $product->name }}
                            </div>
                            @endforeach

                        </div>

                    </div>
                </div>

                <!-- Quick Buttons -->
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('selectedDate', '{{ \Carbon\Carbon::today()->format('Y-m-d') }}')"
                        class="px-4 py-2 bg-gray-900 text-white text-xs rounded-lg hover:bg-green-600">
                        @lang('Today')
                    </button>

                    <button wire:click="$set('selectedDate', '{{ \Carbon\Carbon::yesterday()->format('Y-m-d') }}')"
                        class="px-4 py-2 bg-white text-gray-700 text-xs rounded-lg border border-gray-300 hover:bg-gray-50">
                        @lang('Yesterday')
                    </button>
                </div>
            </div>

        </div>

        <!-- Order Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div
                class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-[0_4px_20px_-4px_rgba(99,102,241,0.1)] relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
                    <i
                        class="ri-box-3-line text-8xl text-gray-200 transform rotate-12 -translate-y-4 translate-x-4"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">@lang('Total Order')</p>
                    <div class="flex items-baseline gap-1 mb-2">
                        <h3 class="text-3xl font-black text-slate-900">{{ $orderStats['total'] }}</h3>
                        <span class="text-sm font-bold text-slate-400">@lang('Orders')</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="bg-yellow-50 text-yellow-700 border border-yellow-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="ri-time-line"></i> {{ $performanceData['pending'] }} @lang('Pending')
                        </span>
                        <!-- <span class="text-[10px] text-slate-400">@lang('From Total')</span>

                        <span class="text-[10px] text-slate-400">@lang('From Total')</span> -->
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 p-6 rounded-[24px] shadow-xl shadow-slate-200 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
                    <i
                        class="ri-checkbox-circle-line text-8xl text-green-200 transform rotate-12 -translate-y-4 translate-x-4"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-100 uppercase tracking-wider mb-2">@lang('Confirmed')</p>
                    <div class="flex items-baseline gap-1 mb-2">
                        <h3 class="text-3xl font-black text-slate-200">{{ $orderStats['confirmed'] }}</h3>
                        <span class="text-sm font-bold text-slate-400">@lang('Orders')</span>
                    </div>
                    <!-- <div class="flex items-center gap-2">
                        <span class="text-[10px] text-slate-400">@lang('From Total')</span>
                    </div> -->
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-[0_4px_20px_-4px_rgba(99,102,241,0.1)] relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
                    <i
                        class="ri-error-warning-line text-8xl text-yellow-300 transform rotate-12 -translate-y-4 translate-x-4"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">@lang('Cancelled')</p>
                    <div class="flex items-baseline gap-1 mb-2">
                        <h3 class="text-3xl font-black text-slate-900">{{ $orderStats['cancelled'] }}</h3>
                        <span class="text-sm font-bold text-slate-400">@lang('Orders')</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="bg-blue-50 text-blue-700 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="ri-file-copy-line"></i> {{ $performanceData['double'] }} @lang('Double')
                        </span>
                        <span
                            class="bg-gray-50 text-gray-800 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="ri-close-circle-line"></i> {{ $performanceData['false_rate'] }} @lang('False')
                        </span>
                        <span
                            class="bg-red-50 text-red-700 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="ri-truck-line"></i> {{ $performanceData['no_deliv'] }} @lang('no deliv')
                        </span>

                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[24px] shadow-xl shadow-slate-200 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
                    <i
                        class="ri-loader-4-line text-8xl text-indigo-200 transform rotate-12 -translate-y-4 translate-x-4"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">@lang('In Process')</p>
                    <div class="flex items-baseline gap-1 mb-2">
                        <h3 class="text-3xl font-black text-slate-900">{{ $orderStats['in_process'] }}</h3>
                        <span class="text-sm font-bold text-slate-400">@lang('Orders')</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="bg-orange-50 text-orange-700 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="ri-file-copy-line"></i> {{ $performanceData['no_answer'] }} N/A
                        </span>
                        <span
                            class="bg-purple-50 text-purple-800 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="ri-close-circle-line"></i> {{ $performanceData['reported'] }} @lang('REPORTED')
                        </span>
                        <span
                            class="bg-purple-50 text-purple-800 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="ri-close-circle-line"></i> {{ $performanceData['pre-confirmed'] }} @lang('pre-confirmed')
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[24px] shadow-xl shadow-slate-200 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
                    <i
                        class="ri-truck-line text-8xl text-green-400 transform rotate-12 -translate-y-4 translate-x-4"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">@lang('Delivered')</p>
                    <div class="flex items-baseline gap-1 mb-2">
                        <h3 class="text-3xl font-black text-slate-900">{{ $orderStats['delivered'] }}</h3>
                        <span class="text-sm font-bold text-slate-400">@lang('Orders')</span>
                    </div>
                    <!-- <div class="flex items-center gap-2">
                        <span class="text-[10px] text-slate-400">@lang('From Total')</span>
                    </div> -->
                </div>
            </div>

            <div class="bg-white p-6 rounded-[24px] shadow-xl shadow-slate-200 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
                    <i
                        class="ri-arrow-go-back-fill text-8xl text-red-600 transform rotate-12 -translate-y-4 translate-x-4"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">@lang('Return')</p>
                    <div class="flex items-baseline gap-1 mb-2">
                        <h3 class="text-3xl font-black text-slate-900">{{ $orderStats['returned'] }}</h3>
                        <span class="text-sm font-bold text-slate-400">@lang('Orders')</span>
                    </div>
                    <!-- <div class="flex items-center gap-2">
                        <span class="text-[10px] text-slate-400">@lang('From Total')</span>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="bg-white col-span-2 p-6 rounded-[24px] border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-slate-900 text-lg">
                        @lang('Confirmation & Delivery Overview')
                    </h3>

                    <!-- Range buttons -->
                    <div class="flex gap-2 d-none">
                        <button
                            wire:click="setRange('day')"
                            wire:loading.attr="disabled"
                            class="px-3 py-1 text-xs rounded-lg {{ $this->range === 'day' ? 'bg-indigo-500 text-white' : 'bg-slate-100' }}"
                        >
                            @lang('Day')
                        </button>

                        <button
                            wire:click="setRange('week')"
                            wire:loading.attr="disabled"
                            class="px-3 py-1 text-xs rounded-lg {{ $this->range === 'week' ? 'bg-indigo-500 text-white' : 'bg-slate-100' }}"
                        >
                            @lang('Week')
                        </button>

                        <button
                            wire:click="setRange('month')"
                            wire:loading.attr="disabled"
                            class="px-3 py-1 text-xs rounded-lg {{ $this->range === 'month' ? 'bg-indigo-500 text-white' : 'bg-slate-100' }}"
                        >
                            @lang('Month')
                        </button>
                    </div>
                </div>

                <!-- Chart -->
                <div
                    wire:ignore
                    x-data="confirmationChart(@js($chartData ?? []))"
                    x-init="init()"
                    class="relative h-72"
                >
                    <canvas x-ref="chartCanvas"></canvas>
                </div>
            </div>

            <!-- Include Chart.js library -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex flex-col">
                <h3 class="font-bold text-slate-900 text-lg mb-6 flex items-center gap-2">
                    <i class="ri-map-pin-line text-indigo-500"></i> @lang('Top Wilayas')
                </h3>
                <div class="flex-1 space-y-5 overflow-y-auto pr-2">
                    @foreach($topWilayas as $wilaya)
                    <div>
                        <div class="flex justify-between text-sm font-bold text-slate-700 mb-1">
                            <span>{{$wilaya->wilaya_id }} - {{$wilaya->wilaya_name}} ({{$wilaya->total_orders}} @lang('Orders'))</span>
                            <span>{{$wilaya->delivered_percentage}}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5">
                            <div class="bg-indigo-900 h-2.5 rounded-full" style="width: {{$wilaya->delivered_percentage}}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Performance Cycles Section -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-slate-900 mb-4 ml-1">@lang('Performance Cycles')</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Confirmation Chart -->
                <div 
                    wire:ignore
                    id="performanceChart"
                    x-data="performanceChart(@js($performanceData))"
                    x-init="init()"
                    class="group bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center hover:shadow-xl transition-all duration-500 w-full max-w-sm transform hover:-translate-y-1">

                    <!-- Animated border effect -->
                    <div
                        class="absolute inset-0 rounded-[24px]  opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                    </div>

                    <!-- Header with refresh icon -->
                    <div class="flex items-center justify-between w-full mb-4 relative z-10">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest animate-pulse">
                            @lang('Confirmation Rate')</h4>
                        <div
                            class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 transition-all duration-500 hover:rotate-180">
                            <i class="ri-refresh-line text-sm animate-spin-slow"></i>
                        </div>
                    </div>

                    <!-- Dynamic donut chart with enhanced animations -->
                    <div class="relative w-40 h-40 rounded-full flex items-center justify-center transition-all duration-1000 hover:scale-110 hover:rotate-12"
                        :style="{ background: getAngles(), transform: 'rotate(-90deg)' }">

                        <!-- Animated rings -->
                        <div class="absolute inset-0 rounded-full border-2 border-slate-100 animate-ping opacity-20">
                        </div>

                        <div class="absolute w-32 h-32 bg-white rounded-full flex flex-col items-center justify-center shadow-inner transition-all duration-500 hover:shadow-lg"
                            style="transform: rotate(90deg)">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">@lang('Rate')</span>
                            <span class="text-lg font-black mt-1 transition-all duration-500 animate-pulse"
                                :style="{ color: getTopReason().color }" x-text="getTopReason().label"></span>
                            <div class="h-1.5 w-8 mt-2 rounded-full transition-all duration-500"
                                :style="{ backgroundColor: getTopReason().color }"></div>
                        </div>
                    </div>

                    <!-- Stats display with animations -->
                    <div class="grid grid-cols-3 gap-2 w-full mt-6 px-4">
                        <template
                            x-for="(item, key) in { confirmed: confirmed, cancelled: cancelled, noAnswer: noAnswer }">
                            <div class="text-center transition-all duration-500 cursor-pointer transform hover:scale-105"
                                @click="item.show = !item.show"
                                :class="item.show ? 'opacity-100' : 'opacity-40 grayscale'">
                                <span class="block text-xs font-bold text-slate-400" x-text="item.label"></span>
                                <span class="block text-sm font-bold transition-all duration-500 animate-fade-in"
                                    :style="{ color: item.color }" x-text="item.val.toLocaleString()"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Second row of stats -->
                    <div class="grid grid-cols-3 gap-2 w-full mt-2 px-4">
                        <template x-for="(item, key) in { reported: reported, double: double, falserate: falserate }">
                            <div class="text-center transition-all duration-500 cursor-pointer transform hover:scale-105"
                                @click="item.show = !item.show"
                                :class="item.show ? 'opacity-100' : 'opacity-40 grayscale'">
                                <span class="block text-xs font-bold text-slate-400" x-text="item.label"></span>
                                <span class="block text-sm font-bold transition-all duration-500 animate-fade-in"
                                    :style="{ color: item.color }" x-text="item.val.toLocaleString()"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Footer with enhanced live indicator -->
                    <div
                        class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between w-full relative z-10">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest animate-pulse">@lang('Live Data')</span>
                        <div
                            class="flex items-center gap-2 bg-gradient-to-r from-green-50 to-emerald-50 px-3 py-1 rounded-full border border-green-200">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full absolute"></span>
                            <span class="text-[9px] font-bold text-green-700 uppercase">@lang('Updating')</span>
                        </div>
                    </div>
                </div>
                <div 
                wire:ignore
                id="deliveryChart"
                x-data="deliveryChart(@js($deliveryData))"
                x-init="init()"
                    class="group bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center hover:shadow-xl transition-all duration-500 w-full max-w-sm transform hover:-translate-y-1">
                    <div
                        class="absolute inset-0 rounded-[24px]  opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                    </div>

                    <!-- Header with refresh icon -->
                    <div class="flex items-center justify-between w-full mb-4 relative z-10">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest animate-pulse">@lang('Delivery Rate')
                        </h4>
                        <div
                            class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 transition-all duration-500 hover:rotate-180">
                            <i class="ri-refresh-line text-sm animate-spin-slow"></i>
                        </div>
                    </div>

                    <!-- Dynamic donut chart with enhanced animations -->
                    <div class="relative w-40 h-40 rounded-full flex items-center justify-center transition-all duration-1000 hover:scale-110 hover:rotate-12"
                        :style="{ background: getAngles(), transform: 'rotate(-90deg)' }">

                        <!-- Animated rings -->
                        <div class="absolute inset-0 rounded-full border-2 border-slate-100 animate-ping opacity-20">
                        </div>

                        <div class="absolute w-32 h-32 bg-white rounded-full flex flex-col items-center justify-center shadow-inner transition-all duration-500 hover:shadow-lg"
                            style="transform: rotate(90deg)">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">@lang('Rate')</span>
                            <span class="text-lg font-black mt-1 transition-all duration-500 animate-pulse"
                                :style="{ color: getTopReason().color }" x-text="getTopReason().label"></span>
                            <div class="h-1.5 w-8 mt-2 rounded-full transition-all duration-500"
                                :style="{ backgroundColor: getTopReason().color }"></div>
                        </div>
                    </div>

                    <!-- Stats display with animations -->
                    <div class="grid grid-cols-3 gap-2 w-full mt-6 px-4">
                        <template
                            x-for="(item, key) in { in_delivery: in_delivery, delivered: delivered, suspended: suspended }">
                            <div class="text-center transition-all duration-500 cursor-pointer transform hover:scale-105"
                                @click="item.show = !item.show"
                                :class="item.show ? 'opacity-100' : 'opacity-40 grayscale'">
                                <span class="block text-xs font-bold text-slate-400" x-text="item.label"></span>
                                <span class="block text-sm font-bold transition-all duration-500 animate-fade-in"
                                    :style="{ color: item.color }" x-text="item.val.toLocaleString()"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Second row of stats -->
                    <div class="grid grid-cols-3 gap-2 w-full mt-2 px-4">
                        <template x-for="(item, key) in { in_route: in_route, rReturn: rReturn }">
                            <div class="text-center transition-all duration-500 cursor-pointer transform hover:scale-105"
                                @click="item.show = !item.show"
                                :class="item.show ? 'opacity-100' : 'opacity-40 grayscale'">
                                <span class="block text-xs font-bold text-slate-400" x-text="item.label"></span>
                                <span class="block text-sm font-bold transition-all duration-500 animate-fade-in"
                                    :style="{ color: item.color }" x-text="item.val.toLocaleString()"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Footer with enhanced live indicator -->
                    <div
                        class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between w-full relative z-10">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest animate-pulse">Live
                            Data</span>
                        <div
                            class="flex items-center gap-2 bg-gradient-to-r from-green-50 to-emerald-50 px-3 py-1 rounded-full border border-green-200">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full absolute"></span>
                            <span class="text-[9px] font-bold text-green-700 uppercase">Updating</span>
                        </div>
                    </div>
                </div>

                <!-- Third Chart - Failure Reasons (Static) -->
                <div
                    class="pointer-events-none grayscale-50 opacity-50 bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center hover:shadow-md transition">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Failure Reasons</h4>

                    <div class="relative w-40 h-40 rounded-full flex items-center justify-center transition-transform hover:scale-105 duration-500"
                        style="background: conic-gradient(#ef4444 0% 40%, #f97316 40% 70%, #94a3b8 70% 100%);">
                        <div class="absolute w-32 h-32 bg-white rounded-full flex flex-col items-center justify-center">
                            <span class="text-xs font-bold text-slate-500 uppercase">Top Reason</span>
                            <span class="text-lg font-black text-slate-900 mt-1">No Answer</span>
                        </div>
                    </div>

                    <div class="flex flex-col w-full mt-4 space-y-2 px-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span>
                                No
                                Answer</span>
                            <span class="font-bold">40%</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="flex items-center gap-2"><span
                                    class="w-2 h-2 rounded-full bg-orange-500"></span>
                                Canceled</span>
                            <span class="font-bold">30%</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="flex items-center gap-2"><span
                                    class="w-2 h-2 rounded-full bg-slate-400"></span> Wrong
                                #</span>
                            <span class="font-bold">30%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-900 text-lg mb-6">@lang('Pipeline Flow') {{$pipeLineFlow['total']}} @lang('Orders')</h3>
                <div class="space-y-1">
                    <div class="relative group">
                        <div
                            class="flex items-center justify-between px-4 py-3 bg-slate-50 rounded-lg border-l-4 border-blue-600 z-10 relative w-[{{$pipeLineFlow['confirmed']['percent']+50}}%]">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-blue-600 shadow-sm">
                                    <i class="ri-shopping-cart-2-line"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase">@lang('Confirmed')</p>
                                    <p class="text-sm font-bold text-slate-900">{{$pipeLineFlow['confirmed']['count']}} @lang('Orders')</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-400">{{$pipeLineFlow['confirmed']['percent']}}%</span>
                        </div>
                    </div>
                    <div class="flex justify-center -my-2 relative z-0"><i class="ri-arrow-down-line text-slate-300"></i></div>
                    <div class="relative group">
                        <div
                            class="flex items-center justify-between px-4 py-3 bg-slate-50 rounded-lg border-l-4 border-indigo-500 z-10 relative w-[{{$pipeLineFlow['shipped']['percent']+50}}%]">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-indigo-500 shadow-sm">
                                    <i class="ri-truck-line"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase">@lang('Shipped')</p>
                                    <p class="text-sm font-bold text-slate-900">{{$pipeLineFlow['shipped']['count']}} @lang('Orders')</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-400">{{$pipeLineFlow['shipped']['percent']}}%</span>
                        </div>
                    </div>
                    <div class="flex justify-center -my-2 relative z-0"><i class="ri-arrow-down-line text-slate-300"></i></div>
                    <div class="relative group">
                        <div
                            class="flex items-center justify-between px-4 py-3 bg-green-50 rounded-lg border-l-4 border-green-500 z-10 relative w-[{{$pipeLineFlow['delivered']['percent']+50}}%]">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-green-600 shadow-sm">
                                    <i class="ri-home-smile-fill"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-green-700 uppercase">@lang('Delivered')</p>
                                    <p class="text-sm font-bold text-slate-900">{{$pipeLineFlow['delivered']['count']}} @lang('Orders')</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-green-700">{{$pipeLineFlow['delivered']['percent']}}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-slate-900 text-lg">@lang('Inventory Movers')</h3>
                </div>

                <div class="space-y-4">
                    @foreach($topProducts as $key => $product)
                    <div
                        class="flex items-center gap-2 p-2 hover:bg-slate-50 rounded-2xl transition group cursor-pointer border border-transparent hover:border-slate-100">
                        <div class="w-14 h-14 bg-gray-100 rounded-xl overflow-hidden relative">
                            <div class="w-full h-full bg-slate-200 flex items-center justify-center text-xl"><i class="ri-archive-line"></i></div>
                            @if($key == 0)
                            <div class="absolute top-0 right-0 bg-yellow-400 text-[8px] font-bold px-1.5 py-0.5 rounded-bl-lg">
                                #1 @lang('Top')</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600">{{$product->name}}</h4>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-slate-900">{{$product->total_qty_sold}}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">@lang('Sold')</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <script>
            const translations = {
                confirmed: "{{ __('Confirmed') }}",
                cancelled: "{{ __('Cancelled') }}",
                no_answer: "{{ __('No Answer') }}",
                reported: "{{ __('Reported') }}",
                double: "{{ __('Double') }}",
                false_rate: "{{ __('False Rate') }}",
                delivered: "{{ __('Delivered') }}",
                suspended: "{{ __('Suspended') }}",
                rReturn: "{{ __('Return') }}",
                in_delivery: "{{ __('In Delivery') }}",
                in_route: "{{ __('In Route') }}"
            };

            function performanceChart(initialData = {}) {
                return {
                    confirmed: {
                        val: 0,
                        show: true,
                        color: '#10b981',
                        label: translations.confirmed
                    },
                    cancelled: {
                        val: 0,
                        show: true,
                        color: '#ef4444',
                        label: translations.cancelled
                    },
                    noAnswer: {
                        val: 0,
                        show: true,
                        color: '#7bff00ff',
                        label: translations.no_answer
                    },
                    reported: {
                        val: 0,
                        show: true,
                        color: '#3b82f6',
                        label: translations.reported
                    },
                    double: {
                        val: 0,
                        show: true,
                        color: '#f59e0b',
                        label: translations.double
                    },
                    falserate: {
                        val: 0,
                        show: true,
                        color: '#8b5cf6',
                        label: translations.false_rate
                    },

                    init() {
                        this.updateData(initialData);
                    },
                    updateData(newData) {
                        this.confirmed.val = newData.confirmed ?? 0;
                        this.cancelled.val = newData.cancelled ?? 0;
                        this.noAnswer.val = newData.no_answer ?? 0;
                        this.reported.val = newData.reported ?? 0;
                        this.double.val = newData.double ?? 0;
                        this.falserate.val = newData.false_rate ?? 0;
                    },

                    getTotal() {
                        return (this.confirmed.show ? this.confirmed.val : 0) +
                            (this.cancelled.show ? this.cancelled.val : 0) +
                            (this.noAnswer.show ? this.noAnswer.val : 0) +
                            (this.reported.show ? this.reported.val : 0) +
                            (this.double.show ? this.double.val : 0) +
                            (this.falserate.show ? this.falserate.val : 0);
                    },

                    getAngles() {
                        let total = this.getTotal();
                        if (total === 0) return 'conic-gradient(#f1f5f9 0% 100%)';

                        let p1 = this.confirmed.show ? (this.confirmed.val / total) * 100 : 0;
                        let p2 = p1 + (this.cancelled.show ? (this.cancelled.val / total) * 100 : 0);
                        let p3 = p2 + (this.noAnswer.show ? (this.noAnswer.val / total) * 100 : 0);
                        let p4 = p3 + (this.reported.show ? (this.reported.val / total) * 100 : 0);
                        let p5 = p4 + (this.double.show ? (this.double.val / total) * 100 : 0);

                        return `conic-gradient(
                            ${this.confirmed.color} 0% ${p1}%,
                            ${this.cancelled.color} ${p1}% ${p2}%,
                            ${this.noAnswer.color} ${p2}% ${p3}%,
                            ${this.reported.color} ${p3}% ${p4}%,
                            ${this.double.color} ${p4}% ${p5}%,
                            ${this.falserate.color} ${p5}% 100%
                        )`;
                    },

                    getTopReason() {
                        let items = [
                            this.confirmed,
                            this.cancelled,
                            this.noAnswer,
                            this.reported,
                            this.double,
                            this.falserate
                        ].filter(item => item.show);

                        if (!items.length) {
                            return { label: 'No Data', color: '#94a3b8' };
                        }

                        return items.sort((a, b) => b.val - a.val)[0];
                    }
                }
            }

            function deliveryChart(initialData = {}) {
                return {
                    delivered: {
                        val: 0,
                        show: true,
                        color: '#10b981',
                        label: translations.delivered
                    },
                    suspended: {
                        val: 0,
                        show: true,
                        color: '#f59e0b',
                        label: translations.suspended
                    },
                    rReturn: {
                        val: 0,
                        show: true,
                        color: '#ef4444',
                        label: translations.rReturn
                    },
                    in_delivery: {
                        val: 0,
                        show: true,
                        color: '#3b82f6',
                        label: translations.in_delivery
                    },
                    in_route: {
                        val: 0,
                        show: true,
                        color: '#4c58f6',
                        label: translations.in_route
                    },

                    init() {
                        this.updateData(initialData);
                    },

                    updateData(newData) {
                        this.delivered.val = newData.delivered ?? 0;
                        this.suspended.val = newData.suspended ?? 0;
                        this.rReturn.val = newData.return ?? 0;
                        this.in_delivery.val = newData.in_delivery ?? 0;
                        this.in_route.val = newData.in_route ?? 0;
                    },

                    getTotal() {
                        return (this.delivered.show ? this.delivered.val : 0) +
                            (this.suspended.show ? this.suspended.val : 0) +
                            (this.rReturn.show ? this.rReturn.val : 0) +
                            (this.in_delivery.show ? this.in_delivery.val : 0) +
                            (this.in_route.show ? this.in_route.val : 0);
                    },

                    getAngles() {
                        let total = this.getTotal();
                        if (total === 0) return 'conic-gradient(#f1f5f9 0% 100%)';

                        let p1 = this.delivered.show ? (this.delivered.val / total) * 100 : 0;
                        let p2 = p1 + (this.suspended.show ? (this.suspended.val / total) * 100 : 0);
                        let p3 = p2 + (this.rReturn.show ? (this.rReturn.val / total) * 100 : 0);
                        let p4 = p3 + (this.in_delivery.show ? (this.in_delivery.val / total) * 100 : 0);

                        return `conic-gradient(
                            ${this.delivered.color} 0% ${p1}%,
                            ${this.suspended.color} ${p1}% ${p2}%,
                            ${this.rReturn.color} ${p2}% ${p3}%,
                            ${this.in_delivery.color} ${p3}% ${p4}%,
                            ${this.in_route.color} ${p4}% 100%
                        )`;
                    },

                    getTopReason() {
                        let items = [
                            this.delivered,
                            this.suspended,
                            this.rReturn,
                            this.in_delivery,
                            this.in_route
                        ].filter(item => item.show);

                        if (!items.length) {
                            return { label: 'No Data', color: '#94a3b8' };
                        }

                        return items.sort((a, b) => b.val - a.val)[0];
                    }
                }
            }
        </script>
        @script
        <script>
            $wire.on('delivery-updated', (event) => {
                const el = document.getElementById('deliveryChart')

                if (el && el._x_dataStack) {
                    el._x_dataStack[0].updateData(event.deliveryData)
                }
            })

            $wire.on('performance-updated', (event) => {
                const el = document.getElementById('performanceChart')

                if (el && el._x_dataStack) {
                    el._x_dataStack[0].updateData(event.performanceData)
                }
            })
            document.addEventListener('livewire:init', () => {
                Livewire.on('confirmation-data-updated', (event) => {

                    window.updateConfirmationChart?.(event.chartData)

                })
            })
        </script>
        @endscript
        <script>
            function confirmationChart(initialData = {}) {
    return {
        chart: null,
        data: initialData,

        init() {
            const ctx = this.$refs.chartCanvas.getContext('2d')

            this.chart = new Chart(ctx, {
                type: 'line',
                data: this.formatData(this.data),
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            })

            // expose global updater
            window.updateConfirmationChart = (newData) => {
                this.updateChart(newData)
            }
        },

        formatData(data = {}) {
            return {
                labels: (data.labels ?? []).map(String),
                datasets: [
                    {
                        label: "Confirmation",
                        data: data.confirmed ?? [],
                        borderColor: '#4f46e5',
                        fill: true
                    },
                    {
                        label: "Delivery",
                        data: data.delivered ?? [],
                        borderColor: '#10b981',
                        fill: true
                    }
                ]
            }
        },

        updateChart(newData) {
            if (!this.chart) return

            const formatted = this.formatData(newData)

            this.chart.data.labels = formatted.labels
            this.chart.data.datasets = formatted.datasets

            this.chart.update()
        }
    }
}
        </script>


        <!-- Keep the existing styles -->
        <style>
            .animate-spin-slow {
                animation: spin 4s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.5s ease-out;
            }

            /* Custom scrollbar */
            .scrollbar-thin::-webkit-scrollbar {
                width: 6px;
            }

            .scrollbar-thin::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 3px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>

    </div>
</div>