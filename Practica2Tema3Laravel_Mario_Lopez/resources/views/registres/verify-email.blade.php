<x-app title="Verifica el teu email">
    <x-auth-card title="Revisa el teu correu">

        {{-- Missatge d'èxit reenviat --}}
        @if(session('status'))
            <div class="mb-5 flex items-start gap-3 rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-green-700">
                <svg class="h-4 w-4 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        {{-- Icona email --}}
        <div class="flex justify-center mb-5">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 border border-blue-100">
                <svg class="h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <p class="text-sm text-slate-600 mb-2 text-center leading-relaxed">
            T'hem enviat un correu de confirmació a:
        </p>
        <p class="text-center font-semibold text-slate-800 mb-5 break-all">
            {{ session('pending_email', 'el teu correu') }}
        </p>
        <p class="text-sm text-slate-500 mb-6 text-center leading-relaxed">
            Fes clic a l'enllaç del correu per activar el teu compte.
            L'enllaç és vàlid durant <strong class="text-slate-700">60 minuts</strong>.
        </p>

        {{-- Botó reenviar --}}
        <form method="POST" action="{{ route('verification.resend') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="email" value="{{ session('pending_email') }}">

            @error('email')
                <p class="text-red-500 text-xs text-center">{{ $message }}</p>
            @enderror

            <button type="submit"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                Reenviar email de verificació
            </button>
        </form>

        <p class="mt-5 text-center text-xs text-slate-400">
            Ja tens compte?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Inicia sessió</a>
        </p>

    </x-auth-card>
</x-app>
