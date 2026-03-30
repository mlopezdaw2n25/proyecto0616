<div class="flex items-center space-x-3">
    @auth
        <div class="hidden sm:flex items-center space-x-2">
            <a href="/publicacion" class="px-3 py-1 rounded-full bg-primary-500 text-white text-sm">Crear</a>
            <a href="/perfil" class="px-3 py-1 rounded-full border border-slate-200 text-sm">{{ auth()->user()->name }}</a>
        </div>

        <form method="POST" action="/logout" class="ml-2">
            @csrf
            <button type="submit" class="px-3 py-1 rounded-full bg-red-500 text-white text-sm">Sortir</button>
        </form>
    @endauth

    @guest
        <a href="/registre" class="px-3 py-1 rounded-full bg-green-600 text-white text-sm">Registre</a>
        <a href="/login" class="px-3 py-1 rounded-full border border-slate-200 text-sm">Login</a>
    @endguest
</div>
