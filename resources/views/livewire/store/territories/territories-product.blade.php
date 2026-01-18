<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
        <div>
            <h3 class="text-xs font-black uppercase text-slate-700 tracking-widest">
                Shipping Manager: <span class="text-indigo-600">{{ $product->name }}</span>
            </h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">SKU: {{ $product_id }}</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="syncAll" class="px-3 py-1.5 text-[10px] font-bold bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                Sync All to 1st Row
            </button>
            <button wire:click="save" class="px-4 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-sm">
                Save All Rates
            </button>
        </div>
    </div>

    <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
        <table class="w-full text-left">
            <thead class="sticky top-0 bg-white z-10 border-b border-slate-100 shadow-sm">
                <tr class="text-[10px] font-black text-slate-400 uppercase">
                    <th class="px-6 py-4">Wilaya</th>
                    <th class="px-4 py-4">3PL App</th>
                    <th class="px-4 py-4 text-center">StopDesk (Orig → Custom)</th>
                    <th class="px-4 py-4 text-center">Domicile (Orig → Custom)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($feesData as $wid => $data)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $data['name'] }}</td>
                        <td class="px-4 py-4">
                            <select wire:model="feesData.{{ $wid }}.app_id" class="text-[11px] font-bold w-full rounded-lg border-slate-200 focus:ring-indigo-500">
                                <option value="">Select App</option>
                                @foreach($installedApps as $app)
                                    <option value="{{ $app->app_id }}">{{ $app->supportedApp->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-[9px] font-bold text-slate-300">{{ $data['o_s_p'] }}</span>
                                <input type="number" wire:model="feesData.{{ $wid }}.c_s_p" class="w-16 text-xs text-center border-slate-200 rounded-lg font-bold text-indigo-600">
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-[9px] font-bold text-slate-300">{{ $data['o_d_p'] }}</span>
                                <input type="number" wire:model="feesData.{{ $wid }}.c_d_p" class="w-16 text-xs text-center border-slate-200 rounded-lg font-bold text-orange-600">
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>