@props(['disabled' => false, 'error' => false])

@php
    $baseClasses = 'block w-full rounded-xl border-0 py-3 text-dark dark:text-white bg-light dark:bg-card shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 transition-all duration-200';
    $ringClasses = $error 
        ? 'ring-red-500 focus:ring-red-500' 
        : 'ring-gray-200 dark:ring-borderdark focus:ring-dark dark:focus:ring-white';
    $classes = $baseClasses . ' ' . $ringClasses . ' ' . ($disabled ? 'opacity-50 cursor-not-allowed' : '');
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
