@props(['variant' => 'primary', 'size' => 'md'])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold transition-all duration-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-dark text-white hover:bg-opacity-90 dark:bg-white dark:text-dark dark:hover:bg-opacity-90 shadow-minimal',
        'secondary' => 'bg-light text-dark hover:bg-gray-200 dark:bg-card dark:text-white dark:hover:bg-borderdark border border-transparent',
        'outline' => 'bg-transparent border-2 border-dark text-dark hover:bg-dark hover:text-white dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-dark',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 shadow-minimal',
        'ghost' => 'bg-transparent text-dark hover:bg-light dark:text-white dark:hover:bg-card',
        'glass' => 'bg-white/20 dark:bg-white/10 backdrop-blur-md border border-white/30 dark:border-white/10 text-dark dark:text-white hover:bg-white/30 dark:hover:bg-white/20',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs rounded-lg',
        'md' => 'px-5 py-2.5 text-sm rounded-xl',
        'lg' => 'px-8 py-4 text-base rounded-2xl',
        'icon' => 'p-2 rounded-lg',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($attributes->has('href'))
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
