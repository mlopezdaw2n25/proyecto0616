@props(['containerClass' => 'container mx-auto px-4'])

<div {{ $attributes->merge(['class' => $containerClass]) }}>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <aside class="md:col-span-3">
            {{ $left ?? '' }}
        </aside>

        <section class="md:col-span-6">
            {{ $slot }}
        </section>

        <aside class="md:col-span-3">
            {{ $right ?? '' }}
        </aside>
    </div>
</div>
