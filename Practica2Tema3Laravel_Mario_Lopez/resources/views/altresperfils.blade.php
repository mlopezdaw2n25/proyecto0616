<x-app>
    
    <section class="bg-gray-100 min-h-screen pt-4 pb-8 px-4 sm:pt-6 sm:pb-8 sm:px-8">
        @if(Auth::user()->id == $usuari->id)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p class="font-bold">Aquest és el teu perfil!</p>
            <p>Estàs veient la teva pròpia pàgina de perfil. Per veure altres perfils, visita la secció de "Usuaris" a la feed.</p>
        </div>
        @endif
        @php
            $connPriv      = Auth::user()->connectionWith($usuari->id);
            $isConnected   = $connPriv && $connPriv->status === 'accepted';
            $isOwnProfileP = Auth::user()->id === $usuari->id;
            $canSeeAll     = $isOwnProfileP || !$usuari->is_private || $isConnected;
        @endphp

        {{-- ── Custom privacy alert (only for private profiles of others) ──────── --}}
        @if(!$isOwnProfileP && $usuari->is_private && !$isConnected)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="mb-6 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 shadow-sm"
            role="alert"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 text-amber-500 mt-0.5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-semibold text-amber-800">Advertiment, aquest compte és privat</p>
                <p class="text-xs text-amber-700 mt-0.5">Connecta amb aquest usuari per veure les seves dades completes.</p>
            </div>
            <button @click="show = false" class="text-amber-400 hover:text-amber-600 transition flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        </div>
        @endif

        <div class="flex items-center gap-3 mb-4">
            <h1 class="text-2xl md:text-3xl font-bold">Perfil de {{ $usuari->name }}</h1>
            @if($usuari->is_private)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/>
                    </svg>
                    Compte privat
                </span>
            @endif
        </div>

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
                        <div class="mt-4 flex items-center gap-3 flex-wrap">
                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $usuari->email }}"
                               target="_blank" rel="noopener noreferrer"
                               title="Enviar correu a {{ $usuari->email }} via Gmail"
                               class="inline-flex items-center justify-center w-10 h-10 bg-white hover:bg-gray-50 active:bg-gray-100 border-2 border-[#EA4335] text-[#EA4335] rounded-lg shadow-sm transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </a>

                            {{-- Connect button — shows different states --}}
                            @php $conn = Auth::user()->connectionWith($usuari->id); @endphp

                            @if(!$conn || in_array($conn->status, ['rejected', 'cancelled']))
                                {{-- Not connected / was rejected/cancelled → show active button --}}
                                <form method="POST" action="/connect/{{ $usuari->id }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 shadow-sm transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                        Connectar
                                    </button>
                                </form>

                            @elseif($conn->status === 'pending' && $conn->sender_id === Auth::id())
                                {{-- You sent the request → show disabled + cancel option --}}
                                <form method="POST" action="/connect/{{ $conn->id }}/cancel">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-500 text-sm font-semibold rounded-lg border border-gray-300 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition"
                                            title="Fes clic per cancel·lar la sol·licitud">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11H7v-2h10v2z"/>
                                        </svg>
                                        Sol·licitud enviada
                                    </button>
                                </form>

                            @elseif($conn->status === 'pending' && $conn->receiver_id === Auth::id())
                                {{-- They sent you a request → show accept/reject --}}
                                <div class="flex gap-2">
                                    <form method="POST" action="/connect/{{ $conn->id }}/accept">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                            Acceptar
                                        </button>
                                    </form>
                                    <form method="POST" action="/connect/{{ $conn->id }}/reject">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-600 text-sm font-semibold rounded-lg border border-gray-300 hover:bg-gray-200 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                            Rebutjar
                                        </button>
                                    </form>
                                </div>

                            @elseif($conn->status === 'accepted')
                                {{-- Already friends --}}
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 text-sm font-semibold rounded-lg border border-green-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    Connectats
                                </span>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                </article>

                <!-- PUBLICACIONES -->
                @if(!$canSeeAll)
                <div class="bg-white rounded-xl shadow-lg p-8 text-center border border-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300 mx-auto mb-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/>
                    </svg>
                    <p class="text-slate-500 font-semibold">Aquest compte és privat</p>
                    <p class="text-sm text-slate-400 mt-1">Connecta amb {{ $usuari->name }} per veure les seves publicacions, CV i aptituds.</p>
                </div>
                @else
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

                <!-- ── APTITUDS ──────────────────────────────────────────── -->
                @if($skills->isNotEmpty())
                <section class="bg-white rounded-xl shadow-lg p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Aptituds</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($skills as $skill)
                            <span class="inline-flex items-center bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-full text-sm font-medium">
                                {{ $skill->name }}
                            </span>
                        @endforeach
                    </div>
                </section>
                @endif
                <!-- ── FI APTITUDS ────────────────────────────────────────── -->

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
                @endif {{-- canSeeAll --}}

            </div>

            <!-- COLUMNA DERECHA -->
            <aside class="space-y-6">
                {{-- ── Amistats ──────────────────────────────────────────── --}}
                @php
                    $perfilFriends = $usuari->friends()->get();
                    $isOwnProfile  = Auth::user()->id === $usuari->id;
                    $profSettings  = $usuari->getOrCreateSettings();
                @endphp

                {{-- AMISTATS: hidden if private and not connected --}}
                @if($canSeeAll && ($isOwnProfile || $profSettings->show_friends))
                <div class="bg-white rounded-xl shadow-lg p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Amistats
                        <span class="text-sm font-normal text-gray-500">({{ $perfilFriends->count() }})</span>
                    </h3>

                    @if($perfilFriends->isEmpty())
                        <p class="text-sm text-gray-400 text-center py-6">
                            @if($isOwnProfile)
                                Encara no tens cap connexió acceptada.
                            @else
                                No té amistats.
                            @endif
                        </p>
                    @else
                        <ul class="space-y-3">
                            @foreach($perfilFriends as $friend)
                                @php $authConn = Auth::user()->connectionWith($friend->id); @endphp
                                <li class="flex items-center justify-between gap-3 p-2 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <a href="/perfiles/{{ $friend->id }}" class="flex-shrink-0">
                                            <img src="/storage/{{ $friend->ruta }}" alt="{{ $friend->name }}"
                                                 class="w-10 h-10 rounded-full object-cover">
                                        </a>
                                        <div class="min-w-0">
                                            <a href="/perfiles/{{ $friend->id }}"
                                               class="text-sm font-semibold text-gray-800 hover:text-blue-600 block truncate">
                                                {{ $friend->name }}
                                            </a>
                                            <p class="text-xs text-gray-500 truncate">{{ $friend->tipus_user->name ?? '' }}</p>
                                        </div>
                                    </div>

                                    @if($isOwnProfile)
                                        {{-- Own profile: unfriend button --}}
                                        @if($authConn)
                                            <form method="POST" action="/connect/{{ $authConn->id }}/unfriend">
                                                @csrf
                                                <button type="submit"
                                                        class="flex-shrink-0 text-gray-300 hover:text-red-500 transition"
                                                        title="Eliminar amistat">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($friend->id !== Auth::id())
                                        {{-- Other's profile: show connection state with this friend --}}
                                        @if(!$authConn)
                                            <form method="POST" action="/connect/{{ $friend->id }}">
                                                @csrf
                                                <button type="submit"
                                                        class="text-xs font-semibold text-blue-600 border border-blue-600 px-2 py-1 rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                                                    + Connectar
                                                </button>
                                            </form>
                                        @elseif($authConn->status === 'pending' && $authConn->sender_id === Auth::id())
                                            <form method="POST" action="/connect/{{ $authConn->id }}/cancel">
                                                @csrf
                                                <button type="submit"
                                                        class="text-xs font-semibold text-gray-500 border border-gray-300 px-2 py-1 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-300 transition whitespace-nowrap">
                                                    Pendent ✕
                                                </button>
                                            </form>
                                        @elseif($authConn->status === 'pending' && $authConn->receiver_id === Auth::id())
                                            <div class="flex gap-1">
                                                <form method="POST" action="/connect/{{ $authConn->id }}/accept">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-xs font-semibold text-white bg-blue-600 px-2 py-1 rounded-lg hover:bg-blue-700 transition"
                                                            title="Acceptar">✓</button>
                                                </form>
                                                <form method="POST" action="/connect/{{ $authConn->id }}/reject">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-xs font-semibold text-gray-500 border border-gray-300 px-2 py-1 rounded-lg hover:bg-red-50 hover:text-red-500 transition"
                                                            title="Rebutjar">✕</button>
                                                </form>
                                            </div>
                                        @elseif($authConn->status === 'accepted')
                                            <span class="text-xs font-semibold text-green-600 border border-green-200 bg-green-50 px-2 py-1 rounded-lg whitespace-nowrap">Connectats ✓</span>
                                        @endif
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                @else
                    {{-- Show_friends disabled: show locked notice --}}
                    <div class="bg-white rounded-xl shadow-lg p-5 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 mx-auto mb-2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-600">Amistats privades</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $usuari->name }} ha privatitzat les seves amistats.</p>
                    </div>
                @endif

                <!-- LIKES -->
                @if($isOwnProfile || $profSettings->show_likes)
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
                @else
                    <div class="bg-white rounded-xl shadow-lg p-5 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 mx-auto mb-2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-600">Likes privats</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $usuari->name }} ha privatitzat els seus likes.</p>
                    </div>
                @endif

                <!-- COMENTARIS -->
                @if($canSeeAll && ($isOwnProfile || $profSettings->show_comments))
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
                @else
                    <div class="bg-white rounded-xl shadow-lg p-5 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 mx-auto mb-2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-600">Comentaris privats</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $usuari->name }} ha privatitzat els seus comentaris.</p>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-lg p-4 text-sm text-gray-500">
                    <p>Consell: connecta amb persones de la teva industria per ampliar la teva xarxa.</p>
                </div>

            </aside>
        </div>
    </section>
</x-app>