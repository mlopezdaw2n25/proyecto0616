<x-app title="Registre · Tria el teu perfil">

    {{-- CONTINGUT PRINCIPAL (ocult fins que l'overlay desapareix) --}}
    <div id="onboarding-content" class="opacity-0 transition-opacity duration-500">
        <div class="flex flex-col items-center gap-10 py-4">

            {{-- Capçalera --}}
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Elige tu tipo de usuario
                </h1>
                <p class="text-slate-500 mt-2 text-sm">
                    Selecciona el perfil que millor s'adapta a tu
                </p>
            </div>

            {{-- Dues targetes --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-2xl">

                {{-- TARGETA ALUMNO --}}
                <a href="/registre?tipus=alumne"
                   class="group relative flex flex-col items-center gap-6 p-8 bg-white rounded-2xl shadow-md border-2 border-transparent
                          hover:border-blue-500 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 cursor-pointer select-none">

                    <div class="w-24 h-24 rounded-2xl bg-blue-50 flex items-center justify-center
                                group-hover:bg-blue-100 transition-colors duration-300">
                        <svg class="w-12 h-12 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 14l6.16-3.422A12.083 12.083 0 0121 17.25c0 2.082-1.568 3.75-3.5 3.75H6.5C4.568 21 3 19.332 3 17.25c0-1.093.384-2.1 1.02-2.875L12 14z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 14v7"/>
                        </svg>
                    </div>

                    <div class="text-center">
                        <h2 class="text-xl font-bold text-slate-900">Alumno</h2>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Accedeix a ofertes i connecta amb empreses del sector
                        </p>
                    </div>

                    {{-- Badge d'acció --}}
                    <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700
                                 group-hover:bg-blue-500 group-hover:text-white transition-colors duration-200">
                        Registrar-me com a Alumne
                    </span>

                    {{-- Fletxa de confirmació --}}
                    <div class="absolute top-4 right-4 w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center
                                opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow">
                        <svg class="w-3.5 h-3.5 text-white" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                {{-- TARGETA EMPRESA --}}
                <a href="/registre?tipus=empresa"
                   class="group relative flex flex-col items-center gap-6 p-8 bg-white rounded-2xl shadow-md border-2 border-transparent
                          hover:border-emerald-500 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 cursor-pointer select-none">

                    <div class="w-24 h-24 rounded-2xl bg-emerald-50 flex items-center justify-center
                                group-hover:bg-emerald-100 transition-colors duration-300">
                        <svg class="w-12 h-12 text-emerald-600" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                            <line x1="12" y1="12" x2="12" y2="17"
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <line x1="9" y1="14.5" x2="15" y2="14.5"
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <div class="text-center">
                        <h2 class="text-xl font-bold text-slate-900">Empresa</h2>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Publica ofertes i troba el talent que necessites
                        </p>
                    </div>

                    {{-- Badge d'acció --}}
                    <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700
                                 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-200">
                        Registrar-me com a Empresa
                    </span>

                    {{-- Fletxa de confirmació --}}
                    <div class="absolute top-4 right-4 w-7 h-7 rounded-full bg-emerald-500 flex items-center justify-center
                                opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow">
                        <svg class="w-3.5 h-3.5 text-white" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>

            {{-- Link a login --}}
            <p class="text-xs text-slate-400">
                Ja tens compte?
                <a href="/login" class="text-primary-500 hover:underline font-medium">Inicia sessió</a>
            </p>

        </div>
    </div>

</x-app>

{{-- ────────────────────────────────────────────────────────────
     PANTALLA DE CÀRREGA — mateixa estructura que login.blade.php
     ──────────────────────────────────────────────────────────── --}}
<div id="loading-overlay"
     class="fixed inset-0 z-50 flex flex-col items-center justify-center gap-8"
     style="background: linear-gradient(135deg, #eef2ff 0%, #fdf2f8 50%, #fefce8 100%);
            opacity: 1; transition: opacity 0.4s ease;">

    {{-- Logo --}}
    <div class="flex flex-col items-center gap-3 select-none">
        <div class="h-16 w-16 rounded-2xl flex items-center justify-center shadow-lg"
             style="background: linear-gradient(135deg, #0066FF, #FF4D6D);">
            <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <span class="text-2xl font-extrabold tracking-tight"
              style="background: linear-gradient(135deg, #0066FF, #FF4D6D);
                     -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            JobsMerce
        </span>
    </div>

    {{-- Barra de progrés --}}
    <div class="w-64 flex flex-col gap-2">
        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
            <div id="loading-bar"
                 class="h-full rounded-full"
                 style="width: 0%; background: linear-gradient(90deg, #0066FF, #FF4D6D); transition: none;">
            </div>
        </div>
        <p class="text-center text-xs text-slate-400 font-medium tracking-wide">Preparant…</p>
    </div>
</div>

<script>
    (function () {
        const overlay = document.getElementById('loading-overlay');
        const content = document.getElementById('onboarding-content');
        const bar     = document.getElementById('loading-bar');

        if (!overlay || !content || !bar) return;

        let progress  = 0;
        const totalMs = 1100;
        const steps   = 55;
        const delay   = totalMs / steps;

        const tick = setInterval(function () {
            if (progress < 85) {
                progress += (85 - progress) * 0.06 + 0.4;
            } else {
                progress += 0.4;
            }

            bar.style.transition = 'width 0.1s linear';
            bar.style.width      = Math.min(progress, 100) + '%';

            if (progress >= 100) {
                clearInterval(tick);

                bar.style.transition = 'width 0.2s ease';
                bar.style.width      = '100%';

                setTimeout(function () {
                    overlay.style.opacity       = '0';
                    overlay.style.pointerEvents = 'none';

                    setTimeout(function () {
                        content.style.opacity = '1';
                    }, 150);
                }, 250);
            }
        }, delay);
    })();
</script>
