<x-app title="Crear Post">
    <section class="bg-gray-100 min-h-screen p-8">
        <main class="max-w-2xl mx-auto mt-10">
            <div class="animated-border p-6 rounded-xl bg-white shadow-lg mt-10 form-border-animated">
                <h1 class="animated-title text-center font-bold text-xl text-gray-800">Crea un nou Post!</h1>

                <form method="POST" action="/publicacion" >
                    @csrf
                    @method('post')
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="nom">
                            Nom:
                        </label>
                        <input class="border border-gray-200 p-2 w-full rounded transition duration-300 focus:border-blue-500 focus:outline-none text-gray-700" name="nom" id="nom" value="{{ old('nom') }}">
                        @error('nom')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="body">
                            Body:
                        </label>
                        <input class="border border-gray-200 p-2 w-full rounded transition duration-300 focus:border-blue-500 focus:outline-none text-gray-700" name="body" id="body" value="{{ old('body') }}">
                        @error('body')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="imatge">
                            Imatge:
                        </label>
                        <input class="border border-gray-200 p-2 w-full rounded transition duration-300 focus:border-blue-500 focus:outline-none text-gray-700" name="imatge" id="imatge" value="{{ old('imatge') }}">
                        @error('imatge')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="category">
                            Categoria:
                        </label>
                        <select name="category" id="category" class="w-full h-14 px-4 border-2 border-gray-200 text-gray-700 rounded-2xl focus:border-blue-500 focus:outline-none transition duration-300">
                            <option value="">Categoría</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700">
                            Tags:
                        </label>
                        <div class="space-y-2">
                            @foreach($tags as $tag)
                                <label class="flex items-center space-x-2 border border-gray-200 p-2 rounded hover:bg-gray-50 transition duration-300">
                                    <input
                                        type="checkbox"
                                        name="tags[]"
                                        value="{{ $tag->id }}"
                                        class="rounded border-gray-300 text-blue-500 focus:ring-blue-500"
                                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                    >
                                    <span class="text-sm text-gray-700">{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('tags')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6 text-center">
                        <button type="submit"
                            class="bg-blue-500 text-white uppercase font-semibold text-xs py-2 px-10 rounded-2xl hover:bg-blue-600 transition duration-300">
                            Crear
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </section>

    <style>
        .animated-title {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899, #3b82f6);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .animated-border {
            position: relative;
            background: white;
        }

        .animated-border::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6, #ec4899, #10b981, #3b82f6);
            background-size: 400% 400%;
            border-radius: inherit;
            z-index: -1;
            animation: borderGlow 4s ease-in-out infinite;
        }

        @keyframes borderGlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .form-border-animated {
            border: 2px solid rgb(255, 0, 0);
            border-radius: 0.75rem;
            animation: formBorderColor 6s linear infinite;
        }

        @keyframes formBorderColor {
            0% { border-color: rgb(255, 0, 0); }
            16% { border-color: rgb(255, 165, 0); }
            33% { border-color: rgb(255, 255, 0); }
            50% { border-color: rgb(0, 128, 0); }
            66% { border-color: rgb(0, 0, 255); }
            83% { border-color: rgb(75, 0, 130); }
            100% { border-color: rgb(238, 130, 238); }
        }
    </style>
</x-app>