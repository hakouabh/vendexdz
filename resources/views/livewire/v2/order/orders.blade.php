  <div>
      <div class=" flex justify-between mb-8 items-center">
          <div>
              <h1 class="text-2xl font-bold text-slate-900">@lang('Orders')</h1>
          </div>
          <div>
                <a href="{{ route($context.'.create-order') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-slate-200">
                    <i class="ri-add-circle-line text-[#10F0B2] text-lg"></i>
                    <span class="text-[11px] font-bold text-slate-600">@lang('Create Order')</span>
                </a>
          </div>
      </div>
      <div>
          @if($context == 'agent' || $context == 'manager')
          <div class="w-1/2 my-5">
              <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">
                  @lang('Shops You link with')</label>
              <div class="flex rounded-lg bg-gray-100  p-1">
                  @foreach($user->stores as $store)
                  <button type="button" wire:key="store-{{ $store->id }}"
                      wire:click="$set('store_id', {{ $store->id }})"
                      class="flex-1 max-w-44 rounded py-1.5 text-[10px] font-bold {{ $store_id==$store->id?'bg-white text-blue-600 shadow-sm':'text-gray-400' }}"><i
                          class="ri-store-2-line text-xs mx-2"></i>{{$store->name}}</button>
                  @endforeach
              </div>
          </div>
          @endif
      </div>
      <div class="">
          <div class="mb-8">
              <div class="px-1 mb-4">
                  <div class="bg-gray-100 p-1 rounded-lg grid grid-cols-2 md:flex text-xs font-medium w-full md:w-auto gap-1 md:gap-0">

                      <button wire:click="setTab('inconfirmation')"
                          class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                {{ $currentTab === 'inconfirmation' 
                    ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                    : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                }}">
                          <i class="ri-check-line text-sm"></i>
                          @lang('In Confermation')
                      </button>

                      <button wire:click="setTab('postponed')"
                          class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                {{ $currentTab === 'postponed' 
                    ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                    : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                }}">
                          <i class="ri-timer-line text-sm"></i>
                          @lang('Postponed')
                      </button>

                      <button wire:click="setTab('waiting')"
                          class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                {{ $currentTab === 'waiting' 
                    ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                    : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                }}">
                          <i class="ri-hourglass-line text-sm"></i>
                          @lang('Waiting')
                      </button>

                      <button wire:click="setTab('indelivery')"
                          class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                {{ $currentTab === 'indelivery' 
                    ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                    : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                }}">
                          <i class="ri-truck-line text-sm"></i>
                          @lang('In Delivery')
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
                  @livewire('v2.order.inconfermation', ['storefilter' => $store, 'context' => $context], key('comp-inconf-' . $currentTab . $store_id))
                  @elseif ($currentTab === 'postponed')
                  @livewire('v2.order.reported', ['storefilter' => $store, 'context' => $context], key('comp-postponed-' . $currentTab . $store_id))
                  @elseif ($currentTab === 'waiting')
                  @livewire('v2.order.pending', ['storefilter' => $store, 'context' => $context], key('comp-waiting-' . $currentTab . $store_id))
                  @elseif ($currentTab === 'indelivery')
                  @livewire('v2.order.indelivery', ['storefilter' => $store, 'context' => $context], key('comp-delivery-' . $currentTab . $store_id))
                  @endif
              </div>
          </div>
      </div>
  </div>
  </div>