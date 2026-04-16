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
                            <img src="storage/{{ $usuari->ruta }}" alt="Avatar {{ $usuari->name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                        </div>
                    </div>
                    <div class="pt-14 pb-6 px-6">
                        <h2 class="text-xl font-bold text-gray-800">{{ $usuari->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $usuari->email }}</p>
                        <p class="text-sm text-gray-600 mt-2">Ubicació: <span class="font-medium text-gray-700">Barcelona, ES</span></p>
                        <p class="text-sm text-gray-600">Ocupació: <span class="font-medium text-gray-700">{{ $tipus_user->name }}</span></p>

                        <div class="mt-4 flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center gap-3">
                                <a href="/editarperfil/{{ $usuari->id }}" class="bg-orange-400 hover:bg-orange-500 text-black px-4 py-2 rounded-lg text-sm font-semibold transition">Modificar dades</a>
                                <span class="px-3 py-2 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Membre actiu</span>
                            </div>
                            <a href="#cv-section"
                               class="flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-100 px-4 py-2 rounded-lg text-sm font-semibold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                {{ $usuari->cv ? 'Actualitzar CV' : 'Pujar CV' }}
                            </a>
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

                <!-- ── APTITUDS ──────────────────────────────────────────── -->
                <section class="bg-white rounded-xl shadow-lg p-5"
                         x-data="{ modalOpen: false, skillName: '', error: '' }">

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Aptituds</h3>
                        <button @click="modalOpen = true; skillName = ''; error = ''"
                                class="flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                            Afegir aptitud
                        </button>
                    </div>

                    @if($skills->isEmpty())
                        {{-- Empty state --}}
                        <div class="flex flex-col items-center justify-center py-8 text-center border-2 border-dashed border-gray-200 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500">Encara no tens aptituds</p>
                            <p class="text-xs text-gray-400 mt-1">Afegeix les teves habilitats per destacar el teu perfil</p>
                            <button @click="modalOpen = true; skillName = ''; error = ''"
                                    class="mt-4 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                + Afegir aptitud
                            </button>
                        </div>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach($skills as $skill)
                                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-full text-sm font-medium">
                                    {{ $skill->name }}
                                    <form method="POST" action="/skills/{{ $skill->id }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="ml-0.5 text-blue-400 hover:text-red-500 transition leading-none"
                                                title="Eliminar aptitud">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- ── Modal afegir aptitud ── --}}
                    <div x-show="modalOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

                        <div x-show="modalOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="bg-white rounded-2xl shadow-2xl border border-gray-100 w-full max-w-sm p-6">

                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-bold text-gray-900">Afegir aptitud</h4>
                                </div>
                                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Form --}}
                            <form method="POST" action="/skills"
                                  @submit.prevent="
                                      error = '';
                                      const v = skillName.trim();
                                      if (!v) { error = 'El camp no pot estar buit.'; return; }
                                      if (v.length > 50) { error = 'Màxim 50 caràcters.'; return; }
                                      $el.submit();
                                  ">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom de l'aptitud</label>
                                    <input type="text"
                                           name="name"
                                           x-model="skillName"
                                           placeholder="Ej: JavaScript, Disseny UX, Laravel…"
                                           maxlength="50"
                                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                           :class="error ? 'border-red-400 ring-1 ring-red-400' : ''">
                                    <p x-show="error" x-text="error" class="text-red-500 text-xs mt-1.5"></p>
                                    <p class="text-xs text-gray-400 mt-1 text-right" x-text="skillName.length + ' / 50'"></p>
                                </div>

                                <div class="flex gap-3 justify-end">
                                    <button type="button"
                                            @click="modalOpen = false"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                                        Cancel·lar
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <!-- ── FI APTITUDS ────────────────────────────────────────── -->

                <!-- ── EL MEU CV ─────────────────────────────────────────── -->
                <section id="cv-section" class="bg-white rounded-xl shadow-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">El meu CV</h3>
                        @if($usuari->cv)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                                Pujat
                            </span>
                        @endif
                    </div>

                    @if($usuari->cv)
                        {{-- ── PDF preview ── --}}
                        <div class="rounded-xl overflow-hidden border border-gray-200 mb-4 bg-gray-50">
                            <iframe src="/cv/{{ $usuari->id }}/view" class="w-full h-[520px]" title="Vista prèvia del CV"></iframe>
                        </div>

                        {{-- ── Actions ── --}}
                        <div class="flex flex-wrap items-center gap-3">
                            <form action="/cv" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center flex-1 min-w-0">
                                @csrf
                                <input type="file" name="cv" accept=".pdf"
                                       class="flex-1 min-w-0 text-xs text-gray-600 border border-gray-200 rounded-lg px-3 py-2 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition">
                                <button type="submit"
                                        class="flex-shrink-0 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                                    Substituir
                                </button>
                            </form>
                            <form action="/cv" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="flex-shrink-0 flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 3h6l1 1h4v2H4V4h4L9 3zm-3 5h12l-1 13H7L6 8zm5 2v9h1v-9h-1zm3 0v9h1v-9h-1z"/>
                                    </svg>
                                    Eliminar CV
                                </button>
                            </form>
                        </div>
                        @error('cv')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    @else
                        {{-- ── Empty state + upload ── --}}
                        <div class="flex flex-col items-center justify-center py-10 text-center border-2 border-dashed border-gray-200 rounded-xl mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500">Encara no has pujat el teu CV</p>
                            <p class="text-xs text-gray-400 mt-1">Puja un PDF de màx. 5 MB</p>
                        </div>
                        <form action="/cv" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex gap-2">
                                <input type="file" name="cv" accept=".pdf"
                                       class="flex-1 text-xs text-gray-600 border border-gray-200 rounded-lg px-3 py-2 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition">
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                                    Pujar CV
                                </button>
                            </div>
                            @error('cv')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </form>
                    @endif
                </section>
                <!-- ── FI EL MEU CV ────────────────────────────────────────── -->

            </div>

            <!-- COLUMNA DERECHA -->
            <aside class="space-y-6">
                <div class="bg-white rounded-xl shadow-lg p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Amistats</h3>
                    @php
                        $myFriends = Auth::user()->friends()->get();
                    @endphp

                    @if($myFriends->isEmpty())
                        <p class="text-sm text-gray-400 text-center py-4">Encara no tens cap amistat.</p>
                    @else
                        <ul class="space-y-3">
                            @foreach($myFriends as $friend)
                                @php $conn = Auth::user()->connectionWith($friend->id); @endphp
                                <li class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <img src="storage/{{ $friend->ruta }}" alt="{{ $friend->name }}" class="w-10 h-10 rounded-full object-cover">
                                        <div>
                                            <a href="/perfiles/{{ $friend->id }}" class="text-sm font-semibold text-gray-800 hover:text-blue-600 transition">{{ $friend->name }}</a>
                                            <p class="text-xs text-gray-500">{{ $friend->email }}</p>
                                        </div>
                                    </div>
                                    @if($conn)
                                        <form method="POST" action="/connect/{{ $conn->id }}/unfriend">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs font-semibold text-red-500 hover:text-red-700 border border-red-200 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="bg-white rounded-xl shadow-lg p-4 text-sm text-gray-500">
                    <p>Consell: connecta amb persones de la teva industria per ampliar la teva xarxa.</p>
                </div>

                <!-- POSTS QUE TE HAN GUSTADO -->
                <div class="bg-white rounded-xl shadow-lg p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Posts que t'han agradat</h3>

                    @forelse($likedPosts as $likedPost)
                        <article class="border border-gray-200 rounded-lg overflow-hidden mb-3">
                            @if($likedPost->url)
                                <img src="{{ $likedPost->url }}" alt="{{ $likedPost->name }}" class="h-28 w-full object-cover">
                            @endif
                            <div class="p-3">
                                <a href="/vistaprevia/{{ $likedPost->id }}" class="block font-semibold text-gray-800 hover:text-blue-600 text-sm">{{ $likedPost->name }}</a>
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($likedPost->body, 80) }}</p>

                                <div class="mt-3">
                                    <button
                                        class="like-btn flex items-center gap-2 text-red-500 liked transition-colors px-2 py-1 rounded-lg hover:bg-gray-50"
                                        data-id="{{ $likedPost->id }}">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                        </svg>
                                        <span class="text-xs font-medium like-count">{{ $likedPost->likes_count }}</span>
                                    </button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">Encara no has donat like a cap post.</p>
                    @endforelse
                </div>

                <!-- ELS MEUS COMENTARIS -->
                @php
                    $comentsData = $myComents->map(fn($c) => [
                        'id'        => $c->id,
                        'body'      => $c->body,
                        'post_id'   => $c->post_id,
                        'post_name' => $c->post->name,
                        'post_url'  => $c->post->url,
                        'date'      => $c->created_at->format('d/m/Y'),
                    ])->toArray();
                @endphp
                <div class="bg-white rounded-xl shadow-lg p-5"
                     x-data="{
                         items: {{ json_encode($comentsData) }},
                         perPage: 5,
                         page: 1,
                         showConfirm: false,
                         pendingId: null,
                         get total()      { return this.items.length; },
                         get totalPages() { return Math.ceil(this.total / this.perPage); },
                         get paginated()  { return this.items.slice((this.page - 1) * this.perPage, this.page * this.perPage); },
                         askDelete(id)    { this.pendingId = id; this.showConfirm = true; },
                         cancelDelete()   { this.pendingId = null; this.showConfirm = false; },
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
                                     this.items = this.items.filter(c => c.id !== id);
                                     if (this.page > this.totalPages) this.page = Math.max(1, this.totalPages);
                                 }
                             });
                         }
                     }">

                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Els meus comentaris
                        <span class="text-sm font-normal text-gray-500" x-text="'(' + total + ')'"></span>
                    </h3>

                    {{-- Custom confirm dialog --}}
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

                    <template x-if="total === 0">
                        <p class="text-sm text-gray-400 text-center py-4">Encara no has fet cap comentari.</p>
                    </template>

                    <template x-if="total > 0">
                        <div>
                            <ul class="space-y-3">
                                <template x-for="coment in paginated" :key="coment.id">
                                    <li class="border border-gray-100 rounded-lg p-3 hover:bg-gray-50 transition">
                                        <div class="flex items-start justify-between gap-2 mb-1">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <img :src="coment.post_url" :alt="coment.post_name" class="w-8 h-8 rounded object-cover flex-shrink-0">
                                                <a :href="'/vistaprevia/' + coment.post_id" class="text-xs font-semibold text-blue-600 hover:underline truncate" x-text="coment.post_name"></a>
                                            </div>
                                            <button @click="askDelete(coment.id)"
                                                class="text-gray-300 hover:text-red-500 transition-colors flex-shrink-0 mt-0.5"
                                                title="Eliminar comentari">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 3h6l1 1h4v2H4V4h4L9 3zm-3 5h12l-1 13H7L6 8zm5 2v9h1v-9h-1zm3 0v9h1v-9h-1z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-sm text-gray-700 leading-snug" x-text="coment.body"></p>
                                        <p class="text-xs text-gray-400 mt-1" x-text="coment.date"></p>
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
                <!-- FI ELS MEUS COMENTARIS -->

            </aside>
        </div>
    </section>

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
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        const countEl = btn.querySelector('.like-count');
                        countEl.textContent = data.count;

                        if (data.liked) {
                            btn.classList.add('liked', 'text-red-500');
                            btn.classList.remove('text-gray-500', 'hover:text-red-500');
                        } else {
                            btn.classList.remove('liked', 'text-red-500');
                            btn.classList.add('text-gray-500', 'hover:text-red-500');
                        }
                    });
                });
            });
        });
    </script>
</x-app>