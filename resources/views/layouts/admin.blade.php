<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God Mode — Bloxpin Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        pinterest: '#E60023',
                        dark: '#111111',
                        card: '#1d1d1d'
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #111111; color: #ffffff; }
        .glass-nav { background: rgba(17, 17, 17, 0.8); backdrop-filter: blur(20px); }
        .sidebar-link.active { background: #E60023; color: white; box-shadow: 0 10px 20px -5px rgba(230, 0, 35, 0.4); }
    </style>
</head>
<body class="min-h-screen flex antialiased">

    <!-- Sidebar -->
    <aside class="w-72 bg-dark border-r border-white/5 flex flex-col p-8 fixed h-full z-50">
        <div class="flex items-center gap-4 mb-12">
            <div class="w-12 h-12 bg-pinterest rounded-2xl flex items-center justify-center shadow-lg shadow-pinterest/20">
                <span class="text-white font-bold text-2xl">B</span>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight leading-none mb-1">God Mode</h1>
                <p class="text-[10px] text-pinterest font-bold uppercase tracking-[0.2em]">Bloxpin Core</p>
            </div>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-semibold">Overview</span>
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-link flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.users') ? 'active' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="font-semibold">Users List</span>
            </a>
            <a href="{{ route('admin.photos') }}" class="sidebar-link flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.photos') ? 'active' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="font-semibold">Moderation</span>
            </a>
            <a href="#" class="sidebar-link flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300 text-gray-500 hover:text-white hover:bg-white/5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span class="font-semibold">Announce</span>
            </a>
        </nav>

        <div class="mt-auto pt-8 border-t border-white/5">
            <a href="{{ route('home') }}" class="flex items-center gap-4 px-5 py-4 text-gray-500 hover:text-white transition-all group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="font-semibold">Back to Site</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-72 p-12 bg-dark">
        <!-- Status Messages -->
        @if(session('success'))
            <div class="fixed top-12 left-1/2 -translate-x-1/2 z-[100] animate-bounce">
                <div class="bg-white text-dark px-8 py-4 rounded-2xl font-bold shadow-2xl flex items-center gap-3">
                    <span class="text-pinterest text-xl">✨</span>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
