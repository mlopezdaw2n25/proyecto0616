<x-app title="Registrat">
    <section class="px-6 py-8 bg-white">
        <main class="max-w-lg mx-auto mt-10">
            <div class="border border-gray-200 p-6 rounded-xl bg-white">
                <h1 class="text-center font-bold text-xl">Registra't!</h1>

                <form method="POST" action="/registre" class="mt-10">
                    @csrf
                    @method('POST')
                    {{-- Campo: Nombre --}}
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="name">
                            Nom:
                        </label>
                        <input
                            class="border border-gray-200 p-2 w-full rounded"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                        >
                        @error('name')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Email --}}
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="email">
                            Email:
                        </label>
                        <input
                            class="border border-gray-200 p-2 w-full rounded"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Password --}}
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="password">
                            Password:
                        </label>
                        <input
                            type="password"
                            class="border border-gray-200 p-2 w-full rounded"
                            name="password"
                            id="password"
                        >
                        @error('password')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo Radio: Tipus Alumne --}}
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700">
                            Tipus d'Alumne:
                        </label>

                        <div class="grid grid-cols-1 gap-2 border border-gray-100 p-4 rounded bg-gray-50">
                            @foreach($tipus as $item)
                                <div class="flex items-center">
                                    <input 
                                        type="radio" 
                                        name="tipus_user_id"
                                        value="{{ $item->id }}" 
                                        id="tipus_{{ $item->id }}"
                                        class="border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                        {{ old('tipus_user_id') == $item->id ? 'checked' : '' }}
                                    >
                                    <label for="tipus_{{ $item->id }}" class="ml-2 text-sm text-gray-600">
                                        {{ $item->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        @error('tipus_user_id')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botón Enviar --}}
                    <div class="mt-6">
                        <button
                            type="submit"
                            class="bg-blue-500 text-white uppercase font-semibold text-xs py-2 px-10 rounded-2xl hover:bg-blue-600 w-full"
                        >
                            Signa
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </section>
</x-app>