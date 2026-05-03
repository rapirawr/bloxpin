<nav class="fixed top-0 left-0 w-full h-[80px] glass-nav z-50 flex items-center px-6 md:px-12 justify-between gap-8 transition-all duration-500">
    
    <!-- Brand Section -->
    <div class="flex items-center gap-8 shrink-0">
        <a href="{{ route('home') }}" class="group transition-transform duration-500 hover:scale-105">
            <x-logo class="h-8 w-auto filter dark:invert" />
        </a>
        
        <div class="hidden lg:flex items-center gap-1">
            <a href="{{ route('search') }}" class="nav-link" title="Explore">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zM12 13l3-4-4 3-3 4 4-3 3-4z" />
                </svg>
            </a>
        </div>
    </div>

    <div class="flex-1 max-w-3xl relative group" x-data="{ query: '{{ request('q') }}', focused: false }">
        <form action="{{ route('search') }}" method="GET" class="relative">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-dark dark:group-focus-within:text-white transition-all duration-300 transform group-focus-within:scale-110 z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input 
                type="text" 
                name="q" 
                x-model="query"
                @focus="focused = true"
                @blur="setTimeout(() => focused = false, 200)"
                placeholder="Find moments on bloxpin" 
                :class="focused ? 'bg-gray-100 dark:bg-white/10 rounded-2xl pl-10' : 'bg-transparent border-b border-gray-200 dark:border-white/10 pl-8'"
                class="w-full py-2.5 pr-4 focus:ring-0 transition-all duration-500 font-medium placeholder:text-gray-400 text-sm text-dark dark:text-white outline-none"
                autocomplete="off"
            >
        </form>
    </div>

    <!-- Actions Section -->
    <div class="flex items-center gap-4 shrink-0">
        @auth
            <!-- Premium Create Button -->
            <div class="relative hidden md:flex" x-data="{ openUpload: false }">
                <button @click="openUpload = !openUpload" @click.away="openUpload = false" 
                    class="group relative flex items-center gap-2 px-5 py-2.5 bg-dark dark:bg-white text-white dark:text-dark rounded-full font-semibold text-sm transition-all duration-500 hover:shadow-2xl hover:shadow-dark/20 dark:hover:shadow-white/20 active:scale-95 overflow-hidden">
                    <span class="relative z-10">Create</span>
                    <svg class="w-4 h-4 relative z-10 transition-transform duration-500 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                </button>
                
                <div x-show="openUpload" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute right-0 top-full mt-4 w-48 bg-white dark:bg-card border border-gray-100 dark:border-white/5 rounded-2xl shadow-2xl p-2 z-50">
                    <a href="{{ route('photos.create') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hover:text-dark dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition-all font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Postingan
                    </a>
                    <a href="{{ route('boards.create') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hover:text-dark dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition-all font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Board
                    </a>
                    <a href="{{ route('photos.photobooth') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hover:text-dark dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition-all font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        BStudio
                    </a>
                </div>
            </div>

            <!-- Messages -->
            <a href="{{ route('messages.index') }}" class="relative w-10 h-10 hidden md:flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-white/5 transition-all duration-300 text-gray-500 group {{ request()->routeIs('messages.*') ? 'text-dark dark:text-white bg-gray-100 dark:bg-white/5' : '' }}">
                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </a>

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}" class="relative w-10 h-10 hidden md:flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-white/5 transition-all duration-300 text-gray-500 group {{ request()->routeIs('notifications.*') ? 'text-dark dark:text-white bg-gray-100 dark:bg-white/5' : '' }}">
                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-dark dark:bg-white rounded-full ring-2 ring-white dark:ring-dark animate-pulse"></span>
                @endif
            </a>

            <!-- Profile -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="relative p-0.5 rounded-full ring-2 ring-gray-100 dark:ring-white/10 hover:ring-dark dark:hover:ring-white transition-all duration-500 overflow-hidden group">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="w-9 h-9 rounded-full object-cover group-hover:scale-110 transition-transform duration-500">
                </button>
                
                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                    class="absolute right-0 mt-4 w-72 bg-white/90 dark:bg-card/90 backdrop-blur-2xl border border-white/40 dark:border-white/10 rounded-[28px] shadow-[0_20px_60px_rgba(0,0,0,0.15)] overflow-hidden z-50 ring-1 ring-black/5"
                    style="display: none;">
                    
                    <!-- Profile Header -->
                    <div class="p-5 bg-gradient-to-b from-gray-50/50 to-transparent dark:from-white/5 dark:to-transparent border-b border-gray-100/50 dark:border-white/5 flex items-center gap-4 relative z-10">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="w-12 h-12 rounded-full object-cover ring-2 ring-white shadow-md">
                        <div class="overflow-hidden">
                            <div class="font-black text-dark dark:text-white text-base truncate leading-tight">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 truncate uppercase tracking-widest mt-0.5">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="p-3 flex flex-col gap-1 relative z-10">
                        <a href="{{ route('profile.show', auth()->user()) }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 hover:text-dark dark:hover:text-white hover:bg-gray-100/80 dark:hover:bg-white/10 rounded-2xl transition-all">
                            <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center group-hover:scale-110 group-hover:bg-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform">Profil Saya</span>
                        </a>
                        
                        <a href="{{ route('photos.photobooth') }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 hover:text-dark dark:hover:text-white hover:bg-gray-100/80 dark:hover:bg-white/10 rounded-2xl transition-all">
                            <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center group-hover:scale-110 group-hover:bg-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform">Photobooth Studio</span>
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 hover:text-dark dark:hover:text-white hover:bg-gray-100/80 dark:hover:bg-white/10 rounded-2xl transition-all">
                            <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center group-hover:scale-110 group-hover:bg-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform">Pengaturan</span>
                        </a>

                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-2xl transition-all">
                            <div class="w-8 h-8 rounded-xl bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19h14v2H5v-2z"></path>
                                </svg>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform">God Mode</span>
                        </a>
                        @endif
                        
                        <div class="h-px bg-gray-100 dark:bg-white/5 my-1 mx-2"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="group flex w-full items-center gap-3 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-2xl transition-all">
                                <div class="w-8 h-8 rounded-xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center group-hover:scale-110 group-hover:bg-white transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </div>
                                <span class="group-hover:translate-x-1 transition-transform">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
        @guest
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm font-bold tracking-tight hover:text-gray-600 dark:hover:text-gray-300 transition-colors uppercase">Login</a>
                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-dark dark:bg-white text-white dark:text-dark rounded-full font-bold text-sm transition-all hover:scale-105 active:scale-95 shadow-lg shadow-dark/10">Sign Up</a>
            </div>
        @endguest
    </div>
</nav>
