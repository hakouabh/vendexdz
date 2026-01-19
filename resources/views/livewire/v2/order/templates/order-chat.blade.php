<div class="flex flex-col rounded-xl border border-green-200 bg-white shadow-sm overflow-hidden h-full max-h-[300px]">
    <div class="flex items-center justify-between px-3 py-2 border-b border-slate-100 bg-slate-50/50">
        <h5
            class="text-[11px] font-black uppercase text-slate-500 tracking-widest flex items-center gap-2">
            <i class="ri-discuss-line text-blue-500"></i> @lang('Internal Communication')
        </h5>
        <span
            class="text-[9px] font-bold px-2 py-0.5 bg-white border border-slate-200 rounded-full text-slate-400">
            @lang('Private')
        </span>
    </div>
    <div class="flex-1 p-3 bg-white overflow-y-auto max-h-[250px] custom-scrollbar space-y-4"
        id="chat-container">
        @forelse($activeOrder->chats->sortBy('created_at') as $log)
        @php $isMe = $log->aid === auth()->id(); @endphp
        <div class="flex flex-col {{ $isMe ? 'items-start' : 'items-end' }}">
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
            <p class="text-[10px] mt-2 italic">@lang('No internal messages yet.')</p>
        </div>
        @endforelse
    </div>
    <div class="p-3 bg-slate-50 border-t border-slate-100">
        <div class="relative flex items-center">
            <input type="text" wire:model.defer="newMessage"
                wire:keydown.enter="sendMessage" placeholder="Type an internal note..."
                class="w-full rounded-xl border border-slate-200 bg-white pl-4 pr-12 py-2 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all">

            <button wire:click="sendMessage"
                class="absolute right-1.5 p-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-sm">
                <i class="ri-send-plane-2-fill text-sm"></i>
            </button>
        </div>
        <p class="text-[9px] text-slate-400 mt-2 ml-1 italic">
            <i class="ri-information-line"></i> @lang('Press Enter to save note')
        </p>
    </div>
</div>