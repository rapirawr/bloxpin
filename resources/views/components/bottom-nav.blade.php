<!-- Bottom Navigation Bar (Mobile Only) -->
<div class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-lg border-t border-gray-100 flex items-center justify-around py-3 pb-safe md:hidden z-40">
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('home') ? 'text-dark' : 'text-gray-400' }}">
        <svg class="w-6 h-6" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
    </a>
    
    <a href="{{ route('search') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('search') ? 'text-dark' : 'text-gray-400' }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
    </a>

    @auth
        <a href="{{ route('photos.create') }}" class="flex flex-col items-center justify-center">
            <div class="w-10 h-10 bg-dark rounded-full flex items-center justify-center text-white active:scale-95 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            </div>
        </a>

        <a href="{{ route('notifications.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('notifications.*') ? 'text-dark' : 'text-gray-400' }} relative">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('notifications.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-pinterest rounded-full ring-2 ring-white"></span>
            @endif
        </a>

        <a href="{{ route('messages.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('messages.*') ? 'text-dark' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('messages.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        </a>

        <!-- <a href="{{ route('profile.show', auth()->user()) }}" class="flex flex-col items-center">
            <div class="w-7 h-7 rounded-full overflow-hidden {{ request()->routeIs('profile.*') ? 'ring-2 ring-dark' : '' }}">
                <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="w-full h-full object-cover">
            </div>
        </a> -->
    @endauth
</div>

<style>
    .pb-safe { padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 20px); }
</style>
