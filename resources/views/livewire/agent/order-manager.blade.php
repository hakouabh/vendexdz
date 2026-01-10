  <div>
      <div class=" flex justify-between mb-8 items-center">
          <div>
              <h1 class="text-2xl font-bold text-slate-900">Orders </h1>
              <p class="text-slate-500 mt-1 flex items-center gap-2">
                  <span class="text-sm font-normal text-slate-400">Please Be carefull with this orders </span>
              </p>
          </div>
          <div>
              <button wire:click="$toggle('createNeworeder')"
                  class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-slate-200">
                  <i class="ri-add-circle-line text-[#10F0B2] text-lg"></i>
                  <span
                      class="text-[11px] font-bold text-slate-600">{{ $createNeworeder ? 'Cancel Creation' : 'Order Creator' }}</span>
              </button>
          </div>
      </div>
      <div>
          <div class="w-1/2 my-5">
              <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">
                  Shops You link with</label>
              <div class="flex rounded-lg bg-gray-100  p-1">
                  @foreach($this->stores as $store)
                  <button type="button" wire:key="store-{{ $store->id }}"
                      wire:click="$set('store_id', {{ $store->id }})"
                      class="flex-1 max-w-44 rounded py-1.5 text-[10px] font-bold {{ $store_id==$store->id?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}"><i
                          class="ri-store-2-line text-xs mx-2"></i>{{$store->name}}</button>
                  @endforeach
              </div>
          </div>
      </div>
      @if($createNeworeder)
      <div class="w-full space-y-6">
          <!-- Header -->
          <div class="flex items-center justify-between">
              <div>
                  <h1 class="text-2xl font-bold text-slate-900">Create New Order</h1>
                  <p class="text-slate-600 mt-1">Add a new customer order to the system</p>
              </div>

          </div>

          <form wire:submit.prevent="createOrder" class="space-y-6">


              <!-- Order Items -->
              <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
                  <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                      <div class="flex items-center gap-3">
                          <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                              <i class="ri-archive-line text-emerald-600"></i>
                          </div>
                          <h3 class="text-sm font-bold text-slate-800">Order Items</h3>
                      </div>
                      <button type="button" wire:click="addItem"
                          class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 hover:bg-emerald-100 transition-all">
                          <i class="ri-add-line text-sm"></i>
                          <span class="text-[11px] font-bold">Add Item</span>
                      </button>
                  </div>

                  <div class="p-6 space-y-4">
                      @foreach($items as $index => $item)
                      <div
                          class="group relative p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:border-emerald-200 transition-all duration-300">
                          <div class="flex items-center justify-between mb-3">
                              <h4 class="text-xs font-bold text-slate-700">Item {{ $index + 1 }}</h4>
                              @if(count($items) > 1)
                              <button type="button" wire:click="deleteItem({{ $index }})"
                                  class="h-6 w-6 flex items-center justify-center rounded-lg text-slate-300 hover:bg-rose-50 hover:text-rose-500 transition-all">
                                  <i class="ri-delete-bin-line text-sm"></i>
                              </button>
                              @endif
                          </div>

                          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                              <div>
                                  <label
                                      class="mb-1 block text-[10px] font-bold uppercase text-slate-400">Product</label>
                                  <select wire:model.live="items.{{ $index }}.sku"
                                      class="w-full rounded-xl border-none bg-white p-2 text-[11px] font-bold text-slate-700 focus:ring-1 focus:ring-emerald-500 outline-none transition-all">
                                      <option value="">-- Select Product --</option>
                                      @foreach($this->availableProducts as $prod)
                                      <option value="{{ $prod->sku }}">{{ $prod->name }}</option>
                                      @endforeach
                                  </select>
                              </div>

                              <div>
                                  <label
                                      class="mb-1 block text-[10px] font-bold uppercase text-slate-400">Variant</label>
                                  <select wire:model.live="items.{{ $index }}.vid"
                                      {{ empty($item['sku']) ? 'disabled' : '' }}
                                      class="w-full rounded-xl border-none bg-white p-2 text-[11px] font-bold {{ empty($item['sku']) ? 'text-slate-300' : 'text-slate-700' }} focus:ring-1 focus:ring-emerald-500 outline-none transition-all">
                                      <option value="">-- Select Variant --</option>
                                      @if(!empty($item['sku']))
                                      @foreach($this->getVariants($item['sku']) as $v)
                                      <option value="{{ $v->id }}">{{ $v->var_1 }} ({{ $v->var_2 }})</option>
                                      @endforeach
                                      @endif
                                  </select>
                              </div>

                              <div>
                                  <label
                                      class="mb-1 block text-[10px] font-bold uppercase text-slate-400">Quantity</label>
                                  <div class="flex items-center bg-slate-900 rounded-lg px-2 py-1">
                                      <span class="text-[9px] font-black text-slate-400 uppercase mr-1">Q:</span>
                                      <input type="number" wire:model.live="items.{{ $index }}.quantity"
                                          class="w-16 border-none bg-transparent text-center text-[11px] font-black text-white focus:ring-0 p-0"
                                          min="1">
                                  </div>
                              </div>


                          </div>

                          @if(!empty($item['sku']))
                          <div class="mt-2 flex items-center gap-2 px-2">
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
                  </div>
              </div>
              <!-- Customer Information -->
              <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
                  <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                      <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                          <i class="ri-user-line text-blue-600"></i>
                      </div>
                      <h3 class="text-sm font-bold text-slate-800">Customer Information</h3>
                  </div>

                  <div class="p-6 space-y-4">
                      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                          <div>
                              <label
                                  class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-slate-400">
                                  Full Name @error('client_name') <p class="text-[8px] text-red-600">{{ $message }}</p>
                                  @enderror
                              </label>
                              <div class="relative">
                                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                      <i class="ri-user-smile-line text-slate-400"></i>
                                  </div>
                                  <input type="text" wire:model="client_name"
                                      class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-3 text-xs font-bold text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                                      placeholder="Enter customer full name">
                              </div>
                          </div>

                          <div>
                              <label
                                  class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-slate-400">
                                  Primary Phone @error('phone1') <p class="text-[8px] text-red-600">{{ $message }}</p>
                                  @enderror
                              </label>
                              <div class="relative">
                                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                      <i class="ri-phone-line text-slate-400"></i>
                                  </div>
                                  <input type="text" wire:model.live="phone1"
                                      class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-3 text-xs font-bold text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                                      placeholder="Enter primary phone number">
                                  <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                      <button type="button"
                                          class="rounded bg-green-100 p-1 text-green-600 hover:bg-green-200">
                                          <i class="ri-phone-fill text-sm"></i>
                                      </button>
                                  </div>
                              </div>
                          </div>

                          <div>
                              <label class="mb-1 block text-[10px] font-bold uppercase text-slate-400">Secondary
                                  Phone</label>
                              <div class="relative">
                                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                      <i class="ri-phone-line text-slate-400"></i>
                                  </div>
                                  <input type="text" wire:model="phone2"
                                      class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-3 text-xs font-bold text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                                      placeholder="Enter secondary phone number">
                                  <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                      <button type="button"
                                          class="rounded bg-green-100 p-1 text-green-600 hover:bg-green-200">
                                          <i class="ri-phone-fill text-sm"></i>
                                      </button>
                                  </div>
                              </div>
                          </div>

                          <div>
                              <label
                                  class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-slate-400">
                                  Wilaya @error('wilaya') <p class="text-[8px] text-red-600">{{ $message }}</p>@enderror
                              </label>
                              <div class="relative">
                                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                      <i class="ri-map-pin-line text-slate-400"></i>
                                  </div>
                                  <select wire:model.live="wilaya"
                                      class="block w-full appearance-none rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-8 text-xs font-bold text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                                      <option value="">Select Wilaya</option>
                                      @foreach($willayas as $w)
                                      <option value="{{ $w->wid }}">{{ $w->wid }} {{ $w->name }}</option>
                                      @endforeach
                                  </select>
                                  <div
                                      class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                                      <i class="ri-arrow-down-s-fill"></i>
                                  </div>
                              </div>
                          </div>

                          <div>
                              <label
                                  class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-slate-400">
                                  City/Town @error('city') <p class="text-[8px] text-red-600">{{ $message }}</p>
                                  @enderror
                              </label>
                              <div class="relative">
                                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                      <i class="ri-map-pin-line text-slate-400"></i>
                                  </div>
                                  <select wire:model.live="city"
                                      class="block w-full appearance-none rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-8 text-xs font-bold text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                                      <option value="">Select City</option>
                                      @foreach($communes as $commune)
                                      <option value="{{ $commune['name'] }}">
                                          {{ $commune['name'] }}
                                      </option>
                                      @endforeach
                                  </select>
                                  <div
                                      class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                                      <i class="ri-arrow-down-s-fill"></i>
                                  </div>
                              </div>
                          </div>

                          <div>
                              <label
                                  class="flex mb-1 gap-1 items-center block text-[10px] font-bold uppercase text-slate-400">
                                  Address @error('address') <p class="text-[8px] text-red-600">{{ $message }}</p>
                                  @enderror
                              </label>
                              <div class="relative">
                                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                      <i class="ri-home-4-line text-slate-400"></i>
                                  </div>
                                  <input type="text" wire:model="address"
                                      class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-3 text-xs font-bold text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                                      placeholder="Enter delivery address">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- Order Configuration -->
              <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
                  <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                      <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                          <i class="ri-settings-3-line text-purple-600"></i>
                      </div>
                      <h3 class="text-sm font-bold text-slate-800">Order Configuration</h3>
                  </div>

                  <div class="p-6 space-y-4">
                      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                          <div>
                              <label class="mb-1 block text-[10px] font-bold uppercase text-slate-400">Delivery
                                  Type</label>
                              <div class="flex rounded-lg bg-slate-100 p-1">
                                  <button type="button" wire:click="$set('delivery_type', '1')"
                                      {{ !$can_use_stopdesk ? 'disabled' : '' }}
                                      class="flex-1 rounded py-1.5 text-[10px] font-bold {{ $delivery_type=='1'?'bg-white text-blue-600 shadow-sm':'text-slate-400' }}">
                                      <span>Stopdesk</span>
                                      @if(!$can_use_stopdesk)
                                      <span class="text-[8px] text-red-500">Not Available</span>
                                      @endif
                                  </button>
                                  <button type="button" wire:click="$set('delivery_type', '0')"
                                      class="flex-1 rounded py-1.5 text-[10px] font-bold {{ $delivery_type=='0'?'bg-white text-blue-600 shadow-sm':'text-slate-400' }}">Domicile</button>
                              </div>
                          </div>

                          <div>
                              <label class="mb-1 block text-[10px] font-bold uppercase text-slate-400">Discount</label>
                              <div class="relative">
                                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                      <i class="ri-price-tag-3-line text-slate-400"></i>
                                  </div>
                                  <input type="number" wire:model.live="discount" value="0"
                                      class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-3 text-xs font-bold text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                              </div>
                          </div>

                          <div>
                              <div class="sm:col-span-1">
                                  <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">Order
                                      Type</label>
                                  <div class="flex rounded-lg bg-gray-100 p-1">
                                      <button type="button" wire:click="$set('order_type', '1')"
                                          class="flex-1 rounded py-1.5 text-[10px] font-bold {{ $order_type=='1'?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}">Normal</button>
                                      <button type="button" wire:click="$set('order_type', '2')"
                                          class="flex-1 rounded py-1.5 text-[10px] font-bold {{ $order_type=='2'?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}">Quantity
                                          Break</button>
                                      <button type="button" wire:click="$set('order_type', '3')"
                                          class="flex-1 rounded py-1.5 text-[10px] font-bold {{ $order_type=='3'?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}">Up
                                          Sell</button>
                                      <button type="button" wire:click="$set('order_type', '4')"
                                          class="flex-1 rounded py-1.5 text-[10px] font-bold {{ $order_type=='4'?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}">Cross
                                          Sell</button>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div>
                          <label class="mb-1 block text-[10px] font-bold uppercase text-slate-400">Order Notes</label>
                          <div class="relative">
                              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                  <i class="ri-sticky-note-line text-slate-400"></i>
                              </div>
                              <textarea wire:model="comment"
                                  placeholder="Add any special instructions or notes for this order"
                                  class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-3 text-xs font-bold text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                                  rows="3"></textarea>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Price Summary -->
              <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
                  <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                      <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                          <i class="ri-calculator-line text-orange-600"></i>
                      </div>
                      <h3 class="text-sm font-bold text-slate-800">Price Summary</h3>
                  </div>

                  <div class="p-6">
                      <div class="space-y-2">
                          <div class="flex justify-between text-sm">
                              <span class="text-slate-600">Subtotal:</span>
                              <span class="font-bold text-slate-900">{{ number_format($price, 2) }} DZD</span>
                          </div>
                          <div class="flex justify-between text-sm">
                              <span class="text-slate-600">Delivery:</span>
                              <span class="font-bold text-slate-900">{{ number_format($delivery_price, 2) }} DZD</span>
                          </div>
                          <div class="flex justify-between text-sm">
                              <span class="text-red-600">Discount:</span>
                              <span class="font-bold text-red-600">-{{ number_format((float)$discount, 2) }} DZD</span>
                          </div>
                          <div class="border-t border-slate-200 pt-2 mt-2">
                              <div class="flex justify-between font-bold text-lg">
                                  <span class="text-slate-900">Total:</span>
                                  <span class="text-blue-600">{{ number_format($total, 2) }} DZD</span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end">
                  <button type="submit"
                      class="flex items-center gap-2 rounded-lg bg-blue-600 px-8 py-3 text-sm font-bold text-white shadow-md shadow-blue-100 transition-all hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                      :disabled="$isSubmitting">
                      <i class="ri-save-3-line"></i>

                  </button>
              </div>
          </form>

          <!-- Success Modal -->
          @if($showSuccessModal && $createdOrder)
          <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog"
              aria-modal="true">
              <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                      wire:click="closeSuccessModal"></div>

                  <div
                      class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                          <div class="sm:flex sm:items-start">
                              <div
                                  class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                  <i class="ri-check-line text-green-600 text-xl"></i>
                              </div>
                              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                  <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Order Created
                                      Successfully!</h3>
                                  <div class="mt-2">
                                      <p class="text-sm text-gray-500">The order has been created successfully with the
                                          following details:</p>
                                      <div class="mt-3 space-y-1 text-sm">
                                          <p><strong>Order ID:</strong> {{ $createdOrder->ref }}</p>
                                          <p><strong>Total:</strong> {{ number_format($total, 2) }} DZD</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                          <button type="button" wire:click="closeSuccessModal"
                              class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                              Close
                          </button>
                      </div>
                  </div>
              </div>
          </div>
          @endif

          <style>
          /* Custom scrollbar styles */
          .custom-scrollbar::-webkit-scrollbar {
              width: 4px;
              height: 5px;
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
      </div>
      @endif
      <div class="">
          <div class="mb-8">
              <div class="px-1 mb-4">
                  <div class="bg-gray-100 p-1 rounded-lg flex text-xs font-medium w-full md:w-auto">
                      <button wire:click="setTab('inconfirmation')" class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                    {{ $currentTab === 'inconfirmation' 
                        ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                    }}">
                          <i class="ri-check-line text-sm"></i>
                          In Confermation
                      </button>

                      <button wire:click="setTab('postponed')" class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                    {{ $currentTab === 'postponed' 
                        ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                    }}">
                          <i class="ri-timer-line text-sm"></i>
                          Postponed
                      </button>

                      <button wire:click="setTab('waiting')" class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                    {{ $currentTab === 'waiting' 
                        ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                    }}">
                          <i class="ri-hourglass-line text-sm"></i>
                          Waiting
                      </button>

                      <button wire:click="setTab('indelivery')" class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                    {{ $currentTab === 'indelivery' 
                        ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                    }}">
                          <i class="ri-truck-line text-sm"></i>
                          In Delivery
                      </button>

                  </div>
              </div>
          </div>

          <div wire:loading class="w-full py-10 flex justify-center opacity-50">
              <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-slate-800"></div>
          </div>
               
          <div wire:loading.remove>
            <div wire:key="tab-wrapper-{{ $currentTab }}-{{ $store_id }}">
              @if ($currentTab === 'inconfirmation')
              {{-- Adding the store_id to the key forces a refresh when the store changes --}}
              @livewire('agent.order.inconfermation-manager', ['storefilter' => $store_id], key('comp-inconf-' . $currentTab . $store_id))

              @elseif ($currentTab === 'postponed')
              @livewire('agent.order.reported-manager', ['storefilter' => $store_id], key('comp-postponed-' . $currentTab . $store_id))

              @elseif ($currentTab === 'waiting')
              @livewire('agent.order.pending-manager', ['storefilter' => $store_id], key('comp-waiting-' . $currentTab . $store_id))

              @elseif ($currentTab === 'indelivery')
              @livewire('agent.order.indelivery-manager', ['storefilter' => $store_id], key('comp-delivery-' . $currentTab . $store_id))
              @endif
              </div>
          </div>

      </div>


      <!-- Success Modal -->
      @if($showSuccessModal && $createdOrder)
      <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
          <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
              <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeSuccessModal">
              </div>

              <div
                  class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                  <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                      <div class="sm:flex sm:items-start">
                          <div
                              class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                              <i class="ri-check-line text-green-600 text-xl"></i>
                          </div>
                          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                              <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Order Created
                                  Successfully!</h3>
                              <div class="mt-2">
                                  <p class="text-sm text-gray-500">The order has been created successfully with the
                                      following details:</p>
                                  <div class="mt-3 space-y-1 text-sm">
                                      <p><strong>Order ID:</strong> {{ $createdOrder->id }}</p>
                                      <p><strong>Total:</strong> {{ number_format($temp_total, 2) }} DZD</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                      <button type="button" wire:click="closeSuccessModal"
                          class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                          Close
                      </button>
                  </div>
              </div>
          </div>
      </div>
      @endif

      <style>
      /* Custom scrollbar styles */
      .custom-scrollbar::-webkit-scrollbar {
          width: 4px;
          height: 5px;
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
  </div>
  </div>