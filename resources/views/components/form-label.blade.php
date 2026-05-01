@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-bold leading-6 text-dark dark:text-white mb-2']) }}>
    {{ $value ?? $slot }}
</label>
