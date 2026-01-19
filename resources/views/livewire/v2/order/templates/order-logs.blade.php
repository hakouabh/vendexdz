<div class="flex flex-col h-full max-h-[300px]">
    <div class="flex items-center justify-between mb-4 sticky top-0 bg-white z-10 pb-2 border-b border-slate-50">
        <h5
            class="text-[11px] font-black uppercase text-slate-400 tracking-widest flex items-center gap-2">
            <i class="ri-history-line"></i> @lang('Order Activity')
        </h5>
        <span class="text-[9px] bg-indigo-50 px-2 py-0.5 rounded-full text-indigo-600 font-bold">
            {{ $activeOrder->logs->count() ?? 0}} @lang('Actions')
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
                            {{ $log->statusNew?->name ?? __('Status #') . $log->statu_new }}
                        </p>
                        <span class="text-[9px] font-medium text-slate-400">
                            {{ $log->created_at }}
                        </span>
                    </div>

                    <p class="text-[10px] text-slate-500 mt-0.5">
                        @lang('by') <span class="font-semibold text-slate-700">{{ $log->user?->name ?? __('System') }}</span>
                        @if($log->statu_new != $log->statu_old)
                        <span class="opacity-50 italic ml-1">
                            (from {{ $log->statusOld?->name ?? __('Initial') }} @lang('to')
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
                            @lang('Created')
                        </p>
                        <span class="text-[9px] font-medium text-slate-400">
                            {{ $activeOrder->created_at }}
                        </span>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-0.5">
                        @lang('by') <span class="font-semibold text-slate-700">@lang('System')</span>
                    </p>
                    <div class="mt-2 text-[10px] text-slate-500 bg-slate-50 border-l-2 border-slate-200 pl-2 py-1 italic rounded-r">
                        @lang('(This order has been created)')
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
                    @lang('by') <span class="font-semibold text-slate-700">@lang('System')</span>
                </p>
                <div class="mt-2 text-[10px] text-slate-500 bg-slate-50 border-l-2 border-slate-200 pl-2 py-1 italic rounded-r">
                    @lang('(This order has been created)')
                </div>
            </div>
        </div>
        <div class="text-center py-10 opacity-30">
            <i class="ri-inbox-line text-3xl"></i>
            <p class="text-xs mt-2">@lang('No history available')</p>
        </div>
        @endif
    </div>
</div>