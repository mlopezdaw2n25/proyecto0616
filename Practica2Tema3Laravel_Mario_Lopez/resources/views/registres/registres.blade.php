<x-app title="Registre">
    <x-auth-card title="Crea el teu compte" >

        {{-- Banner de tipus d'usuari preseleccionat --}}
        @if(request('tipus') === 'empresa')
            <div class="mb-5 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                <svg class="h-5 w-5 flex-shrink-0 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                </svg>
                <span>Estàs registrant-te com a <strong>Empresa</strong></span>
            </div>
        @endif

        <form method="POST" action="/registre" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('POST')

            {{-- Camp ocult per al tipus d'usuari --}}
            @if(request('tipus') === 'empresa')
                <input type="hidden" name="tipus_type" value="empresa">
            @endif

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
                <x-input name="name" placeholder="El teu nom" :error="$errors->first('name')" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <x-input name="email" placeholder="correu@exemple.com" :error="$errors->first('email')" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Contrasenya</label>
                <x-input type="password" name="password" placeholder="********" :error="$errors->first('password')" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="fitx">Tria una imatge</label>
                <x-input type="file" name="fitx" placeholder="imatge" id="fitx" accept="image/png, image/jpeg"/>
            </div>

            {{-- Selecció de tipus: només per a alumnes --}}
            @if(request('tipus') !== 'empresa')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipus d'Alumne</label>
                <div class="grid gap-2 bg-slate-50 p-3 rounded">
                    @foreach($tipus as $item)
                        <label class="flex items-center gap-3">
                            <input type="radio" name="tipus_user_id" value="{{ $item->id }}" class="h-4 w-4 text-primary-600" {{ old('tipus_user_id') == $item->id ? 'checked' : '' }}>
                            <span class="text-sm text-slate-700">{{ $item->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tipus_user_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>
            @endif

            <div class="pt-2">
                <x-button type="submit" class="w-full">Registra't</x-button>
            </div>
        </form>
    </x-auth-card>

  
</x-app>