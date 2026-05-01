@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 md:py-12">
    <div class="bg-cardlight dark:bg-card rounded-2xl shadow-minimal dark:shadow-minimal-dark border border-borderlight dark:border-borderdark overflow-hidden transition-colors">
        
        <div class="p-6 border-b border-borderlight dark:border-borderdark flex items-center justify-between">
            <h1 class="text-2xl md:text-3xl font-display font-bold text-dark dark:text-white">Edit Postingan</h1>
            <button onclick="history.back()" class="w-10 h-10 bg-light hover:bg-gray-200 dark:bg-dark dark:hover:bg-borderdark rounded-lg flex items-center justify-center transition-colors text-dark dark:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('photos.update', $photo) }}" method="POST" class="p-6 md:p-10 flex flex-col md:flex-row gap-10">
            @csrf
            @method('PUT')
            
            <!-- Left: Current Image (Non-editable) -->
            <div class="w-full md:w-1/2 shrink-0">
                <div style="aspect-ratio: 4/5;" class="w-full bg-light dark:bg-dark rounded-xl border border-borderlight dark:border-borderdark overflow-hidden relative group">
                    <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white font-bold text-sm bg-dark/60 px-4 py-2 rounded-full backdrop-blur-sm">Pratinjau</span>
                    </div>
                </div>
                <p class="text-center text-xs text-gray-500 mt-4 italic">Catatan: Visual asli tidak dapat diubah setelah diunggah.</p>
            </div>

            <!-- Right: Details Form -->
            <div class="w-full md:w-1/2 flex flex-col gap-8">
                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary px-10 shadow-minimal dark:shadow-minimal-dark">Simpan Perubahan</button>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Judul</label>
                    <input type="text" name="title" placeholder="Beri judul karya Anda" value="{{ old('title', $photo->title) }}"
                           class="w-full border-none border-b-2 border-borderlight focus:border-dark dark:border-borderdark dark:focus:border-white focus:ring-0 px-0 py-3 text-3xl md:text-4xl font-display font-bold placeholder:text-gray-400 dark:placeholder:text-gray-600 bg-transparent text-dark dark:text-white transition-colors">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Profile Display -->
                <div class="flex items-center gap-4">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="w-10 h-10 rounded-full object-cover ring-1 ring-borderlight dark:ring-borderdark">
                    <div>
                        <div class="font-bold text-dark dark:text-white">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ '@' . auth()->user()->username }}</div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Visual</label>
                    <textarea name="description" rows="5" placeholder="Ceritakan detail tentang karya visual ini..."
                               class="w-full border border-borderlight dark:border-borderdark rounded-lg focus:border-dark dark:focus:border-white focus:ring-0 p-4 text-dark dark:text-white text-sm resize-none placeholder:text-gray-400 dark:placeholder:text-gray-600 bg-light dark:bg-dark transition-colors">{{ old('description', $photo->description) }}</textarea>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kategori / Tag</label>
                    <input type="text" name="tags" placeholder="Pisahkan dengan koma (contoh: roblox, obby, aesthetic)" value="{{ old('tags', $photo->tags->pluck('name')->implode(', ')) }}"
                           class="w-full border border-borderlight dark:border-borderdark rounded-lg focus:border-dark dark:focus:border-white focus:ring-0 p-4 text-dark dark:text-white text-sm placeholder:text-gray-400 dark:placeholder:text-gray-600 bg-light dark:bg-dark transition-colors">
                    <p class="text-[10px] text-gray-500 mt-2">Pisahkan beberapa tag dengan tanda koma.</p>
                </div>

                <!-- Danger Zone -->
                <div class="mt-8 pt-8 border-t border-borderlight dark:border-borderdark">
                    <h3 class="text-sm font-bold text-red-600 mb-4">Zona Bahaya</h3>
                    <div class="bg-red-50 dark:bg-red-900/10 rounded-xl p-4 flex items-center justify-between border border-red-100 dark:border-red-900/20">
                        <div>
                            <div class="text-sm font-bold text-red-800 dark:text-red-400">Hapus Postingan Ini</div>
                            <div class="text-xs text-red-600/70 dark:text-red-400/60">Tindakan ini tidak dapat dibatalkan.</div>
                        </div>
                        <form action="{{ route('photos.destroy', $photo) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini selamanya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition-colors">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
