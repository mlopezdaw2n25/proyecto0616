<x-app title="Inici">

    <section class="min-h-[40vh] flex flex-col items-center justify-center text-center px-4 bg-slate-900 py-12">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white mb-2">
            Registrar o Fes Login!
        </h1>

        <h3 class="text-lg md:text-xl text-gray-300 font-medium">
            Per veure tots els posts i publicar-los
        </h3>

        <!-- Carousel: autoplay infinite (CSS keyframes, duplicated items) -->
        <div class="w-full mt-6">
            <div class="relative">
                <div class="overflow-hidden">
                    <div class="carousel">
                        <div class="carousel-track flex items-center gap-4 py-3">
                            @for($i = 1; $i <= 10; $i++)
                                <div class="flex-shrink-0">
                                    <img src="https://picsum.photos/seed/{{ $i }}/800/500" alt="img{{ $i }}"
                                         class="h-28 md:h-40 lg:h-48 w-auto rounded-xl shadow-md object-cover transform transition duration-300 hover:scale-105">
                                </div>
                            @endfor

                            {{-- Duplicate for seamless infinite scroll --}}
                            @for($i = 1; $i <= 10; $i++)
                                <div class="flex-shrink-0">
                                    <img src="https://picsum.photos/seed/{{ $i }}/800/500" alt="img{{ $i }}-dup"
                                         class="h-28 md:h-40 lg:h-48 w-auto rounded-xl shadow-md object-cover transform transition duration-300 hover:scale-105">
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Edge fades (mask) -->
                <div class="pointer-events-none absolute inset-y-0 left-0 w-20 hidden sm:block" style="background: linear-gradient(to right, rgba(15,23,42,1), rgba(15,23,42,0));"></div>
                <div class="pointer-events-none absolute inset-y-0 right-0 w-20 hidden sm:block" style="background: linear-gradient(to left, rgba(15,23,42,1), rgba(15,23,42,0));"></div>
            </div>
        </div>

        <style>
            :root { --carousel-speed: 28s; }
            .carousel { width: 100%; }
            .carousel-track { display: flex; gap: 1rem; align-items: center; /* will be animated */ }
            /* animate by moving half the total width (because content duplicated) */
            .carousel-track { animation: scroll var(--carousel-speed) linear infinite; }
            @keyframes scroll {
                from { transform: translateX(0); }
                to { transform: translateX(-50%); }
            }
            /* Pause on hover */
            .carousel-track:hover { animation-play-state: paused; }

            /* Make sure images don't wrap and the track has natural width */
            .carousel-track > div { white-space: nowrap; }

            /* Mobile tweaks: slower and smaller */
            @media (max-width: 640px) {
                :root { --carousel-speed: 40s; }
            }
        </style>
    </section>

    @if (session()->has('visca'))
        <div x-data="{ show:true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
             class="fixed bg-blue-500 text-white py-2 px-4 rounded-x1 bottom-3 right-3 text-sm">
            <p>{{ session('visca') }}</p>
        </div>
    @endif

</x-app>
