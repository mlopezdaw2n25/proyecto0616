<x-app>
    @auth
        @if (session()->has('visca'))
        <div x-data="{ show:true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        class="fixed bg-blue-500 text-white py-2 px-4 rounded-x1 bottom-3 right-3 text-sm">
            <p> {{session('visca')}} </p>
        </div>
        @endif
        
  <div class="flex gap-12">
    <div class="flex-1 max-w-6xl">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            @if(session()->has('missatge'))
                        <div class="alert alert-danger" x-data="{show:true}" x-init="setTimeout(() => show = false, 4000)" x-show="show">
                             <p
            style="
                font-family: 'Arial', 'Segoe UI', sans-serif;
                font-size: 2rem;
                color: #f5f5f0;
                background-color: #2b2b2b;
                padding: 16px 32px;
                border-radius: 10px;
                text-align: center;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                box-shadow: 0 8px 20px rgba(0,0,0,0.25);
                cursor: default;
                transition: none;
            "
            onmouseover="this.style.backgroundColor = '#141414'; this.style.color = '#faf7f0'; this.style.transform = 'translateY(-2px)'; this.style.boxShadow = '0 12px 30px rgba(0,0,0,0.35)';"
            onmouseout="this.style.backgroundColor = '#2b2b2b'; this.style.color = '#f5f5f0'; this.style.transform = 'translateY(0)'; this.style.boxShadow = '0 8px 20px rgba(0,0,0,0.25)';"
        >
            {{ session('missatge') }}
        </p>
                        </div>
            @else 
            @foreach ($posts as $post)
                <article class="bg-white rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all">
                    <img src="{{ $post['url'] }}" alt="{{ $post['name'] }}" class="w-full h-72 object-cover">
                    <div class="p-10">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4 leading-tight" ><a href="/vistaprevia/{{$post['id']}}">{{ $post['name'] }}</a></h2>
                        <p class="text-xl text-gray-600 mb-6 leading-relaxed">{{ $post['extract'] }}</p>
                        <div class="text-lg text-gray-500">
                            Autor: {{ $post->user->name }}<br>Data: {{ $post->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </article>   
            @endforeach
            @endif
        </div>
    </div>
    <aside class="w-80 bg-white p-8 rounded-3xl shadow-2xl border border-gray-200 self-start">
        <h3 class="text-xl font-bold text-gray-900 mb-8">Filtros</h3>
        <form method="POST" action="/posts/p" class="space-y-6">
            @csrf
            
            <div>
                <select name="category" class="w-full h-14 px-4 border-2 border-gray-200 rounded-2xl text-gray-400 focus:border-blue-500 focus:outline-none">
                    <option value="">Categoría</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 h-14 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-2xl font-bold hover:shadow-xl transition-all">
                    Buscar
                </button>
            </div>
        </form>
        <br><br>
        <h4 class="text-xl font-bold text-gray-900 mb-8">Filtrar per nom</h4>
        <form method="POST" action="/posts" class="space-y-6">
            @csrf
            <div>
                <input name="nom" class="w-full h-14 px-4 border-2 border-gray-200 text-gray-400 rounded-2xl focus:border-blue-500 focus:outline-none">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 h-14 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-2xl font-bold hover:shadow-xl transition-all">
                    Buscar
                </button>
            </div>
        </form>
        <div class="flex gap-3 pt-2">   
                <a href="/posts" class="flex-1 h-14 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 flex items-center justify-center transition-all">
                    Limpiar
                </a>
            </div>
    </aside>
</div>
    @endauth
</x-app>