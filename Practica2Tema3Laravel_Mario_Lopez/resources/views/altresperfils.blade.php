<x-app>
    
    <section class="bg-gray-100 min-h-screen pt-4 pb-8 px-4 sm:pt-6 sm:pb-8 sm:px-8">
        @if(Auth::user()->id == $usuari->id)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p class="font-bold">Aquest és el teu perfil!</p>
            <p>Estàs veient la teva pròpia pàgina de perfil. Per veure altres perfils, visita la secció de "Usuaris" a la feed.</p>
        </div>
        @endif
        <h1 class="text-2xl md:text-3xl font-bold mb-4">Perfil de {{ $usuari->name }}</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- COLUMNA IZQUIERDA -->
            <div class="lg:col-span-2 space-y-6">

                <!-- PERFIL CARD -->
                <article class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="relative">
                        <div class="h-36 bg-gradient-to-r from-sky-500 to-indigo-600"></div>
                        <div class="absolute left-6 -bottom-12">
                            <img src="/storage/{{$usuari->ruta}}" alt="Avatar {{ $usuari->name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                        </div>
                    </div>
                    <div class="pt-14 pb-6 px-6">
                        <h2 class="text-xl font-bold text-gray-800">{{ $usuari->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $usuari->email }}</p>
                        <p class="text-sm text-gray-600 mt-2">Ubicació: <span class="font-medium text-gray-700">Barcelona, ES</span></p>
                        <p class="text-sm text-gray-600">Ocupació: <span class="font-medium text-gray-700">{{ $tipus_user->name }}</span></p>
                        @if(Auth::user()->id != $usuari->id)
                        <div class="mt-4">
                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $usuari->email }}"
                               target="_blank" rel="noopener noreferrer"
                               title="Enviar correu a {{ $usuari->email }} via Gmail"
                               class="inline-flex items-center justify-center w-10 h-10 bg-white hover:bg-gray-50 active:bg-gray-100 border-2 border-[#EA4335] text-[#EA4335] rounded-lg shadow-sm transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>
                    
                </article>

                <!-- PUBLICACIONES -->
                @php
                    $postsUsuari = $posts->where('user_id', $usuari->id);
                    $publics = $postsUsuari->where('status', 1)->count();
                @endphp

                <section class="bg-white rounded-xl shadow-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        @if(Auth::user()->id != $usuari->id)
                        <h3 class="text-lg font-bold text-gray-800">Publicacions</h3>
                        <span class="text-sm text-gray-500">Total: {{ $publics }}</span>
                        @else
                        <h3 class="text-lg font-bold text-gray-800">Les meves Publicacions</h3>
                        <span class="text-sm text-gray-500">Total: {{ $publics }}</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($postsUsuari->where('status', 1) as $post)
                            <article class="border border-gray-200 rounded-lg overflow-hidden">
                                <img src="{{ $post->url }}" alt="Post {{ $post->name }}" class="h-36 w-full object-cover">
                                <div class="p-3">
                                    <a href="/vistaprevia/{{ $post->id }}" class="block font-semibold text-gray-800 hover:text-blue-600">{{ $post->name }}</a>
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($post->body, 60) }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full text-center text-gray-500 py-8">No hi ha publicacions públiques.</div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                       
                    </div>

                    <p class="text-xs text-gray-500 mt-2">Mostrant {{ $postsUsuari->where('status', 1)->count() }} posts públics</p>
                </section>

                <!-- ── CV DE L'USUARI ────────────────────────────────────── -->
                @if($usuari->cv)
                <section class="bg-white rounded-xl shadow-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">CV de {{ $usuari->name }}</h3>
                        <a href="/cv/{{ $usuari->id }}/download"
                           class="flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Descarregar
                        </a>
                    </div>
                    <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                        <iframe src="/cv/{{ $usuari->id }}/view" class="w-full h-[520px]" title="CV de {{ $usuari->name }}"></iframe>
                    </div>
                </section>
                @endif
                <!-- ── FI CV DE L'USUARI ──────────────────────────────────── -->

            </div>

            <!-- COLUMNA DERECHA -->
            <aside class="space-y-6">
                <div class="bg-white rounded-xl shadow-lg p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Connecta amb</h3>
                    @php
                        $suggestedUsers = \App\Models\User::where('id', '!=', $usuari->id)->take(5)->get();
                    @endphp

                    <ul class="space-y-3">
                        @foreach($suggestedUsers as $user)
                            <li class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <img src="/storage/{{ $user->ruta}}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
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

                <!-- LIKES -->
                @php
                    $likedPostIds = \App\Models\Likes::where('user_id', $usuari->id)->pluck('post_id');
                    $likedPosts = \App\Models\Post::whereIn('id', $likedPostIds)->where('status', 1)->get();
                    $likedPostsData = $likedPosts->map(fn($p) => [
                        'id'   => $p->id,
                        'name' => $p->name,
                        'url'  => $p->url,
                        'body' => \Illuminate\Support\Str::limit($p->body, 50),
                    ])->values()->toArray();
                @endphp
                <div class="bg-white rounded-xl shadow-lg p-5"
                     x-data="{
                         items: {{ json_encode($likedPostsData) }},
                         perPage: 5,
                         page: 1,
                         get total()      { return this.items.length; },
                         get totalPages() { return Math.ceil(this.total / this.perPage); },
                         get paginated()  { return this.items.slice((this.page - 1) * this.perPage, this.page * this.perPage); }
                     }">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Likes de {{ $usuari->name }}
                        <span class="text-sm font-normal text-gray-500">({{ $likedPosts->count() }})</span>
                    </h3>

                    <template x-if="total === 0">
                        <p class="text-sm text-gray-500 text-center py-4">Encara no ha donat like a cap publicació.</p>
                    </template>

                    <template x-if="total > 0">
                        <div>
                            <ul class="space-y-3">
                                <template x-for="post in paginated" :key="post.id">
                                    <li class="flex items-center gap-3 border border-gray-100 rounded-lg p-2 hover:bg-gray-50 transition">
                                        <img :src="post.url" :alt="post.name" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <a :href="'/vistaprevia/' + post.id" class="text-sm font-semibold text-gray-800 hover:text-blue-600 block truncate" x-text="post.name"></a>
                                            <p class="text-xs text-gray-500 truncate" x-text="post.body"></p>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </li>
                                </template>
                            </ul>
                            <div class="flex items-center justify-between mt-4" x-show="totalPages > 1">
                                <button @click="page--" :disabled="page === 1" class="text-xs px-3 py-1 rounded-lg border border-gray-300 disabled:opacity-40 hover:bg-gray-100 transition">← Anterior</button>
                                <span class="text-xs text-gray-500" x-text="`${page} / ${totalPages}`"></span>
                                <button @click="page++" :disabled="page === totalPages" class="text-xs px-3 py-1 rounded-lg border border-gray-300 disabled:opacity-40 hover:bg-gray-100 transition">Següent →</button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- COMENTARIS -->
                @php
                    $userComents = \App\Models\Coments::where('user_id', $usuari->id)
                        ->with('post')
                        ->latest()
                        ->get()
                        ->filter(fn($c) => $c->post && $c->post->status == 1);
                    $comentsData = $userComents->map(fn($c) => [
                        'id'         => $c->id,
                        'body'       => $c->body,
                        'post_id'    => $c->post_id,
                        'post_name'  => $c->post->name,
                        'post_url'   => $c->post->url,
                        'created_at' => $c->created_at->diffForHumans(),
                    ])->values()->toArray();
                @endphp
                <div class="bg-white rounded-xl shadow-lg p-5"
                     x-data="{
                         items: {{ json_encode($comentsData) }},
                         perPage: 5,
                         page: 1,
                         get total()      { return this.items.length; },
                         get totalPages() { return Math.ceil(this.total / this.perPage); },
                         get paginated()  { return this.items.slice((this.page - 1) * this.perPage, this.page * this.perPage); }
                     }">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Comentaris de {{ $usuari->name }}
                        <span class="text-sm font-normal text-gray-500">({{ $userComents->count() }})</span>
                    </h3>

                    <template x-if="total === 0">
                        <p class="text-sm text-gray-500 text-center py-4">Encara no ha fet cap comentari.</p>
                    </template>

                    <template x-if="total > 0">
                        <div>
                            <ul class="space-y-3">
                                <template x-for="coment in paginated" :key="coment.id">
                                    <li class="border border-gray-100 rounded-lg p-3 hover:bg-gray-50 transition">
                                        <div class="flex items-center gap-2 mb-1">
                                            <img :src="coment.post_url" :alt="coment.post_name" class="w-8 h-8 rounded object-cover flex-shrink-0">
                                            <a :href="'/vistaprevia/' + coment.post_id" class="text-xs font-semibold text-blue-600 hover:underline truncate" x-text="coment.post_name"></a>
                                        </div>
                                        <p class="text-sm text-gray-700 leading-snug" x-text="coment.body"></p>
                                        <p class="text-xs text-gray-400 mt-1" x-text="coment.created_at"></p>
                                    </li>
                                </template>
                            </ul>
                            <div class="flex items-center justify-between mt-4" x-show="totalPages > 1">
                                <button @click="page--" :disabled="page === 1" class="text-xs px-3 py-1 rounded-lg border border-gray-300 disabled:opacity-40 hover:bg-gray-100 transition">← Anterior</button>
                                <span class="text-xs text-gray-500" x-text="`${page} / ${totalPages}`"></span>
                                <button @click="page++" :disabled="page === totalPages" class="text-xs px-3 py-1 rounded-lg border border-gray-300 disabled:opacity-40 hover:bg-gray-100 transition">Següent →</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-4 text-sm text-gray-500">
                    <p>Consell: connecta amb persones de la teva industria per ampliar la teva xarxa.</p>
                </div>

            </aside>
        </div>
    </section>
</x-app>