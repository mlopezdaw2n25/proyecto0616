
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
    <section class="min-h-screen bg-[#f3f2ef] p-4 md:p-8 rounded-2xl border-4 rainbow-section">
        <div class="max-w-6xl mx-auto">

            <!-- CONTENEDOR PRINCIPAL -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- COLUMNA IZQUIERDA: Perfil principal -->
                <aside class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">
                        <div class="flex flex-col items-center text-center gap-3">
                            <img src="/storage/{{ $usuaripost->ruta }}" alt="Avatar {{ $usuaripost->name ?? 'Usuario' }}" class="w-24 h-24 rounded-full object-cover border-2 border-blue-500">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $usuaripost->name ?? 'Nombre de usuario' }}</h2>
                            <p class="text-sm text-gray-500">{{ $usuaripost->tipus_user->name ?? 'Consultor de marketing digital' }}</p>
                            <p class="text-sm text-gray-500">{{ $usuaripost->company ?? 'Institut Mare de Deu de La Merce' }}</p>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="/perfiles/{{ $usuaripost->id ?? '#' }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium shadow-sm hover:bg-blue-700 transition">Ver perfil completo</a>
                        </div>

                        <!-- Sección de comentarios -->
                        <div class="mt-4 border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Comentarios</span>
                                <span class="text-xs text-gray-400">({{ $post->coments->count() }})</span>
                            </div>
                            @if($post->coments->isEmpty())
                                <p class="text-xs text-gray-400 text-center py-2">Sin comentarios aún.</p>
                            @else
                                <div class="space-y-3 max-h-64 overflow-y-auto pr-1" id="comments-list">
                                    @foreach($post->coments as $coment)
                                        <div class="flex gap-2">
                                            <img src="/storage/{{ $coment->user->ruta}}" alt="Avatar" class="w-7 h-7 rounded-full flex-shrink-0 object-cover">
                                            <div class="flex-1 bg-gray-50 rounded-lg p-2">
                                                <p class="text-xs font-semibold text-gray-800">{{ $coment->user->name ?? 'Usuario' }}</p>
                                                <p class="text-xs text-gray-600 mt-0.5">{{ $coment->body }}</p>
                                                <p class="text-[10px] text-gray-400 mt-1">{{ $coment->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </aside>

                <!-- COLUMNA CENTRAL: Publicación simulada -->
                <main class="lg:col-span-6 space-y-5">
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

                        @if(Auth::user()->id != $usuaripost->id)
                        <div class="mt-3">
                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $post->user->email }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Contactar por Gmail</a>
                        </div>
                        @endif
                    </article>

                    <!-- Recomendaciones en línea -->
                    <section class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">
                        <h4 class="font-semibold text-gray-900 mb-4">Recomendaciones</h4>
                        <div class="flex gap-3 overflow-x-auto pb-2">
                            @foreach($usuarios as $recomendado)
                            @if($usuari->tipus_user_id == $recomendado->tipus_user_id && $recomendado->id !== $usuari->id)
                                <article class="min-w-[110px] flex flex-col items-center text-center bg-gray-50 rounded-xl border border-gray-200 p-3">
                                    <a href="/perfiles/{{ $recomendado->id }}">
                                        <img src="/storage/{{ $recomendado->ruta}}" alt="{{ $recomendado->name ?? 'Perfil' }}" class="w-14 h-14 rounded-full object-cover">
                                    </a>
                                    <p class="text-xs font-semibold text-gray-800 mt-2">{{ $recomendado->name ?? 'Perfil anónimo' }}</p>
                                    <p class="text-[11px] text-gray-500">{{ $recomendado->tipus_user->name ?? 'Misma categoría' }}</p>
                                </article>
                            @endif
                            @endforeach
                        </div>
                    </section>
                </main>

                <!-- COLUMNA DERECHA (opcional info extra) -->
                <aside class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-5">
                        <h4 class="font-semibold text-gray-900 mb-2">Conexiones</h4>
                        <p class="text-sm text-gray-600">Explora otros profesionales con intereses similares en tu sector.</p>
                        @foreach($usuarios as $recomendado)
                            @if( $recomendado->id !== $usuari->id)
                                <article class="min-w-[110px] flex flex-col items-center text-center bg-gray-50 rounded-xl border border-gray-200 p-3">                      
                                    <p class="text-xs font-semibold text-gray-800 mt-2">
                                        <a href="/perfiles/{{ $recomendado->id }}" class="flex items-center gap-2">
                                        <img src="/storage/{{ $recomendado->ruta}}" alt="{{ $recomendado->name ?? 'Perfil' }}" class="w-14 h-14 rounded-full object-cover" ">
                                    </a> {{ $recomendado->name ?? 'Perfil anónimo' }}
                                </p>
                                </article>
                            @endif
                        @endforeach
                    </div>
                </aside>

            </div>
        </div>
    </section>
</x-app>