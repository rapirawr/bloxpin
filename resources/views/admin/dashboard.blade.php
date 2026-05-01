@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-4xl font-black tracking-tighter mb-2">OVERVIEW</h2>
    <p class="text-gray-500 text-sm font-medium uppercase tracking-widest">Platform Analytics</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <div class="bg-card p-6 rounded-2xl border border-border group hover:border-white transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Users</span>
        </div>
        <div class="text-3xl font-black">{{ number_format($stats['users_count']) }}</div>
    </div>

    <div class="bg-card p-6 rounded-2xl border border-border group hover:border-white transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Photos</span>
        </div>
        <div class="text-3xl font-black">{{ number_format($stats['photos_count']) }}</div>
    </div>

    <div class="bg-card p-6 rounded-2xl border border-border group hover:border-white transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Boards</span>
        </div>
        <div class="text-3xl font-black">{{ number_format($stats['boards_count']) }}</div>
    </div>

    <div class="bg-card p-6 rounded-2xl border border-border group hover:border-white transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Comments</span>
        </div>
        <div class="text-3xl font-black">{{ number_format($stats['comments_count']) }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Activity -->
    <div class="bg-card border border-border rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-border flex justify-between items-center">
            <h3 class="font-bold text-sm uppercase tracking-widest">Recent Users</h3>
            <a href="{{ route('admin.users') }}" class="text-[10px] font-black hover:underline">VIEW ALL</a>
        </div>
        <div class="divide-y divide-border">
            @foreach($stats['latest_users'] as $user)
            <div class="p-4 flex items-center justify-between hover:bg-white/5 transition-colors">
                <div class="flex items-center gap-4">
                    <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full border border-border grayscale hover:grayscale-0 transition-all">
                    <div>
                        <div class="font-bold text-sm">{{ $user->name }}</div>
                        <div class="text-[10px] text-gray-500">@ {{ $user->username }}</div>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-gray-600 uppercase">{{ $user->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Latest Photos -->
    <div class="bg-card border border-border rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-border flex justify-between items-center">
            <h3 class="font-bold text-sm uppercase tracking-widest">Latest Photos</h3>
            <a href="{{ route('admin.photos') }}" class="text-[10px] font-black hover:underline">MODERATE</a>
        </div>
        <div class="divide-y divide-border">
            @foreach($stats['latest_photos'] as $photo)
            <div class="p-4 flex items-center gap-4 hover:bg-white/5 transition-colors">
                <img src="{{ $photo->thumbnail_url }}" class="w-12 h-12 rounded-lg object-cover border border-border grayscale hover:grayscale-0 transition-all">
                <div class="flex-1">
                    <div class="font-bold text-sm truncate">{{ $photo->title }}</div>
                    <div class="text-[10px] text-gray-500 uppercase tracking-tighter">by {{ $photo->user->name }}</div>
                </div>
                <span class="text-[10px] font-bold text-gray-600 uppercase">{{ $photo->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
