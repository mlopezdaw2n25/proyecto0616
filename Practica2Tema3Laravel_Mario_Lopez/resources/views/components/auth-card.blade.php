@props(['title' => null])

<div class="min-h-screen flex justify-center">
    <div class="w-full max-w-md">
        <!-- He añadido 'text-slate-900' aquí para que afecte a todo el contenido por defecto -->
        <div class="bg-white rounded-2xl p-8 shadow-lg text-slate-900">
            @if($title)
                <!-- Azul marino profundo para el título -->
                <h2 class="text-2xl font-extrabold text-center mb-6 text-slate-900 tracking-tight">
                    {{ $title }}
                </h2>
            @endif

            <div class="text-slate-800">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
