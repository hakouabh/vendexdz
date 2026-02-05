<div class="space-y-4">
    <h5 class="text-[11px] font-bold uppercase text-gray-400">@lang('Order Price')</h5>
    <div class="sm:col-span-1">
        <!-- // TODO calculated discount -->
        <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">@lang('Discount') <br>
        <span class="text-red-600">(@lang('You can discount up to') ) {{$totalDiscount}} DZD</span>
        </label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="ri-price-tag-3-line text-gray-400"></i>
            </div>
            <input type="text" value="0" wire:model.live="order_discount"
                class="block w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-xs font-bold text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
        </div>
    </div>
    <div class="rounded-lg border border-gray-200 bg-white p-3 text-xs">
        <div class="flex justify-between py-1 text-gray-500"><span>@lang('Subtotal')</span>
            <span>{{$order_price}}
                DA</span>
        </div>
        <div class="flex justify-between py-1 text-gray-500"><span>@lang('Delivery')</span>
            <span>+{{$order_delivery_price}}
                DA</span>
        </div>
        <div class="flex justify-between py-1 text-red-600 font-medium"><span>@lang('Discount')</span>
            <span>-{{$order_discount}} DA</span>
        </div>
        <div
            class="border-t border-gray-100 mt-2 pt-2 flex justify-between font-bold text-gray-900 text-sm">
            <span>@lang('Total')</span> <span>{{$order_total}} DA</span>
        </div>
    </div>
</div>