<x-app>
    <section class="bg-gray-100 min-h-screen pt-4 pb-8 px-4 sm:pt-6 sm:pb-8 sm:px-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-4">El meu perfil</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- COLUMNA IZQUIERDA -->
            <div class="lg:col-span-2 space-y-6">

                <!-- PERFIL CARD -->
                <article class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="relative">
                        <div class="h-36 bg-gradient-to-r from-sky-500 to-indigo-600"></div>
                        <div class="absolute left-6 -bottom-12">
                            <img src="https://i.pravatar.cc/120?u={{ $usuari->id }}" alt="Avatar {{ $usuari->name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                        </div>
                    </div>
                    <div class="pt-14 pb-6 px-6">
                        <h2 class="text-xl font-bold text-gray-800">{{ $usuari->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $usuari->email }}</p>
                        <p class="text-sm text-gray-600 mt-2">Ubicació: <span class="font-medium text-gray-700">Barcelona, ES</span></p>
                        <p class="text-sm text-gray-600">Ocupació: <span class="font-medium text-gray-700">{{ $tipus_user->name }}</span></p>

                        <div class="mt-4 flex gap-3">
                            <a href="/editarperfil/{{ $usuari->id }}" class="bg-orange-400 hover:bg-orange-500 text-black px-4 py-2 rounded-lg text-sm font-semibold transition">Modificar dades</a>
                            <span class="px-3 py-2 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Membre actiu</span>
                        </div>
                    </div>
                </article>

                <!-- PUBLICACIONES -->
                <section class="bg-white rounded-xl shadow-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Publicacions</h3>
                        <span class="text-sm text-gray-500">Total: {{ $posts->total() }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($posts as $post)
                            <article class="border border-gray-200 rounded-lg overflow-hidden">
                                <img src="{{ $post->url }}" alt="Post {{ $post->name }}" class="h-36 w-full object-cover">
                                <div class="p-3">
                                    <a href="/vistaprevia/{{ $post->id }}" class="block font-semibold text-gray-800 hover:text-blue-600">{{ $post->name }}</a>
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($post->body, 60) }}</p>

                                    <div class="mt-3 flex items-center justify-between text-sm">
                                        <span class="px-2 py-1 rounded-full text-white {{ $post->status == 1 ? 'bg-emerald-500' : 'bg-gray-400' }}">
                                            {{ $post->status == 1 ? 'Públic' : 'Ocult' }}
                                        </span>
                                        <div class="flex gap-2">
                                            <form action="{{ $post->status == 1 ? '/editarpost/'.$post->id : '/editarpos/'.$post->id }}" method="post">
                                                @csrf
                                                @method('post')
                                                <button type="submit" class="text-blue-600 hover:text-blue-800 transition" title="{{ $post->status == 1 ? 'Ocultar' : 'Mostrar' }}">
                                                    {{ $post->status == 1 ? 'Ocultar' : 'Mostrar' }}
                                                </button>
                                            </form>
                                            <form action="/borrarpost/{{ $post->id }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full text-center text-gray-500 py-8">No hi ha publicacions per mostrar.</div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>

                    <p class="text-xs text-gray-500 mt-2">Mostrant {{ $posts->count() }} de {{ $posts->total() }} resultats</p>
                </section>
            </div>

            <!-- COLUMNA DERECHA -->
            <aside class="space-y-6">
                <div class="bg-white rounded-xl shadow-lg p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Connecta amb</h3>
                    @php
                        $suggestedUsers = isset($suggestedUsers) ? $suggestedUsers : \App\Models\User::where('id', '!=', $usuari->id)->take(5)->get();
                    @endphp

                    <ul class="space-y-3">
                        @foreach($suggestedUsers as $user)
                            <li class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <img src="https://i.pravatar.cc/48?u={{ $user->id }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <a href="/perfiles/{{ $user->id }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">Conectar</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-4 text-sm text-gray-500">
                    <p>Consell: connecta amb persones de la teva industria per ampliar la teva xarxa.</p>
                </div>
            </aside>
        </div>
    </section>
</x-app>