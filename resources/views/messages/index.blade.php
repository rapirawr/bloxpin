@extends('layouts.app')

@section('title', 'Pesan - Bloxpin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-black text-dark dark:text-white uppercase tracking-tighter">Pesan</h1>
        <div class="w-10 h-10 rounded-full bg-pinterest text-white flex items-center justify-center shadow-lg shadow-pinterest/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        </div>
    </div>

    <div class="bg-white dark:bg-card rounded-[32px] overflow-hidden shadow-xl border border-borderlight dark:border-borderdark">
        @if($conversations->count() > 0)
            <div class="divide-y divide-borderlight dark:divide-borderdark">
                @foreach($conversations as $conversation)
                    @php $otherUser = $conversation->otherUser(); @endphp
                    <a href="{{ route('messages.show', $otherUser->username) }}" class="flex items-center gap-4 p-6 hover:bg-light dark:hover:bg-white/5 transition-all group">
                        <div class="relative">
                            <img src="{{ $otherUser->avatar_url }}" alt="{{ $otherUser->name }}" class="w-14 h-14 rounded-full object-cover ring-2 ring-transparent group-hover:ring-pinterest transition-all">
                            @if($otherUser->is_verified)
                                <div class="absolute -bottom-1 -right-1">
                                    <x-verified-badge size="w-5 h-5" checkSize="w-3 h-3" />
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-bold text-dark dark:text-white truncate">{{ $otherUser->name }}</h3>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $conversation->last_message_at?->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate font-medium">
                                @if($conversation->messages->first())
                                    {{ $conversation->messages->first()->sender_id === auth()->id() ? 'Anda: ' : '' }}
                                    {{ $conversation->messages->first()->body }}
                                @else
                                    Mulai obrolan baru...
                                @endif
                            </p>
                        </div>
                        <div class="w-2 h-2 rounded-full bg-pinterest opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="p-20 text-center">
                <div class="w-20 h-20 rounded-full bg-light dark:bg-borderdark flex items-center justify-center mx-auto mb-6 text-gray-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-dark dark:text-white mb-2">Belum ada pesan</h2>
                <p class="text-gray-500 text-sm max-w-xs mx-auto">Mulai ikuti orang lain dan kirim pesan untuk memulai obrolan.</p>
                <a href="{{ route('home') }}" class="inline-block mt-8 px-8 py-3 bg-dark dark:bg-white text-white dark:text-dark rounded-full font-bold text-sm hover:scale-105 active:scale-95 transition-all">Jelajahi Bloxpin</a>
            </div>
        @endif
    </div>
</div>
@endsection
