<x-app title="Registrat">
    <section class="px-6 py-8 bg-white">
        <main class="max-w-lg mx-auto mt-10">
            <div class="border border-gray-200 p-6 rounded-xl bg-white">
                <h1 class="text-center font-bold text-xl">Registra't!</h1>

                <form method="POST" action="/registre" class="mt-10">
                    @csrf
                    @method('post')

                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="">
                            Nom:
                        </label>
                        <input
                            class="border border-gray-200 p-2 w-full rounded"
                            name="name"
                            id=""
                            value="{{ old('name') }}"
                        >
                        @error('name')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="">
                            Email:
                        </label>
                        <input
                            class="border border-gray-200 p-2 w-full rounded"
                            name="email"
                            id=""
                            value="{{ old('email') }}"
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="">
                            Password:
                        </label>
                        <input
                            class="border border-gray-200 p-2 w-full rounded"
                            name="password"
                            id=""
                            value="{{ old('password') }}"
                        >
                        @error('password')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="bg-blue-500 text-white uppercase font-semibold text-xs py-2 px-10 rounded-2xl hover:bg-blue-600"
                        >
                            Signa
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </section>
</x-app>
