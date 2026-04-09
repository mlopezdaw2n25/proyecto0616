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
            <!-- Profile dropdown -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="focus:outline-none" aria-label="Menú d'usuari">
                    <img src="/storage/{{ Auth::user()->ruta }}" alt="Avatar {{ Auth::user()->name }}"
                         class="w-10 h-10 rounded-full ring-2 transition-all duration-200"
                         :class="open ? 'ring-blue-500' : 'ring-transparent hover:ring-blue-300'">
                </button>

                <!-- Dropdown panel -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                     x-cloak
                     class="absolute right-0 top-14 w-60 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">

                    <!-- User info header -->
                    <div class="flex items-center gap-3 px-4 py-4 border-b border-gray-100 bg-gray-50">
                        <img src="/storage/{{ Auth::user()->ruta }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <!-- Menu items -->
                    <ul class="py-2">
                        <!-- Perfil -->
                        <li>
                            <a href="/perfil" @click="open = false"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                    </svg>
                                </span>
                                <span class="font-medium">Perfil</span>
                            </a>
                        </li>

                        <!-- Publicar -->
                        <li>
                            <a href="/publicacion" @click="open = false"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                <span class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-7 13v-4H8v-2h4V6l5 5-5 5z"/>
                                    </svg>
                                </span>
                                <span class="font-medium">Publicar</span>
                            </a>
                        </li>

                        <!-- Configuració -->
                        <li>
                            <button type="button" disabled
                               class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-400 cursor-not-allowed">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65a.5.5 0 0 0 .12-.64l-2-3.46a.5.5 0 0 0-.61-.22l-2.49 1a7.3 7.3 0 0 0-1.68-.98l-.38-2.65A.49.49 0 0 0 14 2h-4a.49.49 0 0 0-.49.42l-.38 2.65a7.3 7.3 0 0 0-1.68.98l-2.49-1a.49.49 0 0 0-.61.22l-2 3.46a.48.48 0 0 0 .12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98L2.51 14.63a.5.5 0 0 0-.12.64l2 3.46a.5.5 0 0 0 .61.22l2.49-1c.52.38 1.08.7 1.68.98l.38 2.65c.07.24.29.42.55.42h4c.26 0 .48-.18.49-.42l.38-2.65a7.3 7.3 0 0 0 1.68-.98l2.49 1a.49.49 0 0 0 .61-.22l2-3.46a.48.48 0 0 0-.12-.64l-2.11-1.65zM12 15.5A3.5 3.5 0 1 1 12 8.5a3.5 3.5 0 0 1 0 7z"/>
                                    </svg>
                                </span>
                                <span class="font-medium">Configuració</span>
                                <span class="ml-auto text-[10px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded-full">Aviat</span>
                            </button>
                        </li>

                        <!-- Notificacions -->
                        <li>
                            <button type="button" disabled
                               class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-400 cursor-not-allowed">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 22a2 2 0 0 0 2-2h-4a2 2 0 0 0 2 2zm6-6V11a6 6 0 0 0-5-5.91V4a1 1 0 1 0-2 0v1.09A6 6 0 0 0 6 11v5l-2 2v1h16v-1l-2-2z"/>
                                    </svg>
                                </span>
                                <span class="font-medium">Notificacions</span>
                                <span class="ml-auto text-[10px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded-full">Aviat</span>
                            </button>
                        </li>

                        <!-- Divider -->
                        <li class="my-1 border-t border-gray-100"></li>

                        <!-- Cerrar sesión -->
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                   class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <span class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8v-2H4V5z"/>
                                        </svg>
                                    </span>
                                    <span class="font-medium">Tancar sessió</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- End profile dropdown -->
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
