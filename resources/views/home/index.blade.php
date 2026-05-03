@extends('layouts.app')

@section('content')
<div class="w-full px-2 sm:px-4 md:px-6 mx-auto pt-6" x-data="masonryGallery()">

    <!-- Masonry Grid -->
    <div id="masonry-grid" class="w-full -ml-2 sm:-ml-4">
        <!-- Grid Sizer (dipakai Masonry untuk ngitung lebar kolom) -->
        <div id="grid-sizer" class="w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/5 xl:w-[16.666%] h-0"></div>

        @forelse($photos as $photo)
            <div class="grid-item w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/5 xl:w-[16.666%] pl-2 sm:pl-4 mb-2 sm:mb-4">
                @include('components.photo-card', ['photo' => $photo])
            </div>
        @empty
            <div class="col-span-full py-20 flex flex-col items-center text-center px-4">
                <div class="w-24 h-24 mb-6 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-dark dark:text-white mb-2">Belum ada inspirasi di sini</h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mb-8">Jadilah yang pertama untuk membagikan referensi visual keren kamu ke komunitas Bloxpin!</p>
                <a href="{{ route('photos.create') }}"
                    class="px-8 py-3 bg-dark dark:bg-white text-white dark:text-dark rounded-full font-bold transition-transform hover:scale-105 active:scale-95 shadow-xl shadow-dark/10">
                    Upload Foto Pertama
                </a>
            </div>
        @endforelse
    </div>



    <!-- End of Feed -->
    <div x-show="!hasMore && !loading" x-cloak class="w-full">
        <div class="text-center text-gray-400 dark:text-gray-600 py-8 text-sm">
            Semua foto sudah ditampilkan.
        </div>

        <!-- Decorative Binary Footer -->
        <div class="relative w-full py-32 overflow-hidden flex flex-col items-center justify-center">
            <!-- Binary Background (Dense and Full) -->
            <div class="absolute inset-0 flex flex-col opacity-[0.04] dark:opacity-[0.06] pointer-events-none select-none font-mono text-[6px] md:text-[8px] overflow-hidden break-all leading-none">
                @for($i = 0; $i < 60; $i++)
                    <div class="whitespace-nowrap">
                        @php 
                            $binary = '';
                            for($j = 0; $j < 400; $j++) { $binary .= rand(0, 1); }
                        @endphp
                        {{ $binary }}
                    </div>
                @endfor
            </div>

            <div class="relative z-10 w-full flex flex-col items-center">
                <pre class="font-mono text-[1.4vw] md:text-[1.6vw] leading-[1.1] text-dark/20 dark:text-white/10 select-none pointer-events-none text-center">
██████╗ ██╗      ██████╗ ██╗  ██╗██████╗ ██╗███╗   ██╗
██╔══██╗██║     ██╔═══██╗╚██╗██╔╝██╔══██╗██║████╗  ██║
██████╔╝██║     ██║   ██║ ╚███╔╝ ██████╔╝██║██╔██╗ ██║
██╔══██╗██║     ██║   ██║ ██╔██╗ ██╔═══╝ ██║██║╚██╗██║
██████╔╝███████╗╚██████╔╝██╔╝ ██╗██║     ██║██║ ╚████║
╚═════╝ ╚══════╝ ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝╚═╝  ╚═══╝
                </pre>
                <div class="flex flex-wrap justify-center gap-4 md:gap-8 mt-8 font-mono text-[8px] md:text-xs text-gray-400 dark:text-gray-600 tracking-[0.3em] md:tracking-[0.5em] uppercase">
                    <span>01052026</span>
                    <span class="hidden md:inline">&bull;</span>
                    <span>Visual Discovery</span>
                    <span class="hidden md:inline">&bull;</span>
                    <span>01001011</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sentinel -->
    <div
        x-show="hasMore"
        x-intersect.margin.600px="loadMore()"
        class="h-10 w-full"
    ></div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/5.0.0/imagesloaded.pkgd.min.js"></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('masonryGallery', () => ({
        msnry: null,
        nextPageUrl: @json($photos->nextPageUrl()),
        hasMore: {{ $photos->hasMorePages() ? 'true' : 'false' }},
        loading: false,

        init() {
            this.$nextTick(() => {
                const grid = document.querySelector('#masonry-grid');

                this.msnry = new window.Masonry(grid, {
                    itemSelector: '.grid-item',
                    columnWidth: '#grid-sizer',
                    percentPosition: true,
                    transitionDuration: '0.2s',
                });

                this.msnry.layout();
            });
        },

        loadMore() {
            if (!this.hasMore || this.loading || !this.nextPageUrl) return;

            this.loading = true;

            let url = this.nextPageUrl;
            if (window.location.protocol === 'https:') {
                url = url.replace(/^http:\/\//i, 'https://');
            }

            // FIX: header X-Requested-With wajib ada agar $request->ajax() return true di Laravel
            window.axios.get(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                }
            })
                .then(response => {
                    const data = response.data;

                    if (!data.html || data.html.trim() === '') {
                        this.hasMore = false;
                        return;
                    }

                    const temp = document.createElement('div');
                    temp.innerHTML = data.html;

                    const newItems = Array.from(temp.children).map(child => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'grid-item w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/5 xl:w-[16.666%] pl-2 sm:pl-4 mb-2 sm:mb-4';
                        wrapper.appendChild(child);
                        return wrapper;
                    });

                    const grid = document.querySelector('#masonry-grid');
                    newItems.forEach(item => grid.appendChild(item));

                    this.msnry.appended(newItems);

                    requestAnimationFrame(() => {
                        this.msnry.layout();
                    });

                    if (data.next_page) {
                        let nextUrl = data.next_page;
                        if (window.location.protocol === 'https:') {
                            nextUrl = nextUrl.replace(/^http:\/\//i, 'https://');
                        }
                        this.nextPageUrl = nextUrl;
                    } else {
                        this.nextPageUrl = null;
                    }
                    this.hasMore = !!data.has_more;
                })
                .catch(error => {
                    console.error('Infinite scroll error:', error);
                    this.hasMore = false;
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }));
});
</script>
@endpush