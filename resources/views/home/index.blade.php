@extends('layouts.app')

@section('content')
<div class="w-full px-2 sm:px-4 md:px-6 mx-auto pt-6" x-data="masonryGallery()">
    
    <!-- Masonry Grid -->
    <div id="masonry-grid" class="w-full -ml-2 sm:-ml-4">
        <!-- Grid Sizer -->
        <div class="w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/5 xl:w-[16.666%] h-0"></div>
        
        @forelse($photos as $photo)
            <div class="grid-item w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/5 xl:w-[16.666%] pl-2 sm:pl-4 mb-2 sm:mb-4">
                @include('components.photo-card', ['photo' => $photo])
            </div>
        @empty
            <div class="col-span-full py-20 flex flex-col items-center text-center px-4">
                <div class="w-24 h-24 mb-6 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-dark dark:text-white mb-2">Belum ada inspirasi di sini</h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mb-8">Jadilah yang pertama untuk membagikan referensi visual keren kamu ke komunitas Bloxpin!</p>
                <a href="{{ route('photos.create') }}" class="px-8 py-3 bg-dark dark:bg-white text-white dark:text-dark rounded-full font-bold transition-transform hover:scale-105 active:scale-95 shadow-xl shadow-dark/10">
                    Upload Foto Pertama
                </a>
            </div>
        @endforelse
    </div>

    <!-- Loading Indicator -->
    <div x-show="loading" class="w-full flex justify-center py-8">
        <div class="w-8 h-8 border-4 border-pinterest border-t-transparent rounded-full animate-spin"></div>
    </div>

    @if($photos->hasMorePages())
    <div x-intersect.margin.200px="loadMore()" class="h-10 w-full"></div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('masonryGallery', () => ({
        msnry: null,
        nextPageUrl: ('{{ $photos->nextPageUrl() }}' || '').replace(/^http:\/\//i, 'https://'),
        hasMore: {{ $photos->hasMorePages() ? 'true' : 'false' }},
        loading: false,

        init() {
            this.$nextTick(() => {
                const grid = document.querySelector('#masonry-grid');
                
                // Initialize Masonry
                this.msnry = new window.Masonry(grid, {
                    itemSelector: '.grid-item',
                    columnWidth: '.w-1\\/2', // Use grid sizer class
                    percentPosition: true,
                    transitionDuration: '0.2s'
                });

                // Wait for images to load before final layout
                window.imagesLoaded(grid).on('progress', () => {
                    this.msnry.layout();
                });
            });
        },

        loadMore() {
            if (!this.hasMore || this.loading) return;

            this.loading = true;

            axios.get(this.nextPageUrl)
                .then(response => {
                    const data = response.data;
                    
                    // Create temporary container to parse HTML
                    const temp = document.createElement('div');
                    temp.innerHTML = data.html;
                    
                    // Modify elements to be masonry items
                    const items = Array.from(temp.children).map(child => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'grid-item w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/5 xl:w-[16.666%] pl-2 sm:pl-4 mb-2 sm:mb-4 opacity-0';
                        wrapper.appendChild(child);
                        return wrapper;
                    });

                    // Append to grid
                    const grid = document.querySelector('#masonry-grid');
                    items.forEach(item => grid.appendChild(item));

                    // Add to Masonry
                    this.msnry.appended(items);
                    
                    // Layout after images load
                    window.imagesLoaded(grid).on('progress', () => {
                        items.forEach(item => item.classList.remove('opacity-0', 'animate-fade-in'));
                        items.forEach(item => item.classList.add('animate-fade-in'));
                        this.msnry.layout();
                    });

                    this.nextPageUrl = (data.next_page || '').replace(/^http:\/\//i, 'https://');
                    this.hasMore = data.has_more;
                })
                .catch(error => {
                    console.error('Error loading more photos:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }));
});
</script>
@endpush
