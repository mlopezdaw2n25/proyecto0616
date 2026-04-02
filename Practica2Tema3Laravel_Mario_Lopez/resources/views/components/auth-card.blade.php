@props(['title' => null])

<div class="min-h-screen flex justify-center">
    <div class="w-full max-w-md">
        <!-- He añadido 'text-slate-900' aquí para que afecte a todo el contenido por defecto -->
        <div class="bg-white rounded-2xl p-8 shadow-lg text-slate-900 auth-card-animated">
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
  <style>
        .auth-card-animated {
            border: 2px solid rgb(255, 0, 0);
            border-radius: 1rem;
            animation: authCardBorderColor 5s linear infinite;
        }

        @keyframes authCardBorderColor {
            0% { border-color: rgb(255, 0, 0); }
            16% { border-color: rgb(255, 165, 0); }
            33% { border-color: rgb(255, 255, 0); }
            50% { border-color: rgb(0, 128, 0); }
            66% { border-color: rgb(0, 0, 255); }
            83% { border-color: rgb(75, 0, 130); }
            100% { border-color: rgb(238, 130, 238); }
        }
    </style>
