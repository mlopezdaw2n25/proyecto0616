<x-app>
    <section class="bg-gray-100 min-h-screen p-8">

        <!-- TÍTULO -->
        <h1 class="text-2xl font-bold mb-6">El meu perfil</h1>

        <!-- PERFIL -->
        <div class="bg-white rounded-xl shadow p-6 flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <img src="https://i.pravatar.cc/80" class="w-16 h-16 rounded-full">

                <div>
                    <h2 class="font-bold text-gray-400">Informació personal</h2>
                    <p class="text-sm text-gray-600">Nom: {{ $usuari->name }}</p>
                    <p class="text-sm text-gray-600">Email: {{ $usuari->email }}</p>
                </div>
            </div>

            <div class="flex items-center">
                <a href="/editarperfil/{{$usuari->id}}" class="bg-orange-400 hover:bg-orange-500 text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
                         Modificar dades
                </a>
            </div>
            
        </div>

        <!-- CONTADOR POSTS -->
        @php
            $id = $usuari->id;
            $postsUsuari = $posts->where('user_id', $usuari->id);
            $publics = $postsUsuari->where('status', 1)->count();
        @endphp

        <div class="bg-gradient-to-r from-purple-500 to-purple-700 text-white px-6 py-2 rounded mb-4 text-sm">
            Has creat {{ $postsUsuari->count() }} posts, dels quals {{ $publics }} són públics
        </div>

        <!-- LISTADO POSTS -->
        <div class="bg-white rounded-xl shadow divide-y">

            @foreach ($postsUsuari as $post)
                <div class="flex items-center justify-between px-6 py-4">

                    <!-- TITULO + ESTADO -->
                    <div>
                        <h3 class="font-semibold text-gray-800">
                            {{ $post->name }}
                            @if($post->status == 1)
                                <span class="text-green-500 text-sm">· Públic</span>
                            @else
                                <span class="text-red-500 text-sm">· Ocult</span>
                            @endif
                        </h3>
                    </div>

                    <!-- ACCIONES -->
                    <div class="flex items-center gap-4 text-sm">

                        <a href="/vistaprevia/{{$post->id}}" class="text-blue-500 hover:underline">
                            Vista prèvia
                        </a>

                        <a href="/editarpost/{{$post->id}}" class="text-gray-600 hover:text-black">
                            ✏️
                        </a>
                        <form action="/borrarpost/{{$post->id}}" method="POST">
                            @method('POST')
                            @csrf
                            <button class="text-gray-600 hover:text-red-500" type="submit"> 
                            🗑️
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>

        <!-- FOOTER -->
        <div class="text-xs text-gray-500 mt-2">
            Mostrant {{ $postsUsuari->count() }} resultats
        </div>

    </section>
</x-app>