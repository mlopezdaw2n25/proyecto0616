<x-app title="Recuperar contrasenya">
    <x-auth-card title="Recupera el teu accés">

        {{-- Missatge d'èxit (en cas que ja s'hagi enviat un correu) --}}
        @if(session('status'))
            <div class="mb-5 flex items-start gap-3 rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-green-700">
                <svg class="h-4 w-4 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <p class="text-sm text-slate-500 mb-6 text-center leading-relaxed">
            Introdueix el teu email i t'enviarem un enllaç per restablir la contrasenya.
            L'enllaç serà vàlid durant <strong class="text-slate-700">60 minuts</strong>.
        </p>

        <form method="POST" action="{{ route('password.send') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Correu electrònic
                </label>
                <x-input
                    name="email"
                    type="email"
                    placeholder="correu@exemple.com"
                    :value="old('email')"
                    :error="$errors->first('email')">
                    <x-slot name="icon">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </x-slot>
                </x-input>
            </div>

            <div class="pt-2">
                <x-button type="submit" class="w-full">
                    Enviar enllaç de recuperació
                </x-button>
            </div>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            <a href="{{ route('login') }}" class="text-primary-500 hover:underline font-medium">
                ← Tornar al login
            </a>
        </p>

    </x-auth-card>
</x-app>
