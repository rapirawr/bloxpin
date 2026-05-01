@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-4xl font-black tracking-tighter mb-2">CONTENT MODERATION</h2>
    <p class="text-gray-500 text-sm font-medium uppercase tracking-widest">Global Photo Stream</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($photos as $photo)
    <div class="bg-card border border-border rounded-2xl overflow-hidden group hover:border-white transition-all duration-300">
        <div class="relative aspect-square grayscale group-hover:grayscale-0 transition-all duration-500">
            <img src="{{ $photo->thumbnail_url }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-6">
                <form action="{{ route('admin.photos.delete', $photo) }}" method="POST" onsubmit="return confirm('Delete this photo?')" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button class="w-full bg-white text-black py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:scale-105 transition-transform active:scale-95">
                        DELETE PHOTO
                    </button>
                </form>
            </div>
        </div>
        <div class="p-4 border-t border-border">
            <div class="font-bold text-xs truncate mb-1">{{ $photo->title }}</div>
            <div class="text-[9px] text-gray-500 font-black uppercase tracking-tighter">BY {{ $photo->user->name }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-10">
    {{ $photos->links() }}
</div>
@endsection
