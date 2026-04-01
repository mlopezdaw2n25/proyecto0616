  <x-app title="editar perfil">
    
        <main class="mx-auto w-full max-w-lg">
            <div class="animated-rgb-border rounded-xl p-1">
                <section class="bg-white rounded-2xl p-8 shadow-lg">
                    <h2 class="text-2xl font-extrabold text-center mb-6 text-slate-900 tracking-tight">Actualitza el teu perfil</h2>
                    <form method="POST" action="/editarperfil/{{ $usuari->id }}" class="space-y-3">
                        @csrf
                        @method('post')

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="email">Email</label>
                            <x-input id="email" name="email" type="email" placeholder="correu@exemple.com" value="{{ old('email', $usuari->email) }}" :error="$errors->first('email')" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="name">Nom</label>
                            <x-input id="name" name="name" type="text" placeholder="El teu nom" value="{{ old('name', $usuari->name) }}" :error="$errors->first('name')" />
                        </div>

                        <div>
                            <x-button type="submit" class="w-full">Actualitza</x-button>
                        </div>
                    </form>
                </section>
            </div>

            <p class="text-xs text-gray-500 text-center mt-4">Simple, actualita la teva informacio.</p>
        </main>
    

    <style>
        .animated-rgb-border {
            background: linear-gradient(90deg, rgb(255, 0, 0), rgb(0, 255, 0), rgb(0, 127, 255), rgb(255, 0, 255));
            background-size: 400% 400%;
            animation: rgbBorderShift 6s ease infinite;
        }

        @keyframes rgbBorderShift {
            0% { background-position: 0% 50%; }
            25% { background-position: 50% 50%; }
            50% { background-position: 100% 50%; }
            75% { background-position: 50% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</x-app>