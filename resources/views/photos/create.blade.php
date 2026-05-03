@extends('layouts.app')

@section('title', 'Upload'. ' — Bloxpin')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 md:py-12">
    <div class="bg-cardlight dark:bg-card rounded-2xl shadow-minimal dark:shadow-minimal-dark border border-borderlight dark:border-borderdark overflow-hidden transition-colors">
        
        <div class="p-6 border-b border-borderlight dark:border-borderdark flex items-center justify-between">
            <h1 class="text-2xl md:text-3xl font-display font-bold text-dark dark:text-white">Unggah Foto</h1>
            <button onclick="history.back()" class="w-10 h-10 bg-light hover:bg-gray-200 dark:bg-dark dark:hover:bg-borderdark rounded-lg flex items-center justify-center transition-colors text-dark dark:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-10 flex flex-col md:flex-row gap-10" x-data="uploadForm()">
            @csrf
            
            <!-- Left: Image Upload Area -->
            <div class="w-full md:w-1/2 shrink-0">
                <div class="space-y-4">
                    <label for="imageUpload" 
                           class="w-full bg-light dark:bg-dark rounded-xl border-2 border-dashed border-borderlight dark:border-borderdark flex flex-col items-center justify-center cursor-pointer hover:bg-gray-100 dark:hover:bg-borderdark hover:border-gray-400 dark:hover:border-gray-500 transition-all relative overflow-hidden group min-h-[300px]"
                           :class="{'border-dark dark:border-white': dragover, 'bg-gray-200 dark:bg-borderdark': dragover}"
                           @dragover.prevent="dragover = true"
                           @dragenter.prevent="dragover = true"
                           @dragleave.prevent="dragover = false"
                           @drop.prevent="handleDrop($event)">
                        
                        <!-- Single Image Preview -->
                        <div x-show="previews.length === 1" class="absolute inset-0 w-full h-full bg-black/5" style="display: none;">
                            <img :src="previews[0].url" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-dark/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-4">
                                <button type="button" @click.stop.prevent="removeImage(0)" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-full hover:bg-red-700 transition-colors shadow-lg">
                                    Ganti Gambar
                                </button>
                            </div>
                        </div>

                        <!-- Multi Image Grid Preview (Simple Summary) -->
                        <div x-show="previews.length > 1" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50 dark:bg-dark/50" style="display: none;">
                            <div class="flex -space-x-4 mb-4">
                                <template x-for="(p, i) in previews.slice(0, 3)">
                                    <div class="w-16 h-16 rounded-lg border-2 border-white dark:border-dark overflow-hidden shadow-lg transform" :style="`z-index: ${10-i};`" :class="i === 1 ? 'rotate-3' : (i === 2 ? '-rotate-6' : '-rotate-3')">
                                        <img :src="p.url" class="w-full h-full object-cover">
                                    </div>
                                </template>
                            </div>
                            <p class="text-sm font-bold text-dark dark:text-white" x-text="`${previews.length} foto dipilih`"></p>
                            <button type="button" @click.stop.prevent="triggerFileInput" class="mt-4 text-xs font-semibold text-gray-500 hover:text-dark dark:hover:text-white transition-colors">
                                Tambah atau Ganti
                            </button>
                        </div>

                        <!-- Placeholder -->
                        <div x-show="previews.length === 0" class="flex flex-col items-center justify-center text-center px-6 pointer-events-none">
                            <div class="w-12 h-12 bg-dark dark:bg-white text-white dark:text-dark rounded-lg flex items-center justify-center mb-6 shadow-minimal dark:shadow-minimal-dark group-hover:-translate-y-1 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-dark dark:text-white mb-2">Pilih file atau seret ke sini</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 max-w-[250px]">Dukung banyak foto sekaligus</p>
                        </div>
                        
                        <input type="file" id="imageUpload" name="image[]" class="hidden" accept="image/jpeg,image/png,image/webp,image/gif" multiple @change="handleFileSelect($event)">
                    </label>

                    <!-- File Size Indicator -->
                    <div x-show="previews.length > 0" class="mt-4 p-4 rounded-xl bg-light dark:bg-dark border border-borderlight dark:border-borderdark shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Upload Size</span>
                            <span class="text-xs font-black" :class="!isAdmin && totalSize > limit ? 'text-red-500 animate-pulse' : 'text-dark dark:text-white'" x-text="formatSize(totalSize)"></span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-200 dark:bg-borderdark rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-500" 
                                 :class="!isAdmin && totalSize > limit ? 'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]' : 'bg-dark dark:bg-white'"
                                 :style="`width: ${Math.min((totalSize / limit) * 100, 100)}%`"
                            ></div>
                        </div>
                        <p x-show="!isAdmin && totalSize > limit" class="mt-2 text-[9px] font-bold text-red-500 uppercase tracking-tight flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Vercel Limit Reached (4.5MB). Please upload fewer or smaller files.
                        </p>
                        <p x-show="isAdmin && totalSize > limit" class="mt-2 text-[9px] font-bold text-amber-500 uppercase tracking-tight flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Admin Mode: Bypassing size limit (Caution: Vercel may still reject).
                        </p>
                    </div>

                    <!-- Detailed Grid for Multiple Files -->
                    <div x-show="previews.length > 1" class="grid grid-cols-4 gap-2" style="display: none;">
                        <template x-for="(p, i) in previews">
                            <div class="relative aspect-square group rounded-lg overflow-hidden border border-borderlight dark:border-borderdark">
                                <img :src="p.url" class="w-full h-full object-cover">
                                <button type="button" @click="removeImage(i)" class="absolute top-1 right-1 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                @error('image')
                    <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                @enderror
                @error('image.*')
                    <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Right: Details Form -->
            <div class="w-full md:w-1/2 flex flex-col gap-8">
                <!-- Board Selection & Submit -->
                <div class="flex justify-between items-center bg-light dark:bg-dark border border-borderlight dark:border-borderdark p-2 rounded-xl relative transition-colors" x-data="{ selectedBoardName: 'Simpan ke Profil' }">
                    <select name="board_id" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="selectedBoardName = $event.target.options[$event.target.selectedIndex].text">
                        <option value="">Simpan ke Profil</option>
                        @foreach($boards as $board)
                            <option value="{{ $board->id }}">{{ $board->title }}</option>
                        @endforeach
                    </select>
                    <div class="flex-1 px-4 text-sm font-semibold text-dark dark:text-white truncate" x-text="selectedBoardName"></div>
                    <button type="submit" class="btn-primary ml-2 z-10 shrink-0 shadow-minimal dark:shadow-minimal-dark" 
                            :disabled="previews.length === 0 || (!isAdmin && totalSize > limit)" 
                            :class="{'opacity-50 cursor-not-allowed grayscale': previews.length === 0 || (!isAdmin && totalSize > limit)}">
                        <span x-text="previews.length > 1 ? 'Publish Semua' : 'Publish'">Publish</span>
                    </button>
                </div>

                <!-- Title -->
                <div>
                    <input type="text" name="title" placeholder="Beri judul karya Anda" value="{{ old('title') }}"
                           class="w-full border-none border-b-2 border-borderlight focus:border-dark dark:border-borderdark dark:focus:border-white focus:ring-0 px-0 py-3 text-3xl md:text-4xl font-display font-bold placeholder:text-gray-400 dark:placeholder:text-gray-600 bg-transparent text-dark dark:text-white transition-colors">
                    <p class="text-xs text-gray-400 mt-2" x-show="previews.length > 1">Jika kosong, nama file akan digunakan sebagai judul masing-masing foto.</p>
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
                    <textarea name="description" rows="3" placeholder="Ceritakan detail tentang karya visual ini..."
                               class="w-full border border-borderlight dark:border-borderdark rounded-lg focus:border-dark dark:focus:border-white focus:ring-0 p-4 text-dark dark:text-white text-sm resize-none placeholder:text-gray-400 dark:placeholder:text-gray-600 bg-light dark:bg-dark transition-colors">{{ old('description') }}</textarea>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kategori / Tag</label>
                    <input type="text" name="tags" placeholder="Pisahkan dengan koma (contoh: roblox, obby, aesthetic)" value="{{ old('tags') }}"
                           class="w-full border border-borderlight dark:border-borderdark rounded-lg focus:border-dark dark:focus:border-white focus:ring-0 p-4 text-dark dark:text-white text-sm placeholder:text-gray-400 dark:placeholder:text-gray-600 bg-light dark:bg-dark transition-colors">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function uploadForm() {
    return {
        dragover: false,
        previews: [], // Array of {file, url}
        totalSize: 0,
        isAdmin: {{ auth()->user()->is_admin ? 'true' : 'false' }},
        limit: 4.5 * 1024 * 1024,
        
        triggerFileInput() {
            document.getElementById('imageUpload').click();
        },

        handleDrop(event) {
            this.dragover = false;
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                this.addFiles(files);
            }
        },
        
        handleFileSelect(event) {
            const files = event.target.files;
            if (files.length > 0) {
                this.addFiles(files);
            }
        },

        addFiles(files) {
            Array.from(files).forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previews.push({
                            file: file,
                            url: e.target.result
                        });
                        this.calculateTotalSize();
                        this.syncInput();
                    };
                    reader.readAsDataURL(file);
                }
            });
        },
        
        calculateTotalSize() {
            this.totalSize = this.previews.reduce((acc, p) => acc + p.file.size, 0);
        },

        formatSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        removeImage(index) {
            this.previews.splice(index, 1);
            this.calculateTotalSize();
            this.syncInput();
        },

        syncInput() {
            const input = document.getElementById('imageUpload');
            const dataTransfer = new DataTransfer();
            this.previews.forEach(p => dataTransfer.items.add(p.file));
            input.files = dataTransfer.files;
        }
    }
}
</script>
@endpush
