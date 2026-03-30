<nav class="md:flex md:justify-between md:items-center bg-slate-800 hover:bg-slate-700 transition-all duration-200 p-6 md:p-8 rounded-xl shadow-xl relative z-10">
    @auth
    <div>
        <a href="/posts">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/3840px-Instagram_icon.png" 
                 alt="Alumnes" 
                 class="h-8 w-auto md:h-10 hover:scale-105 transition-transform duration-200">
        </a>
    </div>
    <x-auth-link></x-auth-link>
    @else
    @guest
    <div>
        <a href="/">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/3840px-Instagram_icon.png" 
                 alt="Alumnes" 
                 class="h-8 w-auto md:h-10 hover:scale-105 transition-transform duration-200">
        </a>
    </div>
    <x-auth-link></x-auth-link>
    @endguest 
    @endauth
</nav>
