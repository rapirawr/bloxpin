@extends('layouts.app')

@section('content')
<div class="w-full max-w-4xl mx-auto pt-8 px-4">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-display font-bold text-dark dark:text-white">Edit Board</h1>
        <button onclick="history.back()" class="text-sm font-bold text-gray-500 hover:text-dark dark:hover:text-white transition-colors">Batal</button>
    </div>

    <div class="bg-cardlight dark:bg-card rounded-3xl shadow-minimal dark:shadow-minimal-dark border border-borderlight dark:border-borderdark p-6 md:p-10 transition-colors" x-data="{ saving: false }">
        <form action="{{ route('boards.update', $board) }}" method="POST" @submit="saving = true">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-semibold text-dark dark:text-white mb-2">Nama Board</label>
                <input type="text" name="title" placeholder="Contoh: Tempat Belanja, Tempat Keren" required
                       value="{{ old('title', $board->title) }}"
                       class="w-full rounded-2xl border-gray-300 dark:border-borderdark focus:border-dark dark:focus:border-white focus:ring-dark/20 dark:focus:ring-white/20 py-3 px-4 transition-colors bg-white dark:bg-dark text-dark dark:text-white">
                @error('title')
                    <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-dark dark:text-white mb-2">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="description" rows="4" placeholder="Apa tujuan dari board ini?"
                          class="w-full rounded-2xl border-gray-300 dark:border-borderdark focus:border-dark dark:focus:border-white focus:ring-dark/20 dark:focus:ring-white/20 py-3 px-4 transition-colors bg-white dark:bg-dark text-dark dark:text-white resize-none">{{ old('description', $board->description) }}</textarea>
            </div>

            <div class="mb-8 flex items-center justify-between py-6 border-t border-borderlight dark:border-borderdark">
                <div>
                    <h3 class="font-semibold text-dark dark:text-white">Rahasiakan board</h3>
                    <p class="text-sm text-gray-500">Hanya Anda yang bisa melihat board ini.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_private" value="1" class="sr-only peer" {{ $board->is_private ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 dark:bg-dark peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-dark dark:peer-checked:bg-white"></div>
                </label>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-borderlight dark:border-borderdark">
                <div x-data="{ openDelete: false }" class="relative">
                    <button type="button" @click="openDelete = !openDelete" class="text-sm font-bold text-red-600 hover:underline">Hapus Board</button>
                    
                    <div x-show="openDelete" @click.away="openDelete = false" class="absolute bottom-full left-0 mb-4 w-64 bg-white dark:bg-card p-4 rounded-2xl shadow-xl border border-borderlight dark:border-borderdark z-50" style="display: none;">
                        <p class="text-sm text-dark dark:text-white mb-4">Apakah Anda yakin ingin menghapus board ini? Semua pin di dalamnya akan tetap ada di profil Anda.</p>
                        <form action="{{ route('boards.destroy', $board) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white font-bold py-2 rounded-xl text-sm hover:bg-red-700 transition-colors">Ya, Hapus Board</button>
                        </form>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary px-8 shadow-minimal dark:shadow-minimal-dark" :class="{ 'opacity-50 pointer-events-none': saving }">
                        <span x-show="!saving">Simpan</span>
                        <span x-show="saving" style="display: none;">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
