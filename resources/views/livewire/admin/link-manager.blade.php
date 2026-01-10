  <div>
      <div class="mb-8">
          <h1 class="text-2xl font-bold text-slate-900">Linking</h1>
          <p class="text-slate-500 mt-1 flex items-center gap-2">
              <span class="text-sm font-normal text-slate-400">You Can Link Client,Agent and Manager  Here</span>
          </p>
      </div>
      <div class="">



          <div class="mb-8">
              <div class="px-1 mb-4">
                  <div class="bg-gray-100 p-1 rounded-lg flex text-xs font-medium w-full md:w-auto">
                     

                      <button wire:click="setTab('stores')" class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                    {{ $currentTab === 'stores' 
                        ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                    }}">
                          <i class="ri-store-2-line text-sm"></i>
                          Stores
                      </button>

                      <button wire:click="setTab('agents')" class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                    {{ $currentTab === 'agents' 
                        ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                    }}">
                          <i class="ri-team-line text-sm"></i>
                          Agents
                      </button>

                  </div>
              </div>
          </div>

          <div wire:loading class="w-full py-10 flex justify-center opacity-50">
              <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-slate-800"></div>
          </div>

          <div wire:loading.remove>
              @if ($currentTab === 'stores')
              @livewire('admin.link.store-manager')
              @elseif ($currentTab === 'agents')
              @livewire('admin.link.agent-manager')
              @endif
          </div>

      </div>

  </div>