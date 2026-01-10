
<header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 flex-shrink-0 z-10">
    <div class="relative w-96 group hidden md:block">
        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500"></i>
        <input type="text" placeholder="Search..." class="w-full pl-10 pr-12 py-2 bg-gray-50 border border-transparent focus:bg-white focus:border-indigo-200 focus:ring-2 focus:ring-indigo-50 rounded-lg outline-none text-sm transition text-slate-700 placeholder-gray-400">
        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 border border-gray-200 px-1.5 py-0.5 rounded bg-white"><i class="ri-search-line"></i></div>
    </div>

    <div class="md:hidden text-gray-500 text-2xl">
        <i class="ri-menu-line"></i>
    </div>

    <div class="flex items-center gap-5">
        
        
        <div class="flex items-center gap-4 text-gray-500">
               
            <div class="relative cursor-pointer hover:text-slate-700 transition">
                <i class="ri-notification-3-line text-xl"></i>
                <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">6</span>
            </div>

            
        </div>

         <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-sm group-hover:scale-105 transition-transform">
        @if(Auth::user()->profile_photo_path)
            <img src="/storage/{{ Auth::user()->profile_photo_path }}" alt="{{ Auth::user()->name }}" class="w-full h-full rounded-full object-cover">
        @else
            {{ collect(explode(' ', Auth::user()->name))->map(fn($n) => mb_substr($n, 0, 1))->join('') }}
        @endif
       </div>
    </div>
</header>