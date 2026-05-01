@props(['photo'])

<div class="relative group mb-4 break-inside-avoid rounded-2xl shadow-sm hover:shadow-pin-hover transition-shadow duration-300 bg-gray-100" 
     style="padding-bottom: {{ ($photo->height / $photo->width) * 100 }}%;"
>
    <!-- Image -->
    <a href="{{ route('photos.show', $photo->uid) }}" class="absolute inset-0 w-full h-full rounded-2xl overflow-hidden" style="background-color: {{ $photo->dominant_color ?? '#e0e0e0' }};" x-data="{ loaded: false }" >
    <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->title }}" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-[1.02] opacity-0" x-bind:class="{ 'opacity-100': loaded }" @load="loaded = true" />

    </a>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none flex flex-col justify-between p-3">
         
         <!-- Top: Save Button -->
         <div class="flex justify-end w-max self-end pointer-events-auto">
             @auth
             <div x-data="{ 
                 pinned: {{ ($photo->is_pinned ?? false) ? 'true' : 'false' }}, 
                 saving: false,
                 showBoards: false
             }" class="relative">
                 
                 <!-- Quick Save (if no board selected, just show button to open boards) -->
                 <button @click="showBoards = !showBoards" class="bg-pinterest hover:bg-pinterest-hover text-white font-bold px-4 py-2 rounded-full text-sm transition-transform active:scale-95 shadow-md">
                     Simpan
                 </button>

                 <!-- Boards Dropdown -->
                 <div x-show="showBoards" @click.away="showBoards = false" class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-xl overflow-hidden z-20" style="display: none;">
                     <div class="max-h-48 overflow-y-auto">
                         <template x-for="board in window.bloxpinBoards || []" :key="board.id">
                             <button @click="
                                 saving = true;
                                 axios.post('{{ route('pins.store') }}', { photo_id: {{ $photo->id }}, board_id: board.id })
                                      .then(res => {
                                          window.showToast(res.data.message);
                                          showBoards = false;
                                          pinned = true;
                                      })
                                      .catch(err => {
                                          window.showToast(err.response?.data?.message || 'Gagal menyimpan', 'error');
                                      })
                                      .finally(() => saving = false);
                             " class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm font-medium text-dark flex items-center justify-between">
                                 <span x-text="board.title" class="truncate"></span>
                             </button>
                         </template>
                         <div x-show="(window.bloxpinBoards || []).length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                             Belum ada board.
                         </div>
                     </div>
                 </div>
             </div>
             @else
             <a href="{{ route('login') }}" class="bg-pinterest text-white font-bold px-4 py-2 rounded-full text-sm shadow-md pointer-events-auto">
                 Simpan
             </a>
             @endauth
         </div>

         <!-- Bottom: Title and Owner -->
         <div class="flex items-end justify-between pointer-events-auto">
             <div class="flex flex-col gap-1 max-w-[70%]">
                 <a href="{{ route('photos.show', $photo) }}" class="text-white font-bold truncate text-sm hover:underline drop-shadow-md">
                     {{ $photo->title }}
                 </a>
                 <a href="{{ route('profile.show', $photo->user) }}" class="flex items-center gap-1.5 text-white/80 hover:text-white transition-colors">
                     <span class="text-[10px] font-bold truncate">@ {{ $photo->user->username }}</span>
                     @if($photo->user->is_verified)
                         <x-verified-badge size="w-3.5 h-3.5" checkSize="w-2 h-2" />
                     @endif
                 </a>
             </div>
             
             <!-- More Options Button -->
             <div x-data="{ openOptions: false }" class="relative shrink-0">
                 <button @click="openOptions = !openOptions" class="w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center text-dark transition-colors backdrop-blur-sm">
                     <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                 </button>

                 <div x-show="openOptions" @click.away="openOptions = false" class="absolute bottom-full right-0 mb-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50 overflow-hidden" style="display: none;">
                     <button @click="
                         navigator.clipboard.writeText('{{ route('photos.show', $photo) }}');
                         window.showToast('Tautan disalin!');
                         openOptions = false;
                     " class="w-full text-left px-4 py-2.5 hover:bg-gray-100 flex items-center gap-2 text-dark text-sm transition-colors">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                         Salin Tautan
                     </button>
                     
                     <a href="{{ $photo->image_url }}" download="{{ $photo->title }}" class="w-full text-left px-4 py-2.5 hover:bg-gray-100 flex items-center gap-2 text-dark text-sm transition-colors">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                         Unduh
                     </a>
                 </div>
             </div>
         </div>
    </div>
</div>
