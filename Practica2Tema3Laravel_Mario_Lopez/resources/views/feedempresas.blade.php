<x-app>
    @auth
        @if (session()->has('visca'))
        <div x-data="{ show:true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
             class="fixed bg-emerald-600 text-white py-2 px-4 rounded-lg bottom-3 right-3 text-sm z-50">
            <p>{{ session('visca') }}</p>
        </div>
        @endif

        <div class="empresa-layout flex gap-4 lg:gap-6 px-4 sm:px-6 lg:px-8 xl:px-12 py-6 min-h-screen"
             style="background-color:#e6f4ea;">

            {{-- ═══════════════════════════════════════════════════════════════
                 COLUMNA ESQUERRA
            ════════════════════════════════════════════════════════════════ --}}
            <aside class="w-72 xl:w-80 hidden lg:block">
                <div class="rounded-lg shadow-sm p-6 sticky top-6 border bg-white"
                     style="border-color:#c3e6cb;">

                    <div class="flex flex-col items-center mb-6">
                        <img src="{{ asset('storage/' . Auth::user()->ruta) }}" alt="Avatar"
                             class="w-24 h-24 rounded-full shadow-lg object-cover"
                             style="outline:3px solid #6abf8a; outline-offset:2px;">
                        <h2 class="mt-4 text-lg font-bold text-gray-900 text-center">
                            <a href="/perfil">{{ Auth::user()->name }}</a>
                        </h2>
                        <span class="mt-1 text-xs font-semibold px-3 py-0.5 rounded-full"
                              style="background:#d4edda; color:#2e7d52;">
                            Feed d'Empreses
                        </span>
                    </div>

                    <div class="border-t pt-4 mb-4" style="border-color:#c3e6cb;">
                        <div class="text-center">
                            <p class="text-xs font-semibold uppercase tracking-widest" style="color:#4a9a6a;">
                                Les meves publicacions
                            </p>
                            <p class="text-3xl font-bold mt-2" style="color:#2e7d52;">
                                {{ $postsu->count() }}
                            </p>
                        </div>
                    </div>

                    @php $myFriends = Auth::user()->friends()->get(); @endphp
                    @if($myFriends->isNotEmpty())
                    <div class="border-t pt-4" style="border-color:#c3e6cb;">
                        <p class="text-xs font-semibold uppercase tracking-widest mb-3"
                           style="color:#4a9a6a;">
                            Connexions ({{ $myFriends->count() }})
                        </p>
                        <ul class="space-y-2">
                            @foreach($myFriends as $friend)
                            <li>
                                <a href="/perfiles/{{ $friend->id }}"
                                   class="flex items-center gap-2 rounded-lg p-1 transition hover:bg-green-50">
                                    <img src="/storage/{{ $friend->ruta }}" alt="{{ $friend->name }}"
                                         class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                    <span class="text-sm font-medium text-gray-800 truncate">{{ $friend->name }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="border-t pt-4 mt-4" style="border-color:#c3e6cb;">
                        <a href="/posts"
                           class="flex items-center gap-2 text-sm font-semibold transition hover:opacity-75"
                           style="color:#2e7d52;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                            </svg>
                            Tornar al Feed general
                        </a>
                    </div>
                </div>
            </aside>

            {{-- ═══════════════════════════════════════════════════════════════
                 COLUMNA CENTRAL: PUBLICACIONS D'EMPRESES
            ════════════════════════════════════════════════════════════════ --}}
            <div class="flex-1 min-w-0">

                {{-- Banner --}}
                <div class="rounded-xl px-5 py-4 mb-5 flex items-center gap-3 shadow-sm"
                     style="background:linear-gradient(135deg,#2e7d52,#4caf7d);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white flex-shrink-0"
                         fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 6h-3V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-9-2h2v2h-2V4zm-6 4h14v3H4V8zm0 9v-4h14v4H4z"/>
                    </svg>
                    <div>
                        <h1 class="text-white font-bold text-lg leading-tight">Feed d'Empreses</h1>
                        <p class="text-xs" style="color:#b9f6ca;">
                            Publicacions exclusives d'empreses registrades
                        </p>
                    </div>
                </div>

                @if(session()->has('missatge'))
                <div x-data="{show:true}" x-init="setTimeout(() => show = false, 4000)"
                     x-show="show" class="mb-4">
                    <div class="text-white text-sm text-center px-4 py-3 rounded-lg shadow"
                         style="background:#2e7d52;">
                        {{ session('missatge') }}
                    </div>
                </div>
                @endif

                {{-- Sort dropdown --}}
                @php
                    $sortLabels = [
                        'recent'   => 'Primers els més recents',
                        'oldest'   => 'Primers els més antics',
                        'likes'    => 'Més likes primer',
                        'comments' => 'Més comentats primer',
                        'visits'   => 'Més visitats primer',
                    ];
                    $activeSort  = $currentSort ?? 'recent';
                    $activeLabel = $sortLabels[$activeSort] ?? $sortLabels['recent'];
                @endphp

                <div class="flex items-center justify-between mb-4"
                     x-data="{ sortOpen: false }" @click.outside="sortOpen = false">
                    <p class="text-sm" style="color:#4a9a6a;">
                        <span class="font-semibold" style="color:#2e7d52;">
                            {{ $posts->count() }}
                        </span> publicacions d'empreses
                    </p>
                    <div class="relative">
                        <button @click="sortOpen = !sortOpen"
                                class="flex items-center gap-2 px-3 py-2 bg-white border rounded-xl text-sm shadow-sm transition-all"
                                style="border-color:#a5d6b5; color:#2e7d52;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0"
                                 fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 18h6v-2H3v2zm0-5h12v-2H3v2zm0-7v2h18V6H3z"/>
                            </svg>
                            <span class="text-xs font-medium hidden sm:inline"
                                  style="color:#6abf8a;">Ordenar:</span>
                            <span class="font-semibold text-sm">{{ $activeLabel }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-4 h-4 flex-shrink-0 transition-transform duration-200"
                                 fill="currentColor" viewBox="0 0 24 24"
                                 :class="sortOpen ? 'rotate-180' : ''">
                                <path d="M7 10l5 5 5-5z"/>
                            </svg>
                        </button>

                        <div x-show="sortOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             x-cloak
                             class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border z-20 py-1 overflow-hidden"
                             style="border-color:#c3e6cb;">
                            @foreach($sortLabels as $key => $label)
                                <a href="?sort={{ $key }}"
                                   @click="sortOpen = false"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors
                                          {{ $activeSort === $key ? 'font-semibold' : 'text-gray-700 hover:bg-green-50' }}"
                                   style="{{ $activeSort === $key ? 'background:#e6f4ea; color:#2e7d52;' : '' }}">
                                    @if($key === 'recent')   <span>🕒</span>
                                    @elseif($key === 'oldest')   <span>🕰</span>
                                    @elseif($key === 'likes')    <span>❤️</span>
                                    @elseif($key === 'comments') <span>💬</span>
                                    @elseif($key === 'visits')   <span>👁</span>
                                    @endif
                                    {{ $label }}
                                    @if($activeSort === $key)
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="w-3.5 h-3.5 ml-auto"
                                             fill="currentColor" viewBox="0 0 24 24"
                                             style="color:#2e7d52;">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Posts --}}
                <div class="space-y-4">
                    @forelse($posts as $post)
                    <article class="bg-white rounded-lg shadow-sm border overflow-hidden transition-shadow hover:shadow-md"
                             style="border-color:#c3e6cb;">

                        {{-- Header empresa --}}
                        <div class="p-4 border-b flex items-center gap-3"
                             style="border-color:#e6f4ea;">
                            <a href="/perfiles/{{ $post->user->id }}" class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $post->user->ruta) }}" alt="Avatar"
                                     class="w-10 h-10 rounded-full object-cover"
                                     style="outline:2px solid #a5d6b5; outline-offset:1px;">
                            </a>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <a href="/perfiles/{{ $post->user->id }}"
                                       class="font-bold text-gray-900 text-sm hover:underline">
                                        {{ $post->user->name }}
                                    </a>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full flex-shrink-0"
                                          style="background:#d4edda; color:#2e7d52;">
                                        Empresa
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">{{ $post->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        {{-- Cos del post --}}
                        <div class="p-4">
                            <h2 class="text-base font-bold text-gray-900 mb-2">
                                <a href="/vistaprevia/{{ $post->id }}"
                                   class="hover:underline transition-colors"
                                   onmouseover="this.style.color='#2e7d52'"
                                   onmouseout="this.style.color=''">
                                    {{ $post->name }}
                                </a>
                            </h2>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ $post->body ?? $post->extract }}
                            </p>
                        </div>

                        @if($post->url)
                            <img src="{{ $post->url }}" alt="{{ $post->name }}"
                                 class="w-full h-64 object-cover">
                        @endif

                        {{-- Accions --}}
                        <div class="px-4 py-3 border-t flex items-center gap-2"
                             style="border-color:#e6f4ea;">
                            @php
                                $userLiked = $post->likes()->where('user_id', auth()->id())->exists();
                                $likeCount = $post->likes()->count();
                            @endphp
                            <button class="like-btn flex items-center gap-2 transition-colors px-3 py-2 rounded-lg hover:bg-green-50 {{ $userLiked ? 'liked' : '' }}"
                                    style="{{ $userLiked ? 'color:#e53935;' : 'color:#6b7280;' }}"
                                    data-id="{{ $post->id }}">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                <span class="text-sm font-medium like-count">{{ $likeCount }}</span>
                            </button>

                            <div class="flex items-center gap-2 px-3 py-2">
                                <svg class="w-5 h-5 text-gray-500 flex-shrink-0"
                                     xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="text-sm text-gray-500 comment-count"
                                      data-post-id="{{ $post->id }}">{{ $post->coments->count() }}</span>
                                <span class="text-sm text-gray-500">comentaris</span>
                            </div>

                            <div class="flex items-center gap-2 px-3 py-2 ml-auto">
                                <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24" fill="currentColor" style="color:#4caf7d;">
                                    <path d="M3 3h2v18H3V3zm4 7h2v11H7V10zm4-4h2v15h-2V6zm4 7h2v8h-2v-8zm4-10h2v18h-2V3z"/>
                                </svg>
                                <span class="text-sm text-gray-500">{{ number_format($post->visits ?? 0) }}</span>
                                <span class="text-sm text-gray-500">visites</span>
                            </div>
                        </div>

                        {{-- Comentaris --}}
                        <div class="px-4 pb-4 border-t" style="border-color:#e6f4ea;">
                            <div class="flex items-center gap-2 pt-3">
                                <img src="{{ asset('storage/' . Auth::user()->ruta) }}" alt="Avatar"
                                     class="w-8 h-8 rounded-full flex-shrink-0 object-cover">
                                <form class="comment-form flex-1 flex gap-2"
                                      data-post-id="{{ $post->id }}">
                                    @csrf
                                    <input type="text" name="body"
                                           placeholder="Escriu un comentari..."
                                           class="comment-input flex-1 px-4 py-2.5 border rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-colors"
                                           style="border-color:#a5d6b5;"
                                           autocomplete="off">
                                    <button type="submit"
                                            class="px-4 py-2.5 text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap"
                                            style="background:#2e7d52;"
                                            onmouseover="this.style.background='#245f40'"
                                            onmouseout="this.style.background='#2e7d52'">
                                        Enviar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                    @empty
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center border"
                         style="border-color:#c3e6cb;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-4"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             style="color:#a5d6b5;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">Cap publicació d'empresa disponible.</p>
                        <p class="text-sm mt-1" style="color:#6abf8a;">
                            Les empreses encara no han publicat contingut.
                        </p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════
                 COLUMNA DRETA: DIRECTORI D'EMPRESES
            ════════════════════════════════════════════════════════════════ --}}
            <aside class="w-72 xl:w-80 hidden lg:block">
                <div class="sticky top-6 space-y-4">

                    {{-- Directori --}}
                    <div class="bg-white rounded-lg shadow-sm p-6 border"
                         style="border-color:#c3e6cb;">
                        <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0"
                                 fill="currentColor" viewBox="0 0 24 24" style="color:#2e7d52;">
                                <path d="M20 6h-3V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-9-2h2v2h-2V4zm-6 4h14v3H4V8zm0 9v-4h14v4H4z"/>
                            </svg>
                            Totes les empreses
                        </h3>

                        @php $authIsEmpresa = Auth::user()->Tipus_User && Auth::user()->Tipus_User->name === 'empresa'; @endphp

                        <div class="space-y-3">
                            @forelse($companies as $company)
                            @php $conn = Auth::user()->connectionWith($company->id); @endphp
                            <div class="flex items-center gap-3 p-2 rounded-lg transition-colors hover:bg-green-50">
                                <a href="/perfiles/{{ $company->id }}" class="flex-shrink-0">
                                    <img src="/storage/{{ $company->ruta }}" alt="{{ $company->name }}"
                                         class="w-10 h-10 rounded-full object-cover"
                                         style="outline:2px solid #a5d6b5; outline-offset:1px;">
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="/perfiles/{{ $company->id }}">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $company->name }}</p>
                                    </a>
                                    <p class="text-xs text-gray-400">
                                        {{ $company->followers }}
                                        {{ $company->followers === 1 ? 'seguidor' : 'seguidors' }}
                                    </p>
                                </div>

                                @if($authIsEmpresa)
                                    {{-- Empresa ↔ Empresa: connectar --}}
                                    @if(!$conn || in_array($conn->status, ['cancelled', 'rejected']))
                                        <form method="POST" action="/connect/{{ $company->id }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs font-semibold px-2 py-0.5 rounded-lg border transition whitespace-nowrap"
                                                    style="color:#2e7d52; border-color:#2e7d52; background:transparent;"
                                                    onmouseover="this.style.background='#e6f4ea'"
                                                    onmouseout="this.style.background='transparent'">
                                                + Connectar
                                            </button>
                                        </form>
                                    @elseif($conn->status === 'pending' && $conn->sender_id === Auth::id())
                                        <form method="POST" action="/connect/{{ $conn->id }}/cancel">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs font-semibold text-gray-400 border border-gray-300 px-2 py-0.5 rounded-lg hover:text-red-500 hover:border-red-300 transition whitespace-nowrap">
                                                Pendent ✕
                                            </button>
                                        </form>
                                    @elseif($conn->status === 'pending' && $conn->receiver_id === Auth::id())
                                        <div class="flex gap-1">
                                            <form method="POST" action="/connect/{{ $conn->id }}/accept">
                                                @csrf
                                                <button type="submit"
                                                        class="text-xs font-semibold text-white px-2 py-0.5 rounded-lg transition whitespace-nowrap"
                                                        style="background:#2e7d52;"
                                                        title="Acceptar">✓</button>
                                            </form>
                                            <form method="POST" action="/connect/{{ $conn->id }}/reject">
                                                @csrf
                                                <button type="submit"
                                                        class="text-xs font-semibold text-gray-400 border border-gray-300 px-2 py-0.5 rounded-lg hover:text-red-500 transition"
                                                        title="Rebutjar">✕</button>
                                            </form>
                                        </div>
                                    @elseif($conn->status === 'accepted')
                                        <span class="text-xs font-semibold" style="color:#2e7d52;">Connectat ✓</span>
                                    @endif
                                @else
                                    {{-- Alumno: només visitar (el follow es fa des del perfil) --}}
                                    <a href="/perfiles/{{ $company->id }}"
                                       class="text-xs font-semibold px-2 py-0.5 rounded-lg border transition whitespace-nowrap"
                                       style="color:#2e7d52; border-color:#2e7d52; background:transparent;"
                                       onmouseover="this.style.background='#e6f4ea'"
                                       onmouseout="this.style.background='transparent'">
                                        Visitar
                                    </a>
                                @endif
                            </div>
                            @empty
                            <p class="text-xs text-center py-4" style="color:#a5d6b5;">
                                No hi ha empreses registrades.
                            </p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Estadístiques --}}
                    <div class="bg-white rounded-lg shadow-sm p-6 border"
                         style="border-color:#c3e6cb;">
                        <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0"
                                 fill="currentColor" viewBox="0 0 24 24" style="color:#2e7d52;">
                                <path d="M3 3h2v18H3V3zm4 7h2v11H7V10zm4-4h2v15h-2V6zm4 7h2v8h-2v-8zm4-10h2v18h-2V3z"/>
                            </svg>
                            Estadístiques
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-1 border-b"
                                 style="border-color:#e6f4ea;">
                                <span class="text-sm text-gray-600">Empreses totals</span>
                                <span class="text-sm font-bold" style="color:#2e7d52;">
                                    {{ $companies->count() }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b"
                                 style="border-color:#e6f4ea;">
                                <span class="text-sm text-gray-600">Publicacions</span>
                                <span class="text-sm font-bold" style="color:#2e7d52;">
                                    {{ $posts->count() }}
                                </span>
                            </div>
                            @if(!$authIsEmpresa)
                            @php
                                $followedCount = $companies->filter(
                                    fn($c) => Auth::user()->isFollowing($c->id)
                                )->count();
                            @endphp
                            <div class="flex justify-between items-center py-1">
                                <span class="text-sm text-gray-600">Empreses seguides</span>
                                <span class="text-sm font-bold" style="color:#2e7d52;">
                                    {{ $followedCount }}
                                </span>
                            </div>
                            @else
                            @php
                                $connectedCount = $companies->filter(function($c) {
                                    $conn = Auth::user()->connectionWith($c->id);
                                    return $conn && $conn->status === 'accepted';
                                })->count();
                            @endphp
                            <div class="flex justify-between items-center py-1">
                                <span class="text-sm text-gray-600">Empreses connectades</span>
                                <span class="text-sm font-bold" style="color:#2e7d52;">
                                    {{ $connectedCount }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Filtres --}}
                    <div class="bg-white rounded-lg shadow-sm p-6 border"
                         style="border-color:#c3e6cb;">
                        <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0"
                                 fill="currentColor" viewBox="0 0 24 24" style="color:#2e7d52;">
                                <path d="M10 18h4v-2h-4v2zm-7-10v2h18V8H3zm3 7h12v-2H6v2z"/>
                            </svg>
                            Filtres
                        </h3>

                        <form method="GET" action="/feedempresas" class="space-y-3">
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5"
                                       style="color:#4a9a6a;">Nom de l'empresa</label>
                                <input type="text"
                                       name="nom"
                                       value="{{ $nomEmpresa }}"
                                       placeholder="Cerca per nom d'empresa..."
                                       class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none transition"
                                       style="border-color:#a5d6b5;"
                                       onfocus="this.style.borderColor='#2e7d52'"
                                       onblur="this.style.borderColor='#a5d6b5'">
                            </div>
                            <button type="submit"
                                    class="w-full py-2 text-white font-bold text-sm rounded-lg transition"
                                    style="background:#2e7d52;"
                                    onmouseover="this.style.background='#245f40'"
                                    onmouseout="this.style.background='#2e7d52'">
                                Cercar
                            </button>
                            @if($nomEmpresa)
                            <a href="/feedempresas{{ request('sort') ? '?sort='.request('sort') : '' }}"
                               class="block w-full py-2 text-sm font-bold text-center rounded-lg transition"
                               style="background:#e6f4ea; color:#2e7d52;"
                               onmouseover="this.style.background='#d4edda'"
                               onmouseout="this.style.background='#e6f4ea'">
                                Netejar filtre
                            </a>
                            @endif
                        </form>

                        @if($nomEmpresa)
                        <p class="text-xs mt-3 px-1" style="color:#6abf8a;">
                            Mostrant resultats per: <strong>"{{ $nomEmpresa }}"</strong>
                        </p>
                        @endif
                    </div>

                </div>
            </aside>
        </div>

        <style>
            .like-btn { cursor: pointer; }
            .like-btn.liked svg { animation: heartbeat 0.5s ease-in-out; }
            @keyframes heartbeat {
                0%, 100% { transform: scale(1); }
                25%       { transform: scale(1.25); }
                50%       { transform: scale(1.1); }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                document.querySelectorAll('.like-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const postId = btn.dataset.id;
                        fetch('/posts/' + postId + '/like', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            btn.querySelector('.like-count').textContent = data.count;
                            if (data.liked) {
                                btn.classList.add('liked');
                                btn.style.color = '#e53935';
                            } else {
                                btn.classList.remove('liked');
                                btn.style.color = '#6b7280';
                            }
                        });
                    });
                });

                document.querySelectorAll('.comment-form').forEach(function (form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const postId = form.dataset.postId;
                        const input  = form.querySelector('.comment-input');
                        const body   = input.value.trim();
                        if (!body) return;

                        fetch('/posts/' + postId + '/comment', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ body: body }),
                        })
                        .then(res => res.json())
                        .then(() => {
                            input.value = '';
                            const countEl = document.querySelector(
                                '.comment-count[data-post-id="' + postId + '"]'
                            );
                            if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;
                        });
                    });
                });
            });
        </script>
    @endauth
</x-app>
