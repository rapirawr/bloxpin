<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Bloxpin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #000000; color: #ffffff; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 overflow-hidden">
    
    <div class="max-w-md w-full text-center relative">
        <!-- Big Ghosty Error Code -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[15rem] md:text-[20rem] font-black text-white/[0.03] select-none z-0">
            @yield('code')
        </div>

        <div class="relative z-10">
            <div class="w-20 h-20 bg-white text-black rounded-3xl flex items-center justify-center mx-auto mb-10 shadow-2xl rotate-12">
                <span class="text-4xl font-black italic">B</span>
            </div>
            
            <h1 class="text-5xl font-black tracking-tighter mb-4 uppercase">@yield('title')</h1>
            <p class="text-gray-500 font-medium text-lg mb-10 leading-relaxed">
                @yield('message')
            </p>

            <a href="/" class="inline-block bg-white text-black font-black text-xs uppercase tracking-[0.2em] px-10 py-5 rounded-2xl hover:scale-105 transition-transform active:scale-95 shadow-xl">
                Back to Home
            </a>
        </div>
    </div>

</body>
</html>
