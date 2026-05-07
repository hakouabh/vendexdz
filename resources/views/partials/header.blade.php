<header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 flex-shrink-0 z-10">
    <div class="relative w-96 group hidden md:block">
        <!-- <input type="text" placeholder="Search..." class="w-full pl-10 pr-12 py-2 bg-gray-50 border border-transparent focus:bg-white focus:border-indigo-200 focus:ring-2 focus:ring-indigo-50 rounded-lg outline-none text-sm transition text-slate-700 placeholder-gray-400"> -->
    </div>

    <button id="sidebar-toggle"
        class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition">
        <i class="ri-menu-line text-xl"></i>
    </button>

    <div class="flex items-center gap-5">
        <div class="flex items-center gap-4 text-gray-500">
            @impersonating
            <a href="{{ route('impersonate.leave') }}" class="text-xs font-medium text-indigo-500 hover:text-indigo-700 hover:underline transition"><i class="las la-sign-in-alt"></i>@lang('Leave Impersonation')</a>
            @endImpersonating
        </div>
        <div class="w-px h-6 bg-gray-200">
        </div>

        <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-sm group-hover:scale-105 transition-transform">
            @if(Auth::user()->profile_photo_path)
            <img src="/storage/{{ Auth::user()->profile_photo_path }}" alt="{{ Auth::user()->name }}" class="w-full h-full rounded-full object-cover">
            @else
            {{ Auth::user()->short_name }}
            @endif
        </div>
    </div>
</header>
<script>
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebar-toggle');

    document.addEventListener('click', function(event) {

        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggle = toggle.contains(event.target);

        if (!isClickInsideSidebar && !isClickOnToggle) {
            sidebar.classList.add('-translate-x-full');
        }

    });
    toggle.addEventListener('click', function() {
        sidebar.classList.toggle('-translate-x-full');
    });
</script>