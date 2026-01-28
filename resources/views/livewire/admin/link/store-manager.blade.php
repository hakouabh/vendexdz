<div class="p-6 bg-slate-50/50 min-h-screen">

    <div class="flex flex-col lg:flex-row gap-6 items-start">
        <div class="w-full lg:w-2/3">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">@lang('Stores')</h2>
                        <p class="text-sm text-slate-500 mt-1">@lang('Manage your active Stores.')</p>
                    </div>
                    {{--<div class="relative w-72 group hidden md:block">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Search..."
                            class="w-full pl-10 pr-3 py-2 bg-gray-50 border border-transparent focus:bg-white focus:border-indigo-200 focus:ring-2 focus:ring-indigo-50 rounded-lg outline-none text-sm transition text-slate-700 placeholder-gray-400">
                    </div>--}}
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50 text-slate-500 text-xs uppercase font-semibold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">@lang('Store')</th>
                                <th class="px-6 py-4">@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($stores as $store)
                            <tr wire:click="SelectStore({{$store->id}})" class="hover:bg-gray-50/80 transition duration-150 group" style="cursor: pointer;">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                            {{$store->short_name}}
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900">{{$store->name}}</div>
                                            <div class="text-xs text-slate-400">{{__('whatsapp') .': '. $store->phone}}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($store->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Disactive
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                {{ $stores->links() }}
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/3 flex flex-col gap-6">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden w-full">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Managers</h2>
                    </div>
                    <i class="ri-user-settings-line text-amber-500"></i>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 text-slate-500 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-5 py-2">Name</th>
                            <th class="px-5 py-2 text-right">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @if($store)
                        @foreach($store->managers as $manager)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-5 py-3 font-medium text-slate-700">{{$manager->name}}</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-slate-400 hover:text-indigo-600 transition"><i
                                        class="ri-delete-bin-line text-lg"></i></button>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden w-full">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Agents</h2>
                    </div>
                    <i class="ri-team-line text-amber-500"></i>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 text-slate-500 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-5 py-2">@lang('Name')</th>
                            <!-- <th class="px-5 py-2 text-right">Number Orders</th> -->
                            <th class="px-5 py-2 text-right">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @if($store)
                            @foreach($store->agents as $agent)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-3 font-medium text-slate-700">{{$agent->name}}</td>
                                <!-- <td class="px-5 py-3 text-right text-green-600 font-bold">{{$agent->name}}</td> -->
                                <td class="px-6 py-4 text-right">
                                    <button class="text-slate-400 hover:text-indigo-600 transition"><i
                                            class="ri-delete-bin-line text-lg"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>