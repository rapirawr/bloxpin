<!-- Floating Bottom Navigation (Mobile Only) -->
<div x-data="{ openMenu: false }" class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[92%] max-w-[400px] z-40 md:hidden">
    <!-- Action Menu (Upload/Photobooth) -->
    <div x-show="openMenu" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="absolute bottom-20 left-1/2 -translate-x-1/2 w-48 bg-white/95 dark:bg-card/95 backdrop-blur-xl border border-white/20 dark:border-white/5 rounded-[24px] shadow-2xl p-2 z-50 ring-1 ring-black/5"
         style="display: none;"
         @click.away="openMenu = false">
        <div class="flex flex-col gap-1">
            <a href="{{ route('photos.create') }}" class="flex items-center gap-3 p-3 hover:bg-pinterest hover:text-white rounded-2xl transition-all group">
                <div class="w-10 h-10 bg-gray-100 dark:bg-white/10 text-dark dark:text-white group-hover:bg-white/20 group-hover:text-white rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider">Postingan</span>
            </a>
            <a href="{{ route('boards.create') }}" class="flex items-center gap-3 p-3 hover:bg-pinterest hover:text-white rounded-2xl transition-all group">
                <div class="w-10 h-10 bg-gray-100 dark:bg-white/10 text-dark dark:text-white group-hover:bg-white/20 group-hover:text-white rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider">Board</span>
            </a>
            <a href="{{ route('photos.photobooth') }}" class="flex items-center gap-3 p-3 hover:bg-pinterest hover:text-white rounded-2xl transition-all group">
                <div class="w-10 h-10 bg-gray-100 dark:bg-white/10 text-dark dark:text-white group-hover:bg-white/20 group-hover:text-white rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider">BStudio</span>
            </a>
        </div>
    </div>

    <!-- Main Nav Bar -->
    <div class="bg-white/80 dark:bg-card/80 backdrop-blur-2xl border border-white/20 dark:border-white/5 rounded-[32px] flex items-center justify-around py-2 px-2 shadow-[0_20px_50px_rgba(0,0,0,0.15)] ring-1 ring-black/5">
        <!-- Home -->
        <a href="{{ route('home') }}" class="relative p-3 flex flex-col items-center justify-center transition-all duration-300 {{ request()->routeIs('home') ? 'scale-110' : 'opacity-40' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
            @if(request()->routeIs('home')) <span class="absolute -bottom-1 w-1 h-1 bg-pinterest rounded-full"></span> @endif
        </a>
        
        <!-- Search -->
        <a href="{{ route('search') }}" class="relative p-3 flex flex-col items-center justify-center transition-all duration-300 {{ request()->routeIs('search') ? 'scale-110' : 'opacity-40' }}">
            <!-- Ikon Kompas -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.5 9.5L13 13l-3.5 1.5L11 11l3.5-1.5z" />
            </svg>

            @if(request()->routeIs('search')) 
                <span class="absolute -bottom-1 w-1 h-1 bg-pinterest rounded-full"></span> 
            @endif
        </a>

        @auth
            <!-- Create Toggle -->
            <button @click="openMenu = !openMenu" class="flex items-center justify-center mx-1 relative z-50">
                <div class="w-11 h-11 bg-pinterest shadow-lg shadow-pinterest/20 rounded-2xl flex items-center justify-center text-white transition-all duration-300"
                     :class="openMenu ? 'rotate-45 scale-90 bg-dark' : 'active:scale-90'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                </div>
            </button>

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}" class="relative p-3 flex flex-col items-center justify-center transition-all duration-300 {{ request()->routeIs('notifications.*') ? 'scale-110' : 'opacity-40' }}">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('notifications.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-2.5 right-2.5 w-2.5 h-2.5 bg-pinterest rounded-full ring-2 ring-white dark:ring-card"></span>
                @endif
                @if(request()->routeIs('notifications.*')) <span class="absolute -bottom-1 w-1 h-1 bg-pinterest rounded-full"></span> @endif
            </a>

            <!-- Inbox/Messages -->
            <a href="{{ route('messages.index') }}" class="relative p-3 flex flex-col items-center justify-center transition-all duration-300 {{ request()->routeIs('messages.*') ? 'scale-110' : 'opacity-40' }}">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('messages.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                @if(request()->routeIs('messages.*')) <span class="absolute -bottom-1 w-1 h-1 bg-pinterest rounded-full"></span> @endif
            </a>
        @endauth
    </div>
</div>

<style>
    .backdrop-blur-2xl { backdrop-filter: blur(40px); -webkit-backdrop-filter: blur(40px); }
</style>
