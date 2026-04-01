<nav class="w-full">
    @auth
    <div class="mx-auto w-[95%] md:w-[90%] bg-white rounded-2xl shadow-md px-4 flex items-center justify-between h-16 mt-4 mb-10">
        <!-- Left: Branding -->
        <div class="flex items-center space-x-3">
            <a href="/posts" class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">JM</div>
                <span class="hidden md:inline font-bold text-slate-800">JobsMercé</span>
            </a>
        </div>

        <!-- Center: Nav links -->
        <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-slate-700">
            <a href="/posts" class="hover:text-blue-600 transition">Feed</a>
            <a href="/perfil" class="hover:text-blue-600 transition">Perfil</a>
        </div>

        <!-- Right: Auth actions -->
        <div class="flex items-center gap-3">
            <a href="/publicacion" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full text-sm font-bold transition-colors">
                Publicar
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-full text-sm font-bold transition-colors">
                    Sortir
                </button>
            </form>
            <a href="/perfil" class="hover:text-blue-600 transition">
                <img src="https://i.pravatar.cc/40?u={{ Auth::user()->id }}" alt="Avatar {{ Auth::user()->name }}" class="w-10 h-10 rounded-full">
            </a>
        </div>
    </div>
    @endauth

    @guest
    <div class="mx-auto w-[95%] md:w-[90%] bg-white rounded-2xl shadow-md px-4 flex items-center justify-between h-16 mt-4 mb-10">
        <!-- Left: Branding -->
        <div class="flex items-center space-x-3">
            <a href="/inici" class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">JM</div>
                <span class="hidden md:inline font-bold text-slate-800">JobsMercé</span>
            </a>
        </div>

        <!-- Center: Nav links -->
        <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-slate-700">
            <a href="/login" class="hover:text-blue-600 transition">Feed</a>
            <a href="/login" class="hover:text-blue-600 transition">Perfil</a>
        </div>

        <!-- Right: Auth actions -->
        <div>
            <x-auth-link />
        </div>
    </div>
    @endguest
</nav>
