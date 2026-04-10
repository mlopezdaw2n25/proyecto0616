<x-app title="Nova contrasenya">
    <x-auth-card title="Crea una nova contrasenya">

        {{-- Errors generals del token --}}
        @if($errors->has('token'))
            <div class="mb-5 flex items-start gap-3 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                <svg class="h-4 w-4 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span>{{ $errors->first('token') }}</span>
            </div>
        @endif

        <p class="text-sm text-slate-500 mb-6 text-center leading-relaxed">
            La contrasenya ha de tenir com a mínim <strong class="text-slate-700">8 caràcters</strong>.
        </p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            {{-- Camps ocults necessaris per validar el token al backend --}}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            {{-- Nova contrasenya --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Nova contrasenya
                </label>
                <x-input
                    type="password"
                    name="password"
                    placeholder="Mínim 8 caràcters"
                    :error="$errors->first('password')">
                    <x-slot name="icon">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </x-slot>
                </x-input>
            </div>

            {{-- Confirmar contrasenya --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Confirma la nova contrasenya
                </label>
                <x-input
                    type="password"
                    name="password_confirmation"
                    placeholder="Repeteix la contrasenya"
                    :error="$errors->first('password_confirmation')">
                    <x-slot name="icon">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </x-slot>
                </x-input>
            </div>

            <div class="pt-2">
                <x-button type="submit" class="w-full">
                    Actualitzar contrasenya
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
