@extends('layouts.admin')

@section('content')
<div class="mb-12">
    <h2 class="text-5xl font-bold tracking-tight mb-4 text-white">System <span class="text-pinterest">Pulse</span></h2>
    <p class="text-gray-500 text-lg">Statistik real-time dari seluruh ekosistem Bloxpin.</p>
</div>

<!-- High Impact Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
    <div class="bg-card p-8 rounded-[2rem] border border-white/5 hover:border-pinterest/30 transition-all duration-500 group">
        <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-pinterest/10 transition-colors">
            <span class="text-2xl">👥</span>
        </div>
        <div class="text-gray-400 font-medium mb-1 uppercase tracking-widest text-[10px]">Total Citizens</div>
        <div class="text-5xl font-bold text-white">{{ number_format($stats['users_count']) }}</div>
    </div>

    <div class="bg-card p-8 rounded-[2rem] border border-white/5 hover:border-pinterest/30 transition-all duration-500 group">
        <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-pinterest/10 transition-colors">
            <span class="text-2xl">📸</span>
        </div>
        <div class="text-gray-400 font-medium mb-1 uppercase tracking-widest text-[10px]">Artifacts Stored</div>
        <div class="text-5xl font-bold text-white">{{ number_format($stats['photos_count']) }}</div>
    </div>

    <div class="bg-card p-8 rounded-[2rem] border border-white/5 hover:border-pinterest/30 transition-all duration-500 group">
        <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-pinterest/10 transition-colors">
            <span class="text-2xl">📂</span>
        </div>
        <div class="text-gray-400 font-medium mb-1 uppercase tracking-widest text-[10px]">Active Boards</div>
        <div class="text-5xl font-bold text-white">{{ number_format($stats['boards_count']) }}</div>
    </div>

    <div class="bg-card p-8 rounded-[2rem] border border-white/5 hover:border-pinterest/30 transition-all duration-500 group">
        <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-pinterest/10 transition-colors">
            <span class="text-2xl">💬</span>
        </div>
        <div class="text-gray-400 font-medium mb-1 uppercase tracking-widest text-[10px]">Total Dialogues</div>
        <div class="text-5xl font-bold text-white">{{ number_format($stats['comments_count']) }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
    <!-- Latest Users -->
    <div class="bg-card p-10 rounded-[2.5rem] border border-white/5">
        <div class="flex justify-between items-center mb-10">
            <h3 class="text-2xl font-bold text-white">New Citizens</h3>
            <a href="{{ route('admin.users') }}" class="text-pinterest font-bold text-sm hover:underline italic">See All →</a>
        </div>
        <div class="space-y-6">
            @foreach($stats['latest_users'] as $user)
            <div class="flex items-center justify-between p-4 rounded-3xl hover:bg-white/5 transition-all group">
                <div class="flex items-center gap-5">
                    <img src="{{ $user->avatar_url }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-white/5 group-hover:ring-pinterest transition-all">
                    <div>
                        <div class="font-bold text-white text-lg">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500">@ {{ $user->username }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-600 font-bold uppercase tracking-tighter">{{ $user->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Latest Photos -->
    <div class="bg-card p-10 rounded-[2.5rem] border border-white/5">
        <div class="flex justify-between items-center mb-10">
            <h3 class="text-2xl font-bold text-white">Latest Artifacts</h3>
            <a href="{{ route('admin.photos') }}" class="text-pinterest font-bold text-sm hover:underline italic">Moderate →</a>
        </div>
        <div class="space-y-6">
            @foreach($stats['latest_photos'] as $photo)
            <div class="flex items-center gap-6 p-4 rounded-3xl hover:bg-white/5 transition-all group">
                <img src="{{ $photo->thumbnail_url }}" class="w-20 h-20 rounded-2xl object-cover ring-2 ring-white/5 group-hover:ring-pinterest transition-all">
                <div class="flex-1">
                    <div class="font-bold text-white text-lg line-clamp-1 mb-1">{{ $photo->title }}</div>
                    <div class="text-sm text-gray-500 font-medium italic">by <span class="text-gray-300 font-bold not-italic">{{ $photo->user->name }}</span></div>
                </div>
                <div class="text-xs text-gray-600 font-bold uppercase">{{ $photo->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
