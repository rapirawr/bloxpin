<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God Mode — Bloxpin Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
        .accent-gradient { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 glass border-r border-slate-800 flex flex-col p-6 fixed h-full">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-10 h-10 accent-gradient rounded-xl flex items-center justify-center font-bold text-xl">B</div>
            <h1 class="text-xl font-bold tracking-tight">God Mode</h1>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-rose-500/10 text-rose-500 font-semibold' : 'text-slate-400 hover:bg-slate-800' }}">
                <span>📊</span> Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('admin.users') ? 'bg-rose-500/10 text-rose-500 font-semibold' : 'text-slate-400 hover:bg-slate-800' }}">
                <span>👥</span> Manage Users
            </a>
            <a href="{{ route('admin.photos') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('admin.photos') ? 'bg-rose-500/10 text-rose-500 font-semibold' : 'text-slate-400 hover:bg-slate-800' }}">
                <span>🖼️</span> Content Moderation
            </a>
        </nav>

        <div class="pt-6 border-t border-slate-800">
            <a href="{{ route('home') }}" class="flex items-center gap-3 p-3 text-slate-400 hover:text-white transition-colors">
                <span>🏠</span> Back to Site
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-10">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 rounded-2xl animate-pulse">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-500/20 border border-rose-500/50 text-rose-400 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
