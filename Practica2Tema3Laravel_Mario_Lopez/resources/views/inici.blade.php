<x-app title="Inici">

    <section class="min-h-[40vh] flex flex-col items-center justify-center text-center px-4 bg-slate-900">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white mb-2">
            Registrar o Fes Login!
        </h1>

        <h3 class="text-lg md:text-xl text-gray-300 font-medium">
            Per veure tots els posts i publicar-los
        </h3>
    </section>

    @if (session()->has('visca'))
        <div x-data="{ show:true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
             class="fixed bg-blue-500 text-white py-2 px-4 rounded-x1 bottom-3 right-3 text-sm">
            <p>{{ session('visca') }}</p>
        </div>
    @endif

</x-app>
