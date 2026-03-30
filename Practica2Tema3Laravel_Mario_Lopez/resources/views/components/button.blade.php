@props(['type' => 'button'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2 rounded-full font-semibold transition-all duration-200 bg-gradient-to-r from-primary-500 to-accent text-white shadow-sm hover:translate-y-[-2px]']) }}>
    {{ $slot }}
</button>
