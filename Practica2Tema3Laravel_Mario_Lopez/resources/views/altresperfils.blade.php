<x-app>
    
    <section class="min-h-screen pt-4 pb-8 px-4 sm:pt-6 sm:pb-8 sm:px-8 {{ $isEmpresaPerfil ? 'empresa-layout' : '' }}"
             style="{{ $isEmpresaPerfil ? 'background-color:#e6f4ea;' : 'background-color:#f3f4f6;' }}">
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
                        <div class="h-36 {{ $isEmpresaPerfil ? '' : 'bg-gradient-to-r from-sky-500 to-indigo-600' }}"
                             style="{{ $isEmpresaPerfil ? 'background:linear-gradient(135deg,#2e7d52,#4caf7d);' : '' }}"></div>
                        <div class="absolute left-6 -bottom-12">
                            <img src="/storage/{{$usuari->ruta}}" alt="Avatar {{ $usuari->name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                        </div>
                    </div>
                    <div class="pt-14 pb-6 px-6">
                        <h2 class="text-xl font-bold text-gray-800">{{ $usuari->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $usuari->email }}</p>
                        <p class="text-sm text-gray-600 mt-2">Ubicació: <span class="font-medium text-gray-700">Barcelona, ES</span></p>
                        <p class="text-sm text-gray-600">Ocupació: <span class="font-medium text-gray-700">{{ $tipus_user->name }}</span></p>
                        @if($isEmpresaPerfil)
                        <p class="mt-2">
                            <span class="font-semibold px-2 py-0.5 rounded-full text-xs" style="background:#d4edda; color:#2e7d52;">Empresa</span>
                        </p>
                        @endif
                        @if($isEmpresaPerfil && $usuari->bio)
                        <p class="text-sm text-gray-700 mt-2 leading-relaxed">{{ $usuari->bio }}</p>
                        @endif
                        @if($tipus_user->name === 'empresa')
                        <p class="text-sm text-gray-600 mt-1">
                            <span class="font-medium text-gray-700">{{ $usuari->followers }}</span>
                            {{ $usuari->followers === 1 ? 'seguidor' : 'seguidors' }}
                        </p>
                        @endif
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

                            @if($tipus_user->name === 'empresa' && !(Auth::user()->Tipus_User && Auth::user()->Tipus_User->name === 'empresa'))
                                {{-- Follow / unfollow button for empresa profiles --}}
                                @php $following = Auth::user()->isFollowing($usuari->id); @endphp
                                @if($following)
                                    <form method="POST" action="/unfollow/{{ $usuari->id }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border transition"
                                                style="background:#f9fefb; color:#2e7d52; border-color:#a5d6b5;"
                                                onmouseover="this.style.background='#fef2f2'; this.style.color='#dc2626'; this.style.borderColor='#fca5a5';"
                                                onmouseout="this.style.background='#f9fefb'; this.style.color='#2e7d52'; this.style.borderColor='#a5d6b5';">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                            </svg>
                                            Seguint
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="/follow/{{ $usuari->id }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold rounded-lg shadow-sm transition"
                                                style="background:#2e7d52;"
                                                onmouseover="this.style.background='#245f40'"
                                                onmouseout="this.style.background='#2e7d52'">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                            Seguir
                                        </button>
                                    </form>
                                @endif

                                {{-- Pending circle invitation: empresa sent request to this alumno --}}
                                @php $circleInvite = Auth::user()->connectionWith($usuari->id); @endphp
                                @if($circleInvite && $circleInvite->status === 'pending' && $circleInvite->sender_id === $usuari->id)
                                    <div class="flex gap-2">
                                        <form method="POST" action="/connect/{{ $circleInvite->id }}/accept">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 text-white text-sm font-semibold rounded-lg shadow-sm transition"
                                                    style="background:#2e7d52;"
                                                    onmouseover="this.style.background='#245f40'"
                                                    onmouseout="this.style.background='#2e7d52'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                </svg>
                                                Unir-me al cercle
                                            </button>
                                        </form>
                                        <form method="POST" action="/connect/{{ $circleInvite->id }}/reject">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-600 text-sm font-semibold rounded-lg border border-gray-300 hover:bg-gray-200 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                                </svg>
                                                Rebutjar
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            @else
                                {{-- Empresa auth user viewing a non-empresa profile: circle flow --}}
                                @if((Auth::user()->Tipus_User && Auth::user()->Tipus_User->name === 'empresa') && $tipus_user->name !== 'empresa')
                                    @php $circleConn = Auth::user()->connectionWith($usuari->id); @endphp

                                    @if(!$circleConn || in_array($circleConn->status, ['rejected', 'cancelled']))
                                        <form method="POST" action="/connect/{{ $usuari->id }}">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 shadow-sm transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                </svg>
                                                Afegir al cercle de treball
                                            </button>
                                        </form>

                                    @elseif($circleConn->status === 'pending' && $circleConn->sender_id === Auth::id())
                                        <form method="POST" action="/connect/{{ $circleConn->id }}/cancel">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-500 text-sm font-semibold rounded-lg border border-gray-300 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition"
                                                    title="Fes clic per cancel·lar la sol·licitud">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11H7v-2h10v2z"/>
                                                </svg>
                                                Petició enviada
                                            </button>
                                        </form>

                                    @elseif($circleConn->status === 'pending' && $circleConn->receiver_id === Auth::id())
                                        <div class="flex gap-2">
                                            <form method="POST" action="/connect/{{ $circleConn->id }}/accept">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                                    Acceptar
                                                </button>
                                            </form>
                                            <form method="POST" action="/connect/{{ $circleConn->id }}/reject">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-600 text-sm font-semibold rounded-lg border border-gray-300 hover:bg-gray-200 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                                    Rebutjar
                                                </button>
                                            </form>
                                        </div>

                                    @elseif($circleConn->status === 'accepted')
                                        <form method="POST" action="/connect/{{ $circleConn->id }}/unfriend"
                                              x-data="{ hovered: false }"
                                              @mouseenter="hovered = true"
                                              @mouseleave="hovered = false">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border transition-all duration-150"
                                                    :class="hovered ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200'">
                                                <template x-if="!hovered">
                                                    <span class="inline-flex items-center gap-2 text-green-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                                        Al cercle
                                                    </span>
                                                </template>
                                                <template x-if="hovered">
                                                    <span class="inline-flex items-center gap-2 text-red-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                                        Treure del cercle
                                                    </span>
                                                </template>
                                            </button>
                                        </form>
                                    @endif

                                @else
                                {{-- Normal connect button — shows different states --}}
                                @php $conn = Auth::user()->connectionWith($usuari->id); @endphp

                                @if(!$conn || in_array($conn->status, ['rejected', 'cancelled']))
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
                                {{-- Already connected: hover reveals Desconnectar --}}
                                <form method="POST" action="/connect/{{ $conn->id }}/unfriend"
                                      x-data="{ hovered: false }"
                                      @mouseenter="hovered = true"
                                      @mouseleave="hovered = false">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border transition-all duration-150"
                                            :class="hovered
                                                ? 'bg-red-50 border-red-300'
                                                : 'bg-green-50 border-green-200'">
                                        {{-- Default state: connected (green) --}}
                                        <template x-if="!hovered">
                                            <span class="inline-flex items-center gap-2 text-green-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                </svg>
                                                Connectats
                                            </span>
                                        </template>
                                        {{-- Hover state: disconnect (red) --}}
                                        <template x-if="hovered">
                                            <span class="inline-flex items-center gap-2 text-red-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                                </svg>
                                                Desconnectar
                                            </span>
                                        </template>
                                    </button>
                                </form>
                            @endif
                                @endif {{-- end empresa-viewing-non-empresa / normal connect branch --}}
                            @endif {{-- end empresa/connect branch --}}
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
                @if($skills->isNotEmpty() && !$isEmpresaPerfil)
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

                <!-- ── OFERTES LABORALS (empresa) ────────────────────── -->
                @if($isEmpresaPerfil && $jobOffers->isNotEmpty())
                <section class="rounded-xl shadow-lg p-5 border"
                         style="background:#fff; border-color:#c3e6cb;">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2" style="color:#2e7d52;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 6h-3V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-9-2h2v2h-2V4zm-6 4h14v3H4V8zm0 9v-4h14v4H4z"/>
                        </svg>
                        Ofertes laborals
                        <span class="ml-auto text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#d4edda; color:#2e7d52;">
                            {{ $jobOffers->count() }}
                        </span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($jobOffers as $offer)
                        <div class="flex items-center gap-3 p-3 rounded-lg border transition-colors hover:bg-green-50"
                             style="background:#f9fefb; border-color:#c3e6cb;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" style="color:#4caf7d;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 7V3.5L18.5 9H13zM9 13h6v1H9v-1zm0 3h6v1H9v-1zm0-6h3v1H9v-1z"/>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $offer->file_name }}</p>
                                <p class="text-xs text-gray-400">{{ $offer->created_at->format('d/m/Y') }}</p>
                            </div>
                            <a href="/storage/{{ $offer->file_path }}" target="_blank" rel="noopener"
                               class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border transition"
                               style="color:#2e7d52; border-color:#2e7d52; background:transparent;"
                               onmouseover="this.style.background='#e6f4ea'"
                               onmouseout="this.style.background='transparent'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Veure PDF
                            </a>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
                <!-- ── FI OFERTES LABORALS ──────────────────────────────── -->

                <!-- ── CERCLE DE TREBALL (empresa profiles) ─────────────── -->
                @if($isEmpresaPerfil && $canSeeAll)
                <section class="rounded-xl shadow-lg p-5 border" style="background:#fff; border-color:#c3e6cb;">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#d4edda;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" style="color:#2e7d52;">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold leading-tight" style="color:#2e7d52;">Cercle de treball</h3>
                            <p class="text-xs" style="color:#6abf8a;">Alumnes del cercle de {{ $usuari->name }}</p>
                        </div>
                        <span class="ml-auto text-xs font-semibold px-2.5 py-1 rounded-full"
                              style="background:#d4edda; color:#2e7d52;">
                            {{ $circleMembers->count() }} {{ $circleMembers->count() === 1 ? 'membre' : 'membres' }}
                        </span>
                    </div>

                    @if($circleMembers->isEmpty())
                        <div class="flex flex-col items-center justify-center py-8 text-center border-2 border-dashed rounded-xl"
                             style="border-color:#a5d6b5;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color:#a5d6b5;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500">Encara no té ningú al cercle</p>
                        </div>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($circleMembers as $member)
                            <a href="/perfiles/{{ $member->id }}"
                               class="flex flex-col items-center gap-2.5 p-4 rounded-xl border transition hover:shadow-md"
                               style="border-color:#c3e6cb; background:#f9fefb;"
                               onmouseover="this.style.background='#e6f4ea'"
                               onmouseout="this.style.background='#f9fefb'">
                                <img src="/storage/{{ $member->ruta }}"
                                     alt="{{ $member->name }}"
                                     class="w-16 h-16 rounded-full object-cover flex-shrink-0"
                                     style="outline:3px solid #a5d6b5; outline-offset:2px;">
                                <div class="text-center w-full">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-400 truncate mt-0.5">{{ $member->email }}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @endif
                </section>
                @endif
                <!-- ── FI CERCLE DE TREBALL ───────────────────────────────── -->

                @endif {{-- canSeeAll --}}

            </div>

            <!-- COLUMNA DERECHA -->
            <aside class="space-y-6">
                {{-- ── Amistats ──────────────────────────────────────────── --}}
                @php
                    $allPerfilFriends = $usuari->friends()->get();
                    // Exclude circle connections: only show peers of the same type
                    $perfilFriends = $allPerfilFriends->filter(function($friend) use ($usuari) {
                        $friendIsEmpresa = $friend->Tipus_User && $friend->Tipus_User->name === 'empresa';
                        $viewedIsEmpresa = $usuari->Tipus_User && $usuari->Tipus_User->name === 'empresa';
                        return $friendIsEmpresa === $viewedIsEmpresa;
                    });
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

                {{-- ── TREBALLO PER (non-empresa profiles only) ──────────── --}}
                @if(!$isEmpresaPerfil && !empty($employerCompany))
                <div class="bg-white rounded-xl shadow-lg p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 6h-3V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-9-2h2v2h-2V4zm-6 4h14v3H4V8zm0 9v-4h14v4H4z"/>
                        </svg>
                        Treballo per
                    </h3>
                    <a href="/perfiles/{{ $employerCompany->id }}"
                       class="flex flex-col items-center gap-3 p-4 rounded-xl border border-gray-100 bg-gray-50 hover:bg-gray-100 transition">
                        <img src="/storage/{{ $employerCompany->ruta }}"
                             alt="{{ $employerCompany->name }}"
                             class="w-20 h-20 rounded-full object-cover border-4 border-white shadow">
                        <div class="text-center">
                            <p class="font-bold text-gray-900">{{ $employerCompany->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $employerCompany->email }}</p>
                            @if($employerCompany->location)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $employerCompany->location }}</p>
                            @endif
                        </div>
                    </a>
                </div>
                @endif
                {{-- ── FI TREBALLO PER ──────────────────────────────────── --}}

                <div class="bg-white rounded-xl shadow-lg p-4 text-sm text-gray-500">
                    <p>Consell: connecta amb persones de la teva industria per ampliar la teva xarxa.</p>
                </div>

            </aside>
        </div>
    </section>
</x-app>