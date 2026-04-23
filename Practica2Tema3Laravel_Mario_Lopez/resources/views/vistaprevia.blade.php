
    <x-app>
    <style>
        @keyframes rainbow-border {
            0% { border-color: #ff0000; }
            16.66% { border-color: #ff8000; }
            33.33% { border-color: #ffff00; }
            50% { border-color: #00ff00; }
            66.66% { border-color: #0080ff; }
            83.33% { border-color: #8000ff; }
            100% { border-color: #ff0080; }
        }
        .rainbow-section {
            animation: rainbow-border 3s infinite linear;
        }
    </style>
    <section class="min-h-screen p-3 sm:p-5 md:p-6 rounded-2xl {{ $isEmpresaPost ? 'border-2 empresa-layout' : 'border-4 rainbow-section' }}"
             style="{{ $isEmpresaPost ? 'background-color:#e6f4ea; border-color:#a5d6b5;' : '' }}"
             x-data="{
                 showConfirm: false,
                 pendingId: null,
                 askDelete(id)  { this.pendingId = id; this.showConfirm = true; },
                 cancelDelete() { this.pendingId = null; this.showConfirm = false; },
                 confirmDelete() {
                     const id = this.pendingId;
                     this.showConfirm = false;
                     this.pendingId = null;
                     fetch('/comments/' + id, {
                         method: 'DELETE',
                         headers: {
                             'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                             'Accept': 'application/json',
                         }
                     }).then(r => {
                         if (r.ok) {
                             const el = document.getElementById('comment-' + id);
                             if (el) el.remove();
                         }
                     });
                 }
             }">
        {{-- Confirm modal --}}
        <div x-show="showConfirm" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4 border border-gray-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 3h6l1 1h4v2H4V4h4L9 3zm-3 5h12l-1 13H7L6 8zm5 2v9h1v-9h-1zm3 0v9h1v-9h-1z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-sm">Eliminar comentari</p>
                        <p class="text-xs text-gray-500 mt-0.5">Aquesta acció no es pot desfer.</p>
                    </div>
                </div>
                <p class="text-sm text-gray-700 mb-5">Estàs segur que vols eliminar aquest comentari?</p>
                <div class="flex gap-3 justify-end">
                    <button @click="cancelDelete()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel·lar</button>
                    <button @click="confirmDelete()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Eliminar</button>
                </div>
            </div>
        </div>
        <div class="max-w-screen-2xl mx-auto px-2 sm:px-4">

            <!-- CONTENEDOR PRINCIPAL -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 xl:gap-6 items-start">

                <!-- COLUMNA IZQUIERDA: Perfil principal -->
                <aside class="lg:col-span-3 xl:col-span-2 self-start sticky top-6 max-h-[calc(100vh-3rem)] overflow-y-auto">
                    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">

                        <div class="flex flex-col items-center text-center gap-3">

                            <h2 class="text-xl font-semibold text-gray-900">{{ $usuaripost->name ?? 'Nombre de usuario' }}</h2>
                            <p class="text-sm text-gray-500">{{ $usuaripost->tipus_user->name ?? 'Consultor de marketing digital' }}</p>
                            <p class="text-sm text-gray-500">{{ $usuaripost->company ?? 'Institut Mare de Deu de La Merce' }}</p>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="/perfiles/{{ $usuaripost->id ?? '#' }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium shadow-sm hover:bg-blue-700 transition">Ver perfil completo</a>
                        </div>

                        <!-- Recomendaciones -->
                        @php
                            $recomanats = \App\Models\User::where('tipus_user_id', $usuaripost->tipus_user_id)
                                ->where('id', '!=', $usuari->id)
                                ->where('id', '!=', $usuaripost->id)
                                ->inRandomOrder()
                                ->limit(5)
                                ->get();
                        @endphp
                        @if($recomanats->isNotEmpty() && (!$isEmpresaPost || ($usuari->Tipus_User && $usuari->Tipus_User->name === 'empresa')))
                        <div class="mt-5 pt-5 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-3 text-sm">Recomanacions</h4>
                            <div class="space-y-2">
                                @foreach($recomanats as $recomendado)
                                    @php $conn = $usuari->connectionWith($recomendado->id); @endphp
                                    <article class="flex items-center gap-2 bg-gray-50 rounded-xl border border-gray-200 p-2">
                                        <a href="/perfiles/{{ $recomendado->id }}" class="flex-shrink-0">
                                            <img src="/storage/{{ $recomendado->ruta }}" alt="{{ $recomendado->name }}" class="w-9 h-9 rounded-full object-cover">
                                        </a>
                                        <div class="flex-1 min-w-0">
                                            <a href="/perfiles/{{ $recomendado->id }}">
                                                <p class="text-xs font-semibold text-gray-800 truncate">{{ $recomendado->name }}</p>
                                            </a>
                                            @if(!$conn || in_array($conn->status, ['rejected', 'cancelled']))
                                                <form method="POST" action="/connect/{{ $recomendado->id }}">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] text-blue-600 font-semibold hover:underline">+ Connectar</button>
                                                </form>
                                            @elseif($conn->status === 'pending' && $conn->sender_id === $usuari->id)
                                                <span class="text-[10px] text-gray-400">Pendent...</span>
                                            @elseif($conn->status === 'accepted')
                                                <span class="text-[10px] text-green-600">Connectat ✓</span>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>
                </aside>

                <!-- COLUMNA CENTRAL: Publicación simulada -->
                <main class="lg:col-span-7 xl:col-span-8 space-y-5">
                    <article class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">
                        <header class="flex items-start gap-3">
                            <img src="/storage/{{$usuaripost->ruta}}" alt="Avatar" class="w-11 h-11 rounded-full">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $usuaripost->name ?? 'Nombre de usuario' }}</h3>
                                <p class="text-xs text-gray-500">{{ $tipus_user->name ?? 'Especialista en estrategia digital' }}</p>
                            </div>
                        </header>

                        <div class="mt-4 text-gray-700 text-sm leading-relaxed">
                            <h2 class="text-lg font-bold mb-2">{{ $post->name ?? 'Título de la publicación' }}</h2>
                            <p>{{ $post->body ?? 'Contenido de la publicación. Aquí se muestra una vista previa del post seleccionado.' }}</p>
                        </div>

                        @if(!empty($post->url))
                            <div class="mt-4 rounded-xl overflow-hidden">
                                @foreach($post->tags as $tag)
                                    <span class="inline-block bg-gray-200 text-gray-800 text-xs px-2 py-1 rounded-full mb-2">#{{ $tag->name }} </span>
                                @endforeach
                                <img src="{{ $post->url }}" alt="Imagen de la publicación" class="w-full h-64 object-cover">
                            </div>
                        @endif

                        <div class="mt-3 text-xs text-gray-500">
                           <strong>Fecha:</strong> {{ $post->created_at->format('d/m/Y') ?? 'Hace 2 horas' }}
                        </div>

                        <div class="mt-3 text-xs text-gray-500">
                           <strong>Categoría:</strong> {{ $post->category->name ?? 'Categoría no especificada' }}
                        </div>

                        <!-- Formulari de comentari -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Afegeix un comentari</p>
                            <form id="vistaprevia-comment-form" class="flex gap-2 items-start" data-post-id="{{ $post->id }}">
                                @csrf
                                <img src="/storage/{{ Auth::user()->ruta }}" alt="Avatar" class="w-8 h-8 rounded-full flex-shrink-0 object-cover mt-1">
                                <div class="flex-1 flex gap-2">
                                    <input
                                        type="text"
                                        name="body"
                                        placeholder="Escriu un comentari..."
                                        class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:outline-none transition-colors"
                                        autocomplete="off"
                                    >
                                    <button type="submit"
                                            class="px-4 py-2 text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap"
                                            style="background: {{ $isEmpresaPost ? '#2e7d52' : '#2563eb' }};">
                                        Enviar
                                    </button>
                                </div>
                            </form>
                        </div>

                        @if(Auth::user()->id != $usuaripost->id)
                        <div class="mt-4">
                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $post->user->email }}"
                               target="_blank" rel="noopener noreferrer"
                               title="Enviar correu a {{ $post->user->email }} via Gmail"
                               class="inline-flex items-center justify-center w-10 h-10 bg-white hover:bg-gray-50 active:bg-gray-100 border-2 border-[#EA4335] text-[#EA4335] rounded-lg shadow-sm transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </a>
                        </div>
                        @endif

                        <!-- Secció de comentaris -->
                        <div class="mt-5 pt-5 border-t border-gray-100">
                            <p class="text-sm font-semibold text-gray-800 mb-4">
                                Comentaris
                                <span class="text-gray-400 font-normal">({{ $post->coments->count() }})</span>
                            </p>
                            <div class="space-y-4" id="comments-list">
                                @if($post->coments->isEmpty())
                                    <p class="text-xs text-gray-400 text-center py-4" id="no-comments-msg">Sense comentaris encara.</p>
                                @else
                                    @foreach($post->coments as $coment)
                                        <div class="flex gap-3" id="comment-{{ $coment->id }}">
                                            <img src="/storage/{{ $coment->user->ruta }}" alt="Avatar" class="w-9 h-9 rounded-full flex-shrink-0 object-cover mt-1">
                                            <div class="flex-1">
                                                <div class="bg-gray-100 rounded-2xl px-4 py-2.5">
                                                    <div class="flex items-start justify-between gap-2">
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-900">{{ $coment->user->name ?? 'Usuari' }}</p>
                                                            <p class="text-sm text-gray-700 mt-0.5">{{ $coment->body }}</p>
                                                        </div>
                                                        @if(Auth::id() === $coment->user_id || Auth::id() === $post->user_id)
                                                        <button
                                                            @click="askDelete({{ $coment->id }})"
                                                            class="flex-shrink-0 text-gray-300 hover:text-red-500 transition-colors mt-0.5"
                                                            title="Eliminar comentari">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M9 3h6l1 1h4v2H4V4h4L9 3zm-3 5h12l-1 13H7L6 8zm5 2v9h1v-9h-1zm3 0v9h1v-9h-1z"/>
                                                            </svg>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <p class="text-[11px] text-gray-400 mt-1 ml-4">{{ $coment->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </article>

                </main>

                <!-- COLUMNA DERECHA -->
                <aside class="lg:col-span-2 self-start sticky top-6 max-h-[calc(100vh-3rem)] overflow-y-auto">
                    @if($isEmpresaPost)
                    {{-- ── POST D'EMPRESA: mostrar directori d'empreses ──────── --}}
                    <div class="rounded-2xl shadow-md p-5 border"
                         style="background:#fff; border-color:#c3e6cb;">
                        <h4 class="font-semibold text-gray-900 mb-1 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" style="color:#2e7d52;">
                                <path d="M20 6h-3V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-9-2h2v2h-2V4zm-6 4h14v3H4V8zm0 9v-4h14v4H4z"/>
                            </svg>
                            Empreses
                        </h4>
                        <p class="text-xs mb-3" style="color:#6abf8a;">Descobreix altres empreses de la plataforma.</p>

                        @php $authIsEmpresa = $usuari->Tipus_User && $usuari->Tipus_User->name === 'empresa'; @endphp

                        @forelse($companies as $company)
                        @php
                            $conn      = $usuari->connectionWith($company->id);
                            $following = $usuari->isFollowing($company->id);
                        @endphp
                        <article class="flex items-center gap-2 rounded-xl border p-2 mb-2 transition-colors hover:bg-green-50"
                                 style="background:#f9fefb; border-color:#c3e6cb;">
                            <a href="/perfiles/{{ $company->id }}" class="flex-shrink-0">
                                <img src="/storage/{{ $company->ruta }}" alt="{{ $company->name }}"
                                     class="w-9 h-9 rounded-full object-cover"
                                     style="outline:2px solid #a5d6b5; outline-offset:1px;">
                            </a>
                            <div class="flex-1 min-w-0">
                                <a href="/perfiles/{{ $company->id }}">
                                    <p class="text-xs font-semibold text-gray-800 truncate">{{ $company->name }}</p>
                                </a>
                                @if($authIsEmpresa)
                                    {{-- Empresa viendo empresa → Connectar --}}
                                    @if(!$conn || in_array($conn->status, ['rejected', 'cancelled']))
                                        <form method="POST" action="/connect/{{ $company->id }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-[10px] font-semibold hover:underline"
                                                    style="color:#2e7d52;">+ Connectar</button>
                                        </form>
                                    @elseif($conn->status === 'pending' && $conn->sender_id === $usuari->id)
                                        <span class="text-[10px] text-gray-400">Pendent...</span>
                                    @elseif($conn->status === 'accepted')
                                        <span class="text-[10px] font-semibold" style="color:#2e7d52;">Connectat ✓</span>
                                    @endif
                                @else
                                    {{-- No empresa viendo empresa → Seguir --}}
                                    @if($following)
                                        <form method="POST" action="/unfollow/{{ $company->id }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-[10px] text-gray-400 hover:text-red-500 transition">
                                                Seguint ✕
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="/follow/{{ $company->id }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-[10px] font-semibold hover:underline"
                                                    style="color:#2e7d52;">+ Seguir</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </article>
                        @empty
                        <p class="text-xs text-center py-3" style="color:#a5d6b5;">No hi ha altres empreses.</p>
                        @endforelse
                    </div>
                    @else
                    {{-- ── POST NORMAL: mostrar professionals ───────────────── --}}
                    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">
                        <h4 class="font-semibold text-gray-900 mb-2">Connexions</h4>
                        <p class="text-sm text-gray-600 mb-3">Explora altres professionals del sector.</p>
                        @foreach($usuarios as $recomendado)
                            @if($recomendado->id !== $usuari->id)
                                @php $conn = $usuari->connectionWith($recomendado->id); @endphp
                                <article class="flex items-center gap-2 bg-gray-50 rounded-xl border border-gray-200 p-2 mb-2">
                                    <a href="/perfiles/{{ $recomendado->id }}" class="flex-shrink-0">
                                        <img src="/storage/{{ $recomendado->ruta }}" alt="{{ $recomendado->name }}" class="w-9 h-9 rounded-full object-cover">
                                    </a>
                                    <div class="flex-1 min-w-0">
                                        <a href="/perfiles/{{ $recomendado->id }}">
                                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $recomendado->name }}</p>
                                        </a>
                                        @if(!$conn || in_array($conn->status, ['rejected', 'cancelled']))
                                            <form method="POST" action="/connect/{{ $recomendado->id }}">
                                                @csrf
                                                <button type="submit" class="text-[10px] text-blue-600 font-semibold hover:underline">+ Connectar</button>
                                            </form>
                                        @elseif($conn->status === 'pending' && $conn->sender_id === $usuari->id)
                                            <span class="text-[10px] text-gray-400">Pendent...</span>
                                        @elseif($conn->status === 'accepted')
                                            <span class="text-[10px] text-green-600">Connectat ✓</span>
                                        @endif
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    </div>
                    @endif
                </aside>

            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const commentForm = document.getElementById('vistaprevia-comment-form');
            if (commentForm) {
                commentForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const postId = commentForm.dataset.postId;
                    const input = commentForm.querySelector('input[name="body"]');
                    const body = input.value.trim();
                    if (!body) return;

                    fetch('/posts/' + postId + '/comment', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ body }),
                    })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        input.value = '';
                        const noMsg = document.getElementById('no-comments-msg');
                        if (noMsg) noMsg.remove();
                        const list = document.getElementById('comments-list');
                        if (list) {
                            const div = document.createElement('div');
                            div.className = 'flex gap-2';
                            div.id = 'comment-' + data.comment.id;
                            div.innerHTML = `
                                <img src="/storage/${data.comment.user.ruta}" alt="Avatar" class="w-7 h-7 rounded-full flex-shrink-0 object-cover">
                                <div class="flex-1 bg-gray-50 rounded-lg p-2">
                                    <div class="flex items-start gap-1">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-800">${data.comment.user.name}</p>
                                            <p class="text-xs text-gray-600 mt-0.5">${data.comment.body}</p>
                                            <p class="text-[10px] text-gray-400 mt-1">${data.comment.created_at}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                            list.appendChild(div);
                            list.scrollTop = list.scrollHeight;
                        }
                    });
                });
            }
        });
    </script>
</x-app>