  <div>
      <div class="mb-8">
          <h1 class="text-2xl font-bold text-slate-900">Accounts </h1>
          <p class="text-slate-500 mt-1 flex items-center gap-2">
              <span class="text-sm font-normal text-slate-400">You Can Manager Accounts Here</span>
          </p>
      </div>
      <div class="">



          <div class="mb-8">
              <div class="px-1 mb-4">
                  <div class="bg-gray-100 p-1 rounded-lg flex text-xs font-medium w-full md:w-auto">
                     <button wire:click="setTab('pending')" class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                    {{ $currentTab === 'pending' 
                        ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                    }}">
                          <i class="ri-hourglass-line text-sm"></i>
                          Pending
                      </button>

                      <button wire:click="setTab('stores')" class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                    {{ $currentTab === 'stores' 
                        ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                    }}">
                          <i class="ri-store-2-line text-sm"></i>
                          Stores
                      </button>

                      <button wire:click="setTab('managers')" class="flex-1 py-1.5 rounded-md flex items-center justify-center gap-2 transition-all duration-200
                    {{ $currentTab === 'managers' 
                        ? 'bg-white shadow-sm text-slate-800 ring-1 ring-black/5' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' 
                    }}">
                          <i class="ri-user-settings-line text-sm"></i>
                          Managers
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
              @livewire('admin.users.store-manager')
              @elseif ($currentTab === 'managers')
              <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-r">
                  <p class="text-sm text-yellow-700">Managers have full access. Be careful.</p>
              </div>
              @livewire('admin.users.manager-manager')
              @elseif ($currentTab === 'agents')
              @livewire('admin.users.agent-manager')
              @elseif ($currentTab === 'pending')
              @livewire('admin.users.pending-manager')
              @endif
          </div>

      </div>

  </div>