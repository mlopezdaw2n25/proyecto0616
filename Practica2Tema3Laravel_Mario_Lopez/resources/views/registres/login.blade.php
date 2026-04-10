<x-app title="Inicia sessió">
    <x-auth-card title="Accedeix al teu compte">

        {{-- Missatge d'èxit provinent del flux de reset de contrasenya --}}
        @if(session('success'))
            <div class="mb-5 flex items-start gap-3 rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-green-700">
                <svg class="h-4 w-4 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Missatge d'estat provinent del flux "forgot password" --}}
        @if(session('status'))
            <div class="mb-5 flex items-start gap-3 rounded-lg bg-blue-50 border border-blue-200 p-3 text-sm text-blue-700">
                <svg class="h-4 w-4 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="/login" class="space-y-4">
            @csrf
            @method('post')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <x-input name="email" placeholder="correu@exemple.com" :error="$errors->first('email')">
                    <x-slot name="icon">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </x-slot>
                </x-input>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-slate-700">Contrasenya</label>
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-primary-500 hover:underline font-medium">
                        ¿Has oblidat la contrasenya?
                    </a>
                </div>
                <x-input type="password" name="password" placeholder="********" :error="$errors->first('password')" />
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    class="h-4 w-4 rounded border-gray-300 text-primary-500 focus:ring-primary-500 cursor-pointer"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label for="remember" class="ml-2 text-sm text-slate-600 cursor-pointer select-none">
                    Recorda'm
                </label>
            </div>

            <div class="pt-2">
                <x-button type="submit" class="w-full">Accedeix</x-button>
            </div>
        </form>
    </x-auth-card>
</x-app>

{{-- Pantalla de càrrega --}}
<div id="loading-overlay"
     class="fixed inset-0 z-50 flex flex-col items-center justify-center gap-8 opacity-0 pointer-events-none transition-opacity duration-300"
     style="background: linear-gradient(135deg, #eef2ff 0%, #fdf2f8 50%, #fefce8 100%);">

    {{-- Logo --}}
    <div class="flex flex-col items-center gap-3 select-none">
        <div class="h-16 w-16 rounded-2xl flex items-center justify-center shadow-lg"
             style="background: linear-gradient(135deg, #0066FF, #FF4D6D);">
            <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <span class="text-2xl font-extrabold tracking-tight"
              style="background: linear-gradient(135deg, #0066FF, #FF4D6D); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            JobsMerce
        </span>
    </div>

    {{-- Barra de progrés --}}
    <div class="w-64 flex flex-col gap-2">
        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
            <div id="loading-bar"
                 class="h-full rounded-full transition-none"
                 style="width: 0%; background: linear-gradient(90deg, #0066FF, #FF4D6D);">
            </div>
        </div>
        <p class="text-center text-xs text-slate-400 font-medium tracking-wide">Iniciant sessió…</p>
    </div>
</div>

<script>
    (function () {
        const form    = document.querySelector('form[action="/login"]');
        const overlay = document.getElementById('loading-overlay');
        const bar     = document.getElementById('loading-bar');

        if (!form || !overlay || !bar) return;

        form.addEventListener('submit', function () {
            // Mostra overlay
            overlay.style.pointerEvents = 'all';
            overlay.style.opacity       = '1';

            const totalMs  = 2800;   // durada estimada fins que la pàgina carregui
            const steps    = 120;
            const interval = totalMs / steps;
            let   progress = 0;

            // Progressió ràpida fins al 85 %, després es frena esperant resposta del servidor
            const tick = setInterval(function () {
                if (progress < 85) {
                    progress += (85 - progress) * 0.045 + 0.3;
                } else if (progress < 95) {
                    progress += 0.08;
                }
                if (progress >= 95) {
                    progress = 95;
                    clearInterval(tick);
                }
                bar.style.transition = 'width 0.12s linear';
                bar.style.width      = progress + '%';
            }, interval);

            // Quan beforeunload la pàgina nova ja s'ha carregat: completa la barra
            window.addEventListener('beforeunload', function () {
                clearInterval(tick);
                bar.style.transition = 'width 0.25s ease';
                bar.style.width      = '100%';
            });
        });
    })();
</script>
