<header class="h-16 bg-white border-b border-[#e2e8f0] flex items-center justify-between px-6 sticky top-0 z-30">
    <!-- Left Side: Mobile Menu Button & App Title -->
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Toggle -->
        <button class="md:hidden p-2 -ml-2 text-[#64748b] hover:text-[#1e293b] hover:bg-[#f1f5f9] rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <h1 class="text-xl font-bold text-primary tracking-tight"></h1>
    </div>

    <!-- Right Side: Actions & Profile -->
    <div class="flex items-center gap-3 md:gap-5">
        <!-- Icons -->
        <div class="flex items-center gap-1 md:gap-2">
            <button class="p-2 text-primary hover:bg-primary/10 rounded-full transition-colors relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <!-- Notification Dot -->
                <span class="absolute top-1.5 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
            </button>
            <button class="p-2 text-primary hover:bg-primary/10 rounded-full transition-colors hidden sm:block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.312.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </button>
        </div>

        <div class="h-6 w-px bg-[#e2e8f0] hidden sm:block"></div>

        <!-- Profile Dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 text-left focus:outline-none hover:bg-slate-50 p-1 rounded-lg transition-colors">
                    <div class="hidden md:block">
                        <div class="text-sm font-semibold text-[#1e293b] leading-tight">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">{{ Auth::user()->role }}</div>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=f1f5f9&color=1e293b&bold=true" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-3 border-b border-slate-100 md:hidden">
                    <div class="text-sm font-semibold text-[#1e293b]">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500 uppercase">{{ Auth::user()->role }}</div>
                </div>
                
                <x-dropdown-link :href="route('profile.edit')" class="!py-2.5 !text-sm">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="!py-2.5 !text-sm !text-red-600 hover:!bg-red-50">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
