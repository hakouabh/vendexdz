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
    @if($showDiscountModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="closeDiscountModal"></div>

                <div
                    class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ri-close-line text-red-600 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">@lang('discount is greater than the maximum')!</h3>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" wire:click="closeDiscountModal"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            @lang('Confirm')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>