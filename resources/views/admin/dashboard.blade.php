@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-3xl font-bold mb-2">Overview Dashboard</h2>
    <p class="text-slate-400">Selamat datang di pusat kendali Bloxpin.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <div class="glass p-6 rounded-3xl">
        <div class="text-slate-400 mb-2">Total Users</div>
        <div class="text-4xl font-bold text-white">{{ $stats['users_count'] }}</div>
    </div>
    <div class="glass p-6 rounded-3xl">
        <div class="text-slate-400 mb-2">Total Photos</div>
        <div class="text-4xl font-bold text-rose-500">{{ $stats['photos_count'] }}</div>
    </div>
    <div class="glass p-6 rounded-3xl">
        <div class="text-slate-400 mb-2">Total Boards</div>
        <div class="text-4xl font-bold text-emerald-500">{{ $stats['boards_count'] }}</div>
    </div>
    <div class="glass p-6 rounded-3xl">
        <div class="text-slate-400 mb-2">Comments</div>
        <div class="text-4xl font-bold text-sky-500">{{ $stats['comments_count'] }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Latest Users -->
    <div class="glass p-8 rounded-3xl">
        <h3 class="text-xl font-bold mb-6">User Terbaru</h3>
        <div class="space-y-4">
            @foreach($stats['latest_users'] as $user)
            <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-800 transition-colors">
                <div class="flex items-center gap-3">
                    <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-700">
                    <div>
                        <div class="font-semibold">{{ $user->name }}</div>
                        <div class="text-xs text-slate-500">@ {{ $user->username }}</div>
                    </div>
                </div>
                <span class="text-xs text-slate-500">{{ $user->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Latest Photos -->
    <div class="glass p-8 rounded-3xl">
        <h3 class="text-xl font-bold mb-6">Upload Terakhir</h3>
        <div class="space-y-4">
            @foreach($stats['latest_photos'] as $photo)
            <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-800 transition-colors">
                <img src="{{ $photo->thumbnail_url }}" class="w-16 h-16 rounded-xl object-cover border border-slate-700">
                <div class="flex-1">
                    <div class="font-semibold line-clamp-1">{{ $photo->title }}</div>
                    <div class="text-xs text-slate-500">by {{ $photo->user->name }}</div>
                </div>
                <span class="text-xs text-slate-500">{{ $photo->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
