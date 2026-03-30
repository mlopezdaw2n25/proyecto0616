<x-app title="Inicia sessió">
    <x-auth-card title="Accedeix al teu compte">
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
                <label class="block text-sm font-medium text-slate-700 mb-1">Contrasenya</label>
                <x-input type="password" name="password" placeholder="********" :error="$errors->first('password')" />
            </div>

            <div class="pt-2">
                <x-button type="submit" class="w-full">Accedeix</x-button>
            </div>
        </form>
    </x-auth-card>
</x-app>
