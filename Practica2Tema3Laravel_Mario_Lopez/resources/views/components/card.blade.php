@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 shadow-md rounded-xl p-6 ' . $class]) }}>
    {{ $slot }}
</div>
