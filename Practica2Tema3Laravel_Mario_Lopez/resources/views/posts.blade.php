<x-app>
    @auth
        @if (session()->has('visca'))
        <div x-data="{ show:true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        class="fixed bg-blue-500 text-white py-2 px-4 rounded-lg bottom-3 right-3 text-sm z-50">
            <p>{{session('visca')}}</p>
        </div>
        @endif
        
        <!-- CONTENEDOR DE 3 COLUMNAS ESTILO LINKEDIN -->
        <div class="flex gap-6 px-4 py-6 bg-gray-50 min-h-screen">
            
            <!-- ===== COLUMNA IZQUIERDA: INFO DEL USUARIO ===== -->
            <aside class="w-72 hidden lg:block">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6 border border-gray-200">
                    <!-- Imagen de perfil redonda -->
                    <div class="flex flex-col items-center mb-6">
                        <img src="{{ Auth::user()->avatar ?? 'https://i.pravatar.cc/96?u=' . (Auth::user()->id ?? 'default') }}" alt="Avatar" class="w-24 h-24 rounded-full shadow-lg object-cover">
                        <h2 class="mt-4 text-lg font-bold text-gray-900 text-center"><a href="/perfil">{{ Auth::user()->name }}</a></h2>
                    </div>
                    
                    <!-- Total de posts del usuario -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="text-center">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Total Posts</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $postsu->count() }}</p>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- ===== COLUMNA CENTRAL: FEED DE POSTS ===== -->
            <div class="flex-1 max-w-2xl">
                <!-- Mensaje de éxito/error -->
                @if(session()->has('missatge'))
                    <div x-data="{show:true}" x-init="setTimeout(() => show = false, 4000)" x-show="show" class="mb-6">
                        <div style="
                            font-family: 'Arial', 'Segoe UI', sans-serif;
                            font-size: 1rem;
                            color: #f5f5f0;
                            background-color: #2b2b2b;
                            padding: 12px 16px;
                            border-radius: 8px;
                            text-align: center;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
                        ">
                            {{ session('missatge') }}
                        </div>
                    </div>
                @endif
                
                <!-- Feed de posts -->
                <div class="space-y-4">
                    @forelse($posts as $post)
                        <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            
                            <!-- Header: Info del usuario y fecha -->
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $post->user->avatar ?? 'https://i.pravatar.cc/40?u=' . ($post->user->id ?? 'default') }}" alt="Avatar" class="w-10 h-10 rounded-full flex-shrink-0 object-cover">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-900 text-sm">{{ $post->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $post->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contenido del post -->
                            <div class="p-4">
                                <h2 class="text-base font-bold text-gray-900 mb-2">
                                    <a href="/vistaprevia/{{$post['id']}}" class="hover:text-blue-600 transition-colors">{{ $post['name'] }}</a>
                                </h2>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $post['body'] ?? $post['extract'] }}</p>
                            </div>
                            
                            <!-- Imagen del post (si existe) -->
                            @if($post['url'])
                                <img src="{{ $post['url'] }}" alt="{{ $post['name'] }}" class="w-full h-64 object-cover">
                            @endif
                            
                            <!-- Acciones (Like con animación) -->
                            <div class="px-4 py-3 border-t border-gray-100 flex gap-2">
                                @php
                                    $userLiked = $post->likes()->where('user_id', auth()->id())->exists();
                                    $likeCount = $post->likes()->count();
                                @endphp
                                <button
                                    class="like-btn flex items-center gap-2 transition-colors px-3 py-2 rounded-lg hover:bg-gray-50 {{ $userLiked ? 'text-red-500 liked' : 'text-gray-500 hover:text-red-500' }}"
                                    data-id="{{ $post->id }}">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                    </svg>
                                    <span class="text-sm font-medium like-count">{{ $likeCount }}</span>
                                </button>
                            </div>
                        </article>
                    @empty
                        <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
                            <p class="text-gray-500 text-lg">No hay posts disponibles.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- ===== COLUMNA DERECHA ===== -->
            <aside class="w-72 hidden lg:block ">
                <div class="sticky top-6">
                
                <!-- BLOQUE 1: OTROS USUARIOS -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Otros usuarios</h3>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @php
                            $users = \App\Models\User::where('id', '!=', Auth::id())->limit(8)->get();
                        @endphp
                        @forelse($users as $user)
                        <a href="/perfiles/{{ $user->id }}">
                            <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                                    <img src="{{ $user->avatar ?? 'https://i.pravatar.cc/40?u=' . ($user->id ?? 'default') }}" alt="Avatar" class="w-10 h-10 rounded-full flex-shrink-0 object-cover">
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 text-sm truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </a>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">No hay más usuarios</p>
                        @endforelse
                    </div>
                </div>
                
                <!-- BLOQUE 2: FILTROS (LÓGICA ORIGINAL PRESERVADA) -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Filtros</h3>
                    
                    <!-- Filtro por categoría -->
                    <form method="POST" action="/posts/p" class="space-y-4 mb-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wider">Categoría</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 focus:border-blue-500 focus:outline-none transition-colors">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full py-2 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 transition-colors">
                            Buscar
                        </button>
                    </form>
                    
                    <!-- Filtro por nombre -->
                    <form method="POST" action="/posts" class="space-y-4 mb-4 pb-4 border-b border-gray-200">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wider">Por nombre</label>
                            <input name="nom" placeholder="Busca un post..." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:outline-none transition-colors">
                        </div>
                        <button type="submit" class="w-full py-2 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 transition-colors">
                            Buscar
                        </button>
                    </form>
                    
                    <!-- Limpiar filtros -->
                    <a href="/posts" class="block w-full py-2 bg-gray-100 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-200 transition-colors text-center">
                        Limpiar filtros
                    </a>
                </div>
                </div>
            </aside>
        </div>
        
        <!-- ===== ESTILOS Y ANIMACIONES ===== -->
        <style>
            .like-btn {
                cursor: pointer;
                position: relative;
            }
            
            .like-btn.liked svg {
                fill: currentColor;
                animation: heartbeat 0.5s ease-in-out;
            }
            
            @keyframes heartbeat {
                0%, 100% { 
                    transform: scale(1);
                }
                25% { 
                    transform: scale(1.25);
                }
                50% { 
                    transform: scale(1.1);
                }
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
    @endauth
</x-app>