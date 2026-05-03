@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-4xl font-black tracking-tighter mb-2">CONTENT MODERATION</h2>
    <p class="text-gray-500 text-sm font-medium uppercase tracking-widest">Global Photo Stream</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
    @foreach($photos as $photo)
    <div class="bg-card border border-border rounded-xl overflow-hidden group hover:border-white transition-all duration-300">
        <div class="relative aspect-[3/4] transition-all duration-500">
            <img src="{{ $photo->thumbnail_url }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4">
                <form id="delete-photo-{{ $photo->id }}" action="{{ route('admin.photos.delete', $photo) }}" method="POST" class="inline" @submit.prevent="window.appConfirm('Hapus Foto', 'Yakin ingin menghapus foto ini?', () => $el.submit(), 'Hapus')">
                    @csrf
                    @method('DELETE')
                    <button class="w-full bg-white text-black py-2 rounded-lg text-[9px] font-black uppercase tracking-[0.15em] hover:scale-105 transition-transform active:scale-95">
                        DELETE
                    </button>
                </form>
            </div>
        </div>
        <div class="p-3 border-t border-border">
            <div class="font-bold text-[10px] truncate mb-0.5">{{ $photo->title }}</div>
            <div class="text-[8px] text-gray-500 font-black uppercase tracking-tighter truncate">BY {{ $photo->user->name }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-10">
    {{ $photos->links() }}
</div>
@endsection
