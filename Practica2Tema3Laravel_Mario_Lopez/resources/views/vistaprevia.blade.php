
    <x-app>
    <section class="max-w-4xl mx-auto py-10" >

        <div class="bg-gray-100 min-h-screen p-8">
            <!-- TITULO -->
        <h1 class="text-4xl font-bold text-gray-900 mb-2">
            {{ $post->name }}
        </h1>

        <!-- EXTRACT -->
        <p class="text-gray-500 mb-6">
            {{ $post->extract }}
        </p>

        <!-- AUTOR -->
        <div class="flex items-center gap-3 mb-6">
            <img src="https://i.pravatar.cc/40" class="w-10 h-10 rounded-full">

            <p class="text-sm text-gray-600">
                <a href="/perfiles/{{$post->user->id}}">
                Autor/a: {{ $post->user->name }} · 
                {{ $usuari->post->count() }} publicacions a Postify
                </a>
                
            </p>
        </div>

        <!-- IMAGEN -->
        <img src="{{ $post->url }}" class="w-full h-96 object-cover rounded mb-6">

        <!-- BODY -->
        <p class="text-gray-700 leading-relaxed mb-6">
            {{ $post->body }}
        </p>

        <hr class="my-6">

        <!-- FOOTER INFO -->
        <div class="text-sm text-gray-500 space-y-2">

            <p>
                <strong>Data:</strong> {{ $post->created_at->format('d/m/Y') }}
            </p>

            <p>
                <strong>Categoria:</strong> 
                <span class="text-blue-500">
                    {{ $post->category->name }}
                </span>
            </p>

            <p>
                <strong>Tags:</strong>
                @foreach($post->tags as $tag)
                    <span class="text-blue-500 mr-2">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </p>

        </div>

        </div>
        
    </section>
</x-app>