<x-app title="Registre">
    <x-auth-card title="Crea el teu compte" >       
        <form method="POST" action="/registre" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('POST')

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

            <div class="pt-2">
                <x-button type="submit" class="w-full">Registra't</x-button>
            </div>
        </form>
    </x-auth-card>

  
</x-app>