@props(['type' => 'text', 'name' => null, 'value' => null, 'placeholder' => '', 'error' => null])

<div class="relative">
    @if(isset($icon))
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            {{ $icon }}
        </div>
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500 transition']) }}>
    @else
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'w-full pr-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500 transition']) }}>
    @endif

    @if($error)
        <p class="text-red-500 text-xs mt-2">{{ $error }}</p>
    @endif
</div>
