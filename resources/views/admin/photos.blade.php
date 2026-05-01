@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-3xl font-bold mb-2">Content Moderation</h2>
    <p class="text-slate-400">Kelola semua foto yang ada di Bloxpin.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($photos as $photo)
    <div class="glass rounded-3xl overflow-hidden group border border-transparent hover:border-rose-500/50 transition-all duration-300">
        <div class="relative aspect-square">
            <img src="{{ $photo->thumbnail_url }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                <form action="{{ route('admin.photos.delete', $photo) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button class="w-full bg-rose-500 hover:bg-rose-600 text-white py-2 rounded-xl text-sm font-bold transition-colors">
                        Hapus Foto
                    </button>
                </form>
            </div>
        </div>
        <div class="p-4">
            <div class="font-semibold text-sm line-clamp-1 mb-1">{{ $photo->title }}</div>
            <div class="text-xs text-slate-500">by {{ $photo->user->name }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-10">
    {{ $photos->links() }}
</div>
@endsection
