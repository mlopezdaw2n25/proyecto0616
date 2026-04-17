<x-app title="Configuració">

{{-- ─────────────────────────────────────────────────────────────────────────
     Configuració de l'usuari – panel estil SaaS
     Alpine.js gestiona la secció activa i les confirmacions inline
──────────────────────────────────────────────────────────────────────────── --}}

@php
    $activeTab = session('settings_tab', 'compte');
@endphp

<div
    x-data="{
        tab: '{{ $activeTab }}',
        sidebarOpen: false,
        confirmDelete: false,
        confirmText: ''
    }"
    class="min-h-screen"
>

    {{-- ── Flash message ─────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="fixed top-6 right-6 z-50 flex items-center gap-3 bg-green-600 text-white text-sm font-medium px-5 py-3 rounded-xl shadow-lg"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Layout wrapper ─────────────────────────────────────────────────── --}}
    <div class="mx-auto max-w-6xl px-4 py-8">

        {{-- Page heading --}}
        <div class="mb-8 flex items-center gap-4">
            <a href="/perfil"
               class="flex items-center gap-1.5 text-xs text-slate-500 hover:text-blue-600 transition font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Tornar
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Configuració</h1>
                <p class="text-sm text-slate-500 mt-0.5">Gestiona el teu compte, privacitat i preferències.</p>
            </div>
        </div>

        {{-- ── Two-column layout ─────────────────────────────────────────── --}}
        <div class="flex gap-6 items-start">

            {{-- ── SIDEBAR ─────────────────────────────────────────────── --}}
            <aside class="hidden md:flex md:flex-col w-56 shrink-0 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-24 self-start">

                {{-- User mini card --}}
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <img src="/storage/{{ $user->ruta }}" alt="{{ $user->name }}"
                         class="w-9 h-9 rounded-full object-cover ring-2 ring-white shadow-sm flex-shrink-0">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- Nav items --}}
                <nav class="py-2">
                    @foreach([
                        ['key' => 'compte',       'label' => 'Compte',              'icon' => 'M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z'],
                        ['key' => 'visibilitat',  'label' => 'Visibilitat',          'icon' => 'M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z'],
                        ['key' => 'accessibilitat','label'=> 'Accessibilitat',       'icon' => 'M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z'],
                        ['key' => 'privacitat',   'label' => 'Privacitat i seguretat','icon'=> 'M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4.3 6 2.68V11c0 3.78-2.52 7.32-6 8.93-3.48-1.61-6-5.15-6-8.93V7.98l6-2.68z'],
                        ['key' => 'notificacions','label' => 'Notificacions',        'icon' => 'M12 22a2 2 0 0 0 2-2h-4a2 2 0 0 0 2 2zm6-6V11a6 6 0 0 0-5-5.91V4a1 1 0 1 0-2 0v1.09A6 6 0 0 0 6 11v5l-2 2v1h16v-1l-2-2z'],
                    ] as $item)
                    <button
                        @click="tab = '{{ $item['key'] }}'"
                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium transition-colors"
                        :class="tab === '{{ $item['key'] }}'
                            ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 flex-shrink-0"
                             :class="tab === '{{ $item['key'] }}' ? 'text-blue-600' : 'text-slate-400'"
                             viewBox="0 0 24 24" fill="currentColor">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </button>
                    @endforeach
                </nav>
            </aside>

            {{-- ── MOBILE sidebar toggle ──────────────────────────────── --}}
            <div class="md:hidden w-full mb-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="flex items-center gap-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl px-4 py-2.5 shadow-sm w-full justify-between">
                    <span x-text="{
                        compte: 'Compte',
                        visibilitat: 'Visibilitat',
                        accessibilitat: 'Accessibilitat',
                        privacitat: 'Privacitat i seguretat',
                        notificacions: 'Notificacions'
                    }[tab]"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform" :class="sidebarOpen ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 10l5 5 5-5H7z"/>
                    </svg>
                </button>
                <div x-show="sidebarOpen" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 bg-white border border-slate-200 rounded-xl shadow-md overflow-hidden">
                    @foreach([
                        ['key' => 'compte',        'label' => 'Compte'],
                        ['key' => 'visibilitat',   'label' => 'Visibilitat'],
                        ['key' => 'accessibilitat','label' => 'Accessibilitat'],
                        ['key' => 'privacitat',    'label' => 'Privacitat i seguretat'],
                        ['key' => 'notificacions', 'label' => 'Notificacions'],
                    ] as $item)
                    <button @click="tab = '{{ $item['key'] }}'; sidebarOpen = false"
                            class="w-full text-left px-4 py-3 text-sm font-medium transition-colors"
                            :class="tab === '{{ $item['key'] }}' ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-50'">
                        {{ $item['label'] }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- ── CONTENT PANEL ────────────────────────────────────────── --}}
            <div class="flex-1 min-w-0">

                {{-- ════════════════════════════════════════════════════════
                     TAB: COMPTE
                ════════════════════════════════════════════════════════ --}}
                <div x-show="tab === 'compte'" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="space-y-5">

                        {{-- Card: Dades del perfil --}}
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-900">Dades del perfil</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Modifica nom, foto i email des de la pàgina d'edició.</p>
                            </div>
                            <div class="px-6 py-5 flex items-center gap-4">
                                <img src="/storage/{{ $user->ruta }}" alt="{{ $user->name }}"
                                     class="w-14 h-14 rounded-full object-cover ring-2 ring-slate-100 shadow flex-shrink-0">
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                    <p class="text-sm text-slate-500 truncate">{{ $user->email }}</p>
                                </div>
                                <a href="/editarperfil/{{ $user->id }}"
                                   class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                    Editar perfil
                                </a>
                            </div>
                        </div>

                        {{-- Card: Canviar contrasenya --}}
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-900">Canviar contrasenya</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Usa una contrasenya forta d'almenys 8 caràcters.</p>
                            </div>
                            <form method="POST" action="/configuracion/password" class="px-6 py-5 space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5" for="current_password">
                                        Contrasenya actual
                                    </label>
                                    <input
                                        id="current_password"
                                        name="current_password"
                                        type="password"
                                        autocomplete="current-password"
                                        class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('current_password') border-red-400 bg-red-50 @enderror"
                                        placeholder="••••••••"
                                    >
                                    @error('current_password')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5" for="password">
                                        Nova contrasenya
                                    </label>
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        autocomplete="new-password"
                                        class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-400 bg-red-50 @enderror"
                                        placeholder="••••••••"
                                    >
                                    @error('password')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5" for="password_confirmation">
                                        Confirmar nova contrasenya
                                    </label>
                                    <input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        autocomplete="new-password"
                                        class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="••••••••"
                                    >
                                </div>

                                <div class="flex justify-end pt-1">
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/>
                                        </svg>
                                        Actualitzar contrasenya
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Card: Zona de perill – eliminar compte --}}
                        <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-red-100 bg-red-50">
                                <h2 class="text-base font-bold text-red-700 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                                    </svg>
                                    Zona de perill
                                </h2>
                                <p class="text-xs text-red-600 mt-0.5">Aquesta acció és irreversible. El compte i totes les dades s'eliminaran permanentment.</p>
                            </div>

                            <div class="px-6 py-5">
                                <button
                                    @click="confirmDelete = !confirmDelete"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-300 text-red-600 text-sm font-semibold hover:bg-red-50 transition-colors"
                                    type="button"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                    </svg>
                                    Eliminar compte
                                </button>

                                {{-- Confirmation form --}}
                                <div x-show="confirmDelete" x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="mt-4 p-4 bg-red-50 rounded-xl border border-red-200">
                                    <p class="text-sm text-red-700 font-semibold mb-3">
                                        Per confirmar, escriu <strong class="font-mono bg-red-100 px-1 rounded">ELIMINAR</strong> al camp:
                                    </p>
                                    <form method="POST" action="/configuracion/delete-account">
                                        @csrf
                                        @method('DELETE')
                                        <input
                                            name="confirm_delete"
                                            type="text"
                                            x-model="confirmText"
                                            placeholder="ELIMINAR"
                                            class="w-full rounded-lg border border-red-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-red-300 focus:outline-none focus:ring-2 focus:ring-red-400 transition mb-3"
                                        >
                                        @error('confirm_delete')
                                            <p class="text-xs text-red-600 mb-3">{{ $message }}</p>
                                        @enderror
                                        <div class="flex gap-2">
                                            <button
                                                type="submit"
                                                :disabled="confirmText !== 'ELIMINAR'"
                                                class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition disabled:opacity-40 disabled:cursor-not-allowed"
                                            >
                                                Eliminar compte definitivament
                                            </button>
                                            <button type="button" @click="confirmDelete = false; confirmText = ''"
                                                    class="px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition">
                                                Cancel·lar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>{{-- /tab compte --}}


                {{-- ════════════════════════════════════════════════════════
                     TAB: VISIBILITAT
                ════════════════════════════════════════════════════════ --}}
                <div x-show="tab === 'visibilitat'" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <form method="POST" action="/configuracion/preferences" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_tab" value="visibilitat">

                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-900">Aparença</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Personalitza com veus l'aplicació.</p>
                            </div>
                            <div class="px-6 py-5 space-y-5">

                                {{-- Dark mode toggle --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Mode fosc</p>
                                        <p class="text-xs text-slate-500 mt-0.5">Canvia al tema fosc per reduir la fatiga visual.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="dark_mode" value="1" class="sr-only peer"
                                               {{ $settings->dark_mode ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 transition-colors peer-focus:ring-2 peer-focus:ring-blue-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                                    </label>
                                </div>

                                <hr class="border-slate-100">

                                {{-- Language selector --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">
                                        Idioma
                                    </label>
                                    <select name="language"
                                            class="w-full max-w-xs rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                                        <option value="ca" {{ $settings->language === 'ca' ? 'selected' : '' }}>🌐 Català</option>
                                        <option value="es" {{ $settings->language === 'es' ? 'selected' : '' }}>🌐 Español</option>
                                        <option value="en" {{ $settings->language === 'en' ? 'selected' : '' }}>🌐 English</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7l-4-4zm-5 16a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm3-10H5V5h10v4z"/>
                                </svg>
                                Guardar canvis
                            </button>
                        </div>
                    </form>
                </div>{{-- /tab visibilitat --}}


                {{-- ════════════════════════════════════════════════════════
                     TAB: ACCESSIBILITAT
                ════════════════════════════════════════════════════════ --}}
                <div x-show="tab === 'accessibilitat'" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <form method="POST" action="/configuracion/preferences" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_tab" value="accessibilitat">
                        {{-- Pass visibility fields unchanged so they are not wiped --}}
                        <input type="hidden" name="dark_mode"  value="{{ $settings->dark_mode ? '1' : '0' }}">
                        <input type="hidden" name="language"   value="{{ $settings->language }}">

                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-900">Accessibilitat</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Adapta la interfície a les teves necessitats visuals.</p>
                            </div>
                            <div class="px-6 py-5 space-y-5">

                                {{-- Font size --}}
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 mb-3">Mida del text</p>
                                    <div class="flex gap-3 flex-wrap">
                                        @foreach(['small' => 'Petit', 'medium' => 'Mitjà', 'large' => 'Gran'] as $val => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="font_size" value="{{ $val }}" class="sr-only peer"
                                                   {{ $settings->font_size === $val ? 'checked' : '' }}>
                                            <span class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-700
                                                         peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-colors select-none">
                                                {{ $label }}
                                            </span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                <hr class="border-slate-100">

                                {{-- Colorblind mode --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Mode daltònic</p>
                                        <p class="text-xs text-slate-500 mt-0.5">Ajusta la paleta de colors per a dalt​onisme.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="colorblind_mode" value="1" class="sr-only peer"
                                               {{ $settings->colorblind_mode ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 transition-colors peer-focus:ring-2 peer-focus:ring-blue-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7l-4-4zm-5 16a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm3-10H5V5h10v4z"/>
                                </svg>
                                Guardar canvis
                            </button>
                        </div>
                    </form>
                </div>{{-- /tab accessibilitat --}}


                {{-- ════════════════════════════════════════════════════════
                     TAB: PRIVACITAT I SEGURETAT
                ════════════════════════════════════════════════════════ --}}
                <div x-show="tab === 'privacitat'" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <form method="POST" action="/configuracion/privacy" class="space-y-5">
                        @csrf

                        {{-- Card: Compte privat --}}
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4.3 6 2.68V11c0 3.78-2.52 7.32-6 8.93-3.48-1.61-6-5.15-6-8.93V7.98l6-2.68z"/>
                                </svg>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900">Compte privat</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Quan el compte és privat, els usuaris no connectats només veuran el teu nom i foto.</p>
                                </div>
                            </div>
                            <div class="px-6 py-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Activar compte privat</p>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Les publicacions, CV i aptituds quedaran ocults per als usuaris que no siguin connexions acceptades.
                                        </p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input type="checkbox" name="is_private" value="1" class="sr-only peer"
                                               {{ $user->is_private ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-red-500 transition-colors peer-focus:ring-2 peer-focus:ring-red-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                                    </label>
                                </div>
                                {{-- Visual state indicator --}}
                                <div class="mt-3 flex items-center gap-2 text-xs font-medium
                                    {{ $user->is_private ? 'text-red-600' : 'text-green-600' }}">
                                    @if($user->is_private)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/>
                                        </svg>
                                        El teu compte és privat
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                        El teu compte és públic
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-900">Privacitat</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Controla quines dades poden veure altres usuaris.</p>
                            </div>
                            <div class="px-6 py-5 space-y-5">

                                @foreach([
                                    ['name' => 'show_friends',  'label' => 'Mostrar amistats',   'desc' => 'Altres usuaris podran veure la teva llista d\'amics.', 'val' => $settings->show_friends],
                                    ['name' => 'show_likes',    'label' => 'Mostrar likes',       'desc' => 'Altres usuaris podran veure les publicacions que has marcat.', 'val' => $settings->show_likes],
                                    ['name' => 'show_comments', 'label' => 'Mostrar comentaris',  'desc' => 'Altres usuaris podran veure els teus comentaris a les publicacions.', 'val' => $settings->show_comments],
                                ] as $i => $option)
                                    @if($i > 0)<hr class="border-slate-100">@endif
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">{{ $option['label'] }}</p>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $option['desc'] }}</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                            <input type="checkbox" name="{{ $option['name'] }}" value="1" class="sr-only peer"
                                                   {{ $option['val'] ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 transition-colors peer-focus:ring-2 peer-focus:ring-blue-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                                        </label>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7l-4-4zm-5 16a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm3-10H5V5h10v4z"/>
                                </svg>
                                Guardar canvis
                            </button>
                        </div>
                    </form>
                </div>{{-- /tab privacitat --}}


                {{-- ════════════════════════════════════════════════════════
                     TAB: NOTIFICACIONS
                ════════════════════════════════════════════════════════ --}}
                <div x-show="tab === 'notificacions'" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <form method="POST" action="/configuracion/notifications" class="space-y-5">
                        @csrf

                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-900">Notificacions</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Controla quan i com reps avisos de l'aplicació.</p>
                            </div>
                            <div class="px-6 py-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Activar notificacions</p>
                                        <p class="text-xs text-slate-500 mt-0.5">Rebràs avisos de sol·licituds de connexió i activitat rellevant.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input type="checkbox" name="notifications_enabled" value="1" class="sr-only peer"
                                               {{ $settings->notifications_enabled ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 transition-colors peer-focus:ring-2 peer-focus:ring-blue-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7l-4-4zm-5 16a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm3-10H5V5h10v4z"/>
                                </svg>
                                Guardar canvis
                            </button>
                        </div>
                    </form>
                </div>{{-- /tab notificacions --}}

            </div>{{-- /content panel --}}
        </div>{{-- /two-column layout --}}
    </div>{{-- /max-w wrapper --}}
</div>{{-- /alpine root --}}

</x-app>
