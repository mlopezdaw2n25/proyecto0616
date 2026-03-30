<x-app>
    <section class="px-6 py-8 bg-white">
        <main class="max-w-lg mx-auto mt-10">
            <div class="border border-gray-200 p-6 rounded-xl bg-white">
                <h1 class="text-center font-bold text-xl">Crea un nou Post!</h1>

                <form method="POST" action="/editarpost/{{$post['id']}}" class="mt-10">
                    @csrf
                    @method('post')
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="">
                            Nom: 
                        </label>

                        <input class="border border-gray-200 p-2 w-full rounded" name="nom" id=""  value="{{ $post['name'] }}">
                        @error('nom')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="">
                            Body: 
                        </label>

                        <input class="border border-gray-200 p-2 w-full rounded" name="body" id="" value="{{ $post['body'] }}">
                        @error('body')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="">
                            Status 1 o 2: 
                        </label>

                        <input class="border border-gray-200 p-2 w-full rounded" name="status" id="" value="{{ $post['status'] }}">
                        @error('status')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="">
                            Imatge: 
                        </label>

                        <input class="border border-gray-200 p-2 w-full rounded" name="imatge" id="" value="{{ $post['url'] }}">
                        @error('imatge')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
    <label class="block mb-2 uppercase font-bold text-xs text-gray-700">
        Categoria: 
    </label>
    
   <select name="category" class="w-full h-14 px-4 border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:outline-none"> 
    <option value="" disabled>Selecciona una categoria</option>
    @foreach($categorias as $cat)
        <option value="{{ $cat->id }}" 
            @selected(old('category', $post->category_id) == $cat->id)>
            {{ $cat->name }}
        </option>
    @endforeach
</select>
</div>
                    <div class="mt-6">
    <label class="block mb-2 uppercase font-bold text-xs text-gray-700">
        Tags:
    </label>

    <div class="space-y-2">
        @foreach($tags as $tag)
            <label class="flex items-center space-x-2 border border-gray-200 p-2 rounded">
                <input 
                    type="checkbox" 
                    name="tags[]" 
                    value="{{ $tag->id }}"
                    class="rounded border-gray-300 text-blue-500 focus:ring-blue-500"
                    {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                    @checked(
                        ($post->tags->contains($tag->id))
                    )
                >
                <span class="text-sm text-gray-700">{{ $tag->name }}</span>
            </label>
        @endforeach
    </div>

    @error('tags')
        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
    @enderror
</div>
                    <div class="mt-6">
                        <button type="submit"
                            class="bg-blue-500 text-white uppercase font-semibold text-xs py-2 px-10 rounded-2xl hover:bg-blue-600">
                            Editar
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </section>
</x-app>