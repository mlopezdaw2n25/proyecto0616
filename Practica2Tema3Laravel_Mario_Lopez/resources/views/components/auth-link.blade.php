<div class="mt-8 md:mt-0 flex items-center justify-end space-x-3 bg-slate-800 hover:bg-slate-700 transition-all duration-200 p-4 rounded-lg">
    @auth
    @if(request()->is('posts'))
    <a href="/publicacion"
       class="px-4 py-2 text-xs font-semibold uppercase rounded-full bg-blue-600 text-white shadow-sm transition-all duration-200 
              hover:bg-blue-700 hover:shadow-md hover:-translate-y-0.5">
        Crear publicacion
    </a>

    <a href="/perfil"
       class="px-4 py-2 text-xs font-semibold uppercase rounded-full border border-blue-600 text-blue-600 bg-white shadow-sm transition-all duration-200 
              hover:bg-blue-50 hover:text-blue-700 hover:border-blue-700 hover:-translate-y-0.5">
        Perfil {{ auth()->user()->name }}
    </a>

    <form id="logout-form" method="POST" action="/logout" 
          class="ml-2">
        @csrf
        <button type="submit"
                class="px-4 py-2 text-xs font-semibold uppercase rounded-full bg-red-500 text-white shadow-sm transition-all duration-200 
                       hover:bg-red-600 hover:shadow-md hover:-translate-y-0.5">
            Sortir
        </button>
    </form>
    @elseif(request()->is('publicacion'))
     <a href="/posts"
       class="px-4 py-2 text-xs font-semibold uppercase rounded-full bg-blue-600 text-white shadow-sm transition-all duration-200 
              hover:bg-blue-700 hover:shadow-md hover:-translate-y-0.5">
        Posts
    </a>
    <a href="/perfil"
       class="px-4 py-2 text-xs font-semibold uppercase rounded-full border border-blue-600 text-blue-600 bg-white shadow-sm transition-all duration-200 
              hover:bg-blue-50 hover:text-blue-700 hover:border-blue-700 hover:-translate-y-0.5">
        Perfil {{ auth()->user()->name }}
    </a>

    <form id="logout-form" method="POST" action="/logout" 
          class="ml-2">
        @csrf
        <button type="submit"
                class="px-4 py-2 text-xs font-semibold uppercase rounded-full bg-red-500 text-white shadow-sm transition-all duration-200 
                       hover:bg-red-600 hover:shadow-md hover:-translate-y-0.5">
            Sortir
        </button>
    </form>
    @elseif(request()->is('perfil'))
    <a href="/posts"
       class="px-4 py-2 text-xs font-semibold uppercase rounded-full bg-blue-600 text-white shadow-sm transition-all duration-200 
              hover:bg-blue-700 hover:shadow-md hover:-translate-y-0.5">
        Posts
    </a>
    <form id="logout-form" method="POST" action="/logout" 
          class="ml-2">
        @csrf
        <button type="submit"
                class="px-4 py-2 text-xs font-semibold uppercase rounded-full bg-red-500 text-white shadow-sm transition-all duration-200 
                       hover:bg-red-600 hover:shadow-md hover:-translate-y-0.5">
            Sortir
        </button>
    </form>
    @else()
    <a href="/posts"
       class="px-4 py-2 text-xs font-semibold uppercase rounded-full bg-blue-600 text-white shadow-sm transition-all duration-200 
              hover:bg-blue-700 hover:shadow-md hover:-translate-y-0.5">
        Posts
    </a>
    <a href="/perfil"
       class="px-4 py-2 text-xs font-semibold uppercase rounded-full border border-blue-600 text-blue-600 bg-white shadow-sm transition-all duration-200 
              hover:bg-blue-50 hover:text-blue-700 hover:border-blue-700 hover:-translate-y-0.5">
        Perfil {{ auth()->user()->name }}
    </a>

    <form id="logout-form" method="POST" action="/logout" 
          class="ml-2">
        @csrf
        <button type="submit"
                class="px-4 py-2 text-xs font-semibold uppercase rounded-full bg-red-500 text-white shadow-sm transition-all duration-200 
                       hover:bg-red-600 hover:shadow-md hover:-translate-y-0.5">
            Sortir
        </button>
    </form>
    @endif
    @endauth
    @guest
    <a href="/registre"
       class="px-4 py-2 text-xs font-semibold uppercase rounded-full bg-green-600 text-white shadow-sm transition-all duration-200 
              hover:bg-green-700 hover:shadow-md hover:-translate-y-0.5">
        Registre
    </a>

    <a href="/login"
       class="px-4 py-2 text-xs font-semibold uppercase rounded-full border border-gray-700 text-gray-800 bg-white shadow-sm transition-all duration-200 
              hover:bg-gray-900 hover:text-white hover:-translate-y-0.5">
        Login
    </a>
    @endguest 
</div>
