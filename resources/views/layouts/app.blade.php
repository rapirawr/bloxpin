<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="view-transition" content="same-origin">
    @auth
        <meta name="user-id" content="{{ auth()->id() }}">
        <script>
            window.bloxpinBoards = {!! auth()->user()->boards->toJson() !!};
        </script>
    @endauth

    <title>@yield('title', config('app.name', 'Bloxpin'))</title>
    <meta name="description" content="@yield('meta_description', 'Discover and share your moments on Bloxpin.')">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <!-- PWA Setup -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#E60023">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Bloxpin">
    <meta property="og:title" content="@yield('title', config('app.name', 'Bloxpin'))">
    <meta property="og:description" content="@yield('meta_description', 'Discover and share your moments on Bloxpin.')">
    <meta property="og:image" content="@yield('meta_image', asset('og-default.png'))">
    <meta property="og:image:secure_url" content="@yield('meta_image', asset('og-default.png'))">
    <meta property="og:image:width" content="@yield('meta_image_width', '1200')">
    <meta property="og:image:height" content="@yield('meta_image_height', '630')">
    <meta property="og:image:type" content="image/jpeg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name', 'Bloxpin'))">
    <meta property="twitter:description" content="@yield('meta_description', 'Discover and share your moments on Bloxpin.')">
    <meta property="twitter:image" content="@yield('meta_image', asset('og-default.png'))">

    <!-- WhatsApp Extra -->
    <meta itemprop="name" content="@yield('title', config('app.name', 'Bloxpin'))">
    <meta itemprop="description" content="@yield('meta_description', 'Discover and share your moments on Bloxpin.')">
    <meta itemprop="image" content="@yield('meta_image', asset('og-default.png'))">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        // Suppress View Transition AbortErrors (harmless)
        window.addEventListener('unhandledrejection', (event) => {
            if (event.reason && event.reason.name === 'AbortError' && event.reason.message.includes('transition')) {
                event.preventDefault();
            }
        });
    </script>

    @stack('head')
</head>
<body class="font-sans antialiased bg-light dark:bg-dark text-dark dark:text-gray-100 pb-24 md:pb-0 pt-[80px] min-h-screen flex flex-col">
    
    <!-- Top Navbar -->
    @include('components.navbar')

    <!-- Global Announcement Banner (Disabled due to production stability issues) -->


    <!-- Main Content -->
    <main class="w-full flex-1">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Bottom Mobile Nav -->
    @if(!request()->routeIs('photos.photobooth'))
        @include('components.bottom-nav')
    @endif

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed bottom-20 md:bottom-6 left-1/2 transform -translate-x-1/2 z-50 flex flex-col gap-2 pointer-events-none">
    </div>

    <!-- Global Modals Component -->
    @include('components.modals')

    @stack('scripts')
    
    <script>
        // Global Modal Logic
        document.addEventListener('alpine:init', () => {
            Alpine.data('appModals', () => ({
                confirmData: { show: false, title: '', message: '', confirmText: '', onConfirm: null },
                promptData: { show: false, title: '', message: '', input: '', confirmText: '', placeholder: '', onConfirm: null },

                init() {
                    window.appConfirm = (title, message, onConfirm, confirmText = 'Ya, Lanjutkan') => {
                        this.confirmData = { show: true, title, message, onConfirm, confirmText };
                    };
                    window.appPrompt = (title, message, onConfirm, defaultValue = '', placeholder = '', confirmText = 'Simpan') => {
                        this.promptData = { show: true, title, message, input: defaultValue, onConfirm, confirmText, placeholder };
                        setTimeout(() => this.$refs.promptInput?.focus(), 100);
                    };
                },
                closeModal() {
                    this.confirmData.show = false;
                    this.promptData.show = false;
                },
                confirmAction() {
                    if (this.confirmData.onConfirm) this.confirmData.onConfirm();
                    this.closeModal();
                },
                promptAction() {
                    if (this.promptData.onConfirm) this.promptData.onConfirm(this.promptData.input);
                    this.closeModal();
                }
            }));
        });

        // Global Toast Notification Helper
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const isDarkMode = document.documentElement.classList.contains('dark');
            const bgClass = isDarkMode ? 'bg-white text-dark' : 'bg-dark text-white';
            
            toast.className = `px-6 py-3 rounded-lg font-medium shadow-minimal dark:shadow-minimal-dark text-sm transition-all duration-300 transform translate-y-10 opacity-0 pointer-events-auto flex items-center gap-2 ${bgClass}`;
            
            const icon = type === 'success' 
                ? `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`
                : `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
                
            toast.innerHTML = `${icon} <span>${message}</span>`;
            container.appendChild(toast);
            
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });
            
            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        };
        
        @if(session('success'))
            setTimeout(() => window.showToast("{{ session('success') }}", 'success'), 500);
        @endif
        @if(session('error'))
            setTimeout(() => window.showToast("{{ session('error') }}", 'error'), 500);
        @endif

        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('SW registered: ', registration);
                }).catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
            });
        }
    </script>
</body>
</html>
