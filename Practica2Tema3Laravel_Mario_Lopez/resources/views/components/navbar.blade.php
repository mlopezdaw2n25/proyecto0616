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
            <a href="/feedempresas" class="hover:text-blue-600 transition">Feed d'empresa</a>
            <a href="/perfil" class="hover:text-blue-600 transition">Perfil</a>
        </div>

        <!-- Right: Auth actions -->
        <div class="flex items-center gap-3">
            <!-- Profile dropdown -->
            <div class="relative" x-data="{ open: false, notifOpen: false }" @click.outside="open = false; notifOpen = false">
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

                        <!-- Feed d'empresa -->
                        <li>
                            <a href="/feedempresas" @click="open = false"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20 6h-3V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-9-2h2v2h-2V4zm-6 4h14v3H4V8zm0 9v-4h14v4H4z"/>
                                    </svg>
                                </span>
                                <span class="font-medium">Feed d'empresa</span>
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
                            <a href="/configuracion" @click="open = false"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                <span class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65a.5.5 0 0 0 .12-.64l-2-3.46a.5.5 0 0 0-.61-.22l-2.49 1a7.3 7.3 0 0 0-1.68-.98l-.38-2.65A.49.49 0 0 0 14 2h-4a.49.49 0 0 0-.49.42l-.38 2.65a7.3 7.3 0 0 0-1.68.98l-2.49-1a.49.49 0 0 0-.61.22l-2 3.46a.48.48 0 0 0 .12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98L2.51 14.63a.5.5 0 0 0-.12.64l2 3.46a.5.5 0 0 0 .61.22l2.49-1c.52.38 1.08.7 1.68.98l.38 2.65c.07.24.29.42.55.42h4c.26 0 .48-.18.49-.42l.38-2.65a7.3 7.3 0 0 0 1.68-.98l2.49 1a.49.49 0 0 0 .61-.22l2-3.46a.48.48 0 0 0-.12-.64l-2.11-1.65zM12 15.5A3.5 3.5 0 1 1 12 8.5a3.5 3.5 0 0 1 0 7z"/>
                                    </svg>
                                </span>
                                <span class="font-medium">Configuració</span>
                            </a>
                        </li>

                        <!-- Notificacions -->
                        @php
                            $navSettings = Auth::user()->getOrCreateSettings();

                            // Connection requests
                            $navPending = $navSettings->notifications_enabled
                                ? Auth::user()->pendingReceivedRequests()->with('sender')->latest()->get()
                                : collect();

                            // Like / comment notifications (unread first, max 30)
                            $navNotifs = Auth::user()->notifications()
                                ->with(['sender', 'post'])
                                ->orderBy('read', 'asc')
                                ->orderByDesc('created_at')
                                ->take(30)
                                ->get();

                            // Badge = unread notifications + pending connection requests
                            $totalUnread = Auth::user()->notifications()->where('read', false)->count()
                                         + $navPending->count();
                        @endphp
                        <li>
                            <button @click="notifOpen = !notifOpen"
                               class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                <span class="relative w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 22a2 2 0 0 0 2-2h-4a2 2 0 0 0 2 2zm6-6V11a6 6 0 0 0-5-5.91V4a1 1 0 1 0-2 0v1.09A6 6 0 0 0 6 11v5l-2 2v1h16v-1l-2-2z"/>
                                    </svg>
                                    @if($totalUnread > 0)
                                        <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center leading-none">
                                            {{ $totalUnread > 99 ? '99+' : $totalUnread }}
                                        </span>
                                    @endif
                                </span>
                                <span class="font-medium">Notificacions</span>
                                @if($totalUnread > 0)
                                    <span class="ml-auto text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full font-semibold">{{ $totalUnread > 99 ? '99+' : $totalUnread }}</span>
                                @endif
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

                {{-- ── Notification panel (sibling of dropdown, outside overflow-hidden) ── --}}
                <div x-show="notifOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                     x-cloak
                     class="absolute right-[16rem] top-14 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 z-[60] flex flex-col overflow-hidden"
                     style="max-height: 32rem;">

                    {{-- Panel header --}}
                    <div class="flex items-center justify-between px-4 py-4 border-b border-gray-100 bg-gray-50 flex-shrink-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold text-gray-900">Notificacions</p>
                            @if($totalUnread > 0)
                                <span class="w-5 h-5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">{{ $totalUnread > 99 ? '99+' : $totalUnread }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if($totalUnread > 0)
                                <form method="POST" action="/notifications/mark-all-read">
                                    @csrf
                                    <button type="submit" class="text-[11px] text-blue-600 hover:text-blue-800 font-medium transition">
                                        Marcar tot com llegit
                                    </button>
                                </form>
                            @endif
                            <button @click="notifOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Scrollable body --}}
                    <div class="flex-1 overflow-y-auto">
                        @php $hasAny = $navPending->isNotEmpty() || $navNotifs->isNotEmpty(); @endphp

                        @if(!$hasAny)
                            <div class="flex flex-col items-center justify-center h-48 gap-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-200" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 22a2 2 0 0 0 2-2h-4a2 2 0 0 0 2 2zm6-6V11a6 6 0 0 0-5-5.91V4a1 1 0 1 0-2 0v1.09A6 6 0 0 0 6 11v5l-2 2v1h16v-1l-2-2z"/>
                                </svg>
                                <p class="text-sm">No hi ha notificacions</p>
                            </div>
                        @else
                            <ul class="divide-y divide-gray-100">

                                {{-- ── Connection requests ── --}}
                                @foreach($navPending as $conn)
                                    <li class="px-5 py-4 hover:bg-gray-50 transition">
                                        <div class="flex items-center gap-3">
                                            <a href="/perfiles/{{ $conn->sender->id }}" @click="notifOpen = false; open = false" class="flex-shrink-0">
                                                <img src="/storage/{{ $conn->sender->ruta }}" alt="{{ $conn->sender->name }}"
                                                     class="w-11 h-11 rounded-full object-cover ring-2 ring-gray-100">
                                            </a>
                                            <div class="flex-1 min-w-0">
                                                <a href="/perfiles/{{ $conn->sender->id }}" @click="notifOpen = false; open = false"
                                                   class="text-sm font-semibold text-gray-900 hover:text-blue-600 block truncate">
                                                    {{ $conn->sender->name }}
                                                </a>
                                                <p class="text-xs text-gray-400 mt-0.5">Vol connectar amb tu</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 mt-3 ml-14">
                                            <form method="POST" action="/connect/{{ $conn->id }}/accept">
                                                @csrf
                                                <button type="submit"
                                                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                                    Acceptar
                                                </button>
                                            </form>
                                            <form method="POST" action="/connect/{{ $conn->id }}/reject">
                                                @csrf
                                                <button type="submit"
                                                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-gray-100 text-gray-600 rounded-lg hover:bg-red-50 hover:text-red-600 border border-gray-200 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                                    Rebutjar
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach

                                {{-- ── Like / Comment notifications ── --}}
                                @foreach($navNotifs as $notif)
                                    <li class="{{ $notif->read ? 'hover:bg-gray-50' : 'bg-blue-50/70 hover:bg-blue-100/60' }} transition">
                                        <a href="/notifications/{{ $notif->id }}/read"
                                           @click="notifOpen = false; open = false"
                                           class="flex items-center gap-3 px-5 py-4">
                                            <img src="/storage/{{ $notif->sender->ruta }}"
                                                 alt="{{ $notif->sender->name }}"
                                                 class="w-11 h-11 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm {{ $notif->read ? 'font-medium' : 'font-semibold' }} text-gray-900 truncate">{{ $notif->sender->name }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5 leading-snug">
                                                    @if($notif->type === 'like')
                                                        ❤️ ha donat like a la teva publicació
                                                    @else
                                                        💬 ha comentat la teva publicació
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                            </div>
                                            @if(!$notif->read)
                                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0"></span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        @endif
                    </div>
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
