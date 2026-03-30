<x-app>
     <section class="bg-gray-100 min-h-screen p-8">

        <!-- TÍTULO -->
        <h1 class="text-2xl font-bold mb-6">Perfil de {{$usuari->name}}</h1>

        <!-- PERFIL -->
        <div class="bg-white rounded-xl shadow p-6 flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <img src="https://i.pravatar.cc/80" class="w-16 h-16 rounded-full">

                <div>
                    <h2 class="font-bold">Informació personal</h2>
                    <p class="text-sm text-gray-600">Nom: {{ $usuari->name }}</p>
                    <p class="text-sm text-gray-600">Email: {{ $usuari->email }}</p>
                </div>
            </div>
        </div>

        @php
            $id = $usuari->id;
            $postsUsuari = $posts->where('user_id', $usuari->id);
            $publics = $postsUsuari->where('status', 1)->count();
        @endphp

        <div class="bg-gradient-to-r from-purple-500 to-purple-700 text-white px-6 py-2 rounded mb-4 text-sm">
            Ha creat {{ $postsUsuari->count() }} posts, dels quals {{ $publics }} són públics
        </div>

        <div class="bg-white rounded-xl shadow divide-y">

            @foreach ($posts as $post)
             @if($post->status == 1)
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h3 class="font-semibold text-gray-800"> 
                                <span class="text-green-500 text-sm">{{ $post->name }}</span>
                        </h3>
                    </div>
                </div>
                @endif
            @endforeach

        </div>

        <!-- FOOTER -->
        <div class="text-xs text-gray-500 mt-2">
            Mostrant {{ $publics }} resultats
        </div>

    </section>
</x-app>