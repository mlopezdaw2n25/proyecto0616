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
                <label for="tipus_user_id" class="block text-sm font-medium text-slate-700 mb-1">
                    Que estudies?
                    <span class="text-red-500 ml-0.5">*</span>
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </span>
                    <select
                        id="tipus_user_id"
                        name="tipus_user_id"
                        class="w-full appearance-none rounded-lg border pl-9 pr-8 py-2 text-sm text-slate-800 shadow-sm transition
                               focus:outline-none focus:ring-2 bg-white
                               {{ $errors->has('tipus_user_id') ? 'border-red-400 focus:ring-red-300' : 'border-slate-300 focus:ring-primary-400' }}"
                    >
                        <option value="" disabled {{ old('tipus_user_id') ? '' : 'selected' }}>-- Tria el teu cicle formatiu --</option>
                        @foreach($tipus as $item)
                            @if($item->id !== 2)
                            <option value="{{ $item->id }}" {{ old('tipus_user_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
                @error('tipus_user_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            @endif

            {{-- Ubicació: només per a empreses --}}
            @if(request('tipus') === 'empresa')
            <div id="location-field">
                <label class="block text-sm font-medium text-slate-700 mb-1" for="location">
                    Ubicació
                    <span class="text-red-500 ml-0.5">*</span>
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        id="location"
                        name="location"
                        value="{{ old('location') }}"
                        placeholder="Introdueix la direcció de l'empresa"
                        maxlength="255"
                        class="w-full rounded-lg border pl-9 pr-3 py-2 text-sm text-slate-800 placeholder-slate-400 shadow-sm transition
                               focus:outline-none focus:ring-2
                               {{ $errors->has('location') ? 'border-red-400 focus:ring-red-300' : 'border-slate-300 focus:ring-primary-400' }}"
                    >
                </div>
                @error('location') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            @endif

            <div class="pt-2">
                <x-button type="submit" class="w-full">Registra't</x-button>
            </div>
        </form>
    </x-auth-card>

  
</x-app>