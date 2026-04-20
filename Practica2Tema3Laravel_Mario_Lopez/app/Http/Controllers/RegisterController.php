<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Mail\VerifyEmailMail;
use App\Models\Category;
use App\Models\Connection;
use App\Models\PendingUser;
use App\Models\Post;
use App\Models\Post_Tag;
use App\Models\Tag;
use App\Models\User;
use App\Models\Likes;
use App\Models\Tipus_User;
use Dom\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function inici(){
        if(!Auth::user()){
            return view('inici', [
                'carouselImages' => $this->buildCarouselImages(),
            ]);
        } else {
        $user = Auth::user();
        $postsu = Post::where('user_id', $user->id)->get();
        $posts = Post::where('status', 1)->get();
        $categories = Category::all();
        return redirect('/posts')->with([
        'visca' => 'Benvingut de nou!',
        'usuari' => $user->id,
        'postsu' => $postsu
         ], ['posts' => $posts, 'categorias' => $categories]);
        }
    }

    public function onboarding()
    {
        return view('registres.onboarding');
    }

    public function registre(){
        $tipus = Tipus_User::all();
        return view('registres.registres', ['tipus' => $tipus]);
    }

     public function login()
    {
        return view('registres.login');
    }

    /**
     * Gestiona el formulari de registre.
     * NO crea l'usuari directament: desa les dades temporalment
     * i envia un email de verificació.
     */
    public function store(RegisterUserRequest $request)
    {
        // Desar imatge de perfil
        $n      = $request->name;
        $imatge = $n . '_' . $request->file('fitx')->getClientOriginalName();
        $path   = $request->file('fitx')->storeAs('imatges', $imatge, 'public');

        // Generar token pla (60 chars); a la BD es guarda el hash
        $plainToken = Str::random(60);
        $tokenHash  = hash('sha256', $plainToken);

        // Si ja existeix un pending_user amb aquest email, el sobreescrivim
        PendingUser::where('email', $request->email)->delete();

        PendingUser::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'ruta'          => $path,
            'tipus_user_id' => $request->input('tipus_type') === 'empresa'
                                    ? null
                                    : $request->tipus_user_id,
            'tipus_type'    => $request->input('tipus_type') === 'empresa'
                                    ? 'empresa'
                                    : null,
            'token'      => $tokenHash,
            'expires_at' => Carbon::now()->addMinutes(60),
        ]);

        // Enviar email de verificació
        Mail::to($request->email)->send(new VerifyEmailMail($plainToken, $request->email));

        return redirect()->route('verification.notice')
            ->with('pending_email', $request->email)
            ->with('status', 'T\'hem enviat un correu de confirmació. Revisa la teva safata d\'entrada.');
    }

    /**
     * Mostra la pàgina d'espera "Revisa el teu correu".
     */
    public function showVerifyEmailNotice()
    {
        return view('registres.verify-email');
    }

    /**
     * Verifica el token de l'URL i, si és vàlid, crea l'usuari definitiu.
     *
     * GET /verify-email/{token}?email=xxx
     */
    public function verifyEmail(Request $request, string $token)
    {
        $email     = $request->query('email');
        $tokenHash = hash('sha256', $token);

        $pending = PendingUser::where('token', $tokenHash)
            ->where('email', $email)
            ->first();

        if (! $pending) {
            return redirect()->route('register')
                ->withErrors(['email' => 'L\'enllaç de verificació no és vàlid.']);
        }

        if ($pending->isExpired()) {
            $pending->delete();
            return redirect()->route('register')
                ->withErrors(['email' => 'L\'enllaç de verificació ha caducat. Torna a registrar-te.']);
        }

        // Determinar el tipus d'usuari
        if ($pending->tipus_type === 'empresa') {
            $tipus = Tipus_User::firstOrCreate(['name' => 'empresa']);
        } else {
            $tipus = Tipus_User::findOrFail($pending->tipus_user_id);
        }

        // Crear l'usuari definitiu
        $user = new User();
        $user->name          = $pending->name;
        $user->email         = $pending->email;
        $user->password      = $pending->password; // ja està hashejat
        $user->ruta          = $pending->ruta;
        $user->Tipus_User()->associate($tipus);
        $user->save();

        // Eliminar el registre temporal
        $pending->delete();

        // Iniciar sessió automàticament
        Auth::login($user);
        $request->session()->regenerate();

        if ($user->Tipus_User && $user->Tipus_User->name === 'empresa') {
            return redirect('/feedempresas')->with('visca', 'Compte activat! Benvingut/da, ' . $user->name . '!');
        }

        return redirect('/posts')->with('visca', 'Compte activat! Benvingut/da, ' . $user->name . '!');
    }

    /**
     * Reenvia l'email de verificació per a un pending_user existent.
     *
     * POST /resend-verification
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $pending = PendingUser::where('email', $request->email)->first();

        if (! $pending) {
            // No revelar si l'email existeix o no
            return redirect()->route('verification.notice')
                ->with('pending_email', $request->email)
                ->with('status', 'Si el compte existeix, t\'hem enviat un nou correu de verificació.');
        }

        // Renovar token i expiració
        $plainToken = Str::random(60);
        $pending->token      = hash('sha256', $plainToken);
        $pending->expires_at = Carbon::now()->addMinutes(60);
        $pending->save();

        Mail::to($pending->email)->send(new VerifyEmailMail($plainToken, $pending->email));

        return redirect()->route('verification.notice')
            ->with('pending_email', $request->email)
            ->with('status', 'Nou correu de verificació enviat. Revisa la teva safata d\'entrada.');
    }

    public function llogat(Request $request)
    {
        $atributs = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($atributs, $remember)) {
            throw ValidationException::withMessages(
                ['email' => 'Les seves credencials no han pogut ser verificades']
            );
        }

        $user = Auth::user();

        $request->session()->regenerate();

        $postsu = Post::where('user_id', $user->id)->get();
        if($postsu === null) return 0;
        $posts = Post::where('status', 1)->get();
        $categories = Category::all();

        if ($user->Tipus_User && $user->Tipus_User->name === 'empresa') {
            return redirect('/feedempresas')->with('visca', 'Benvingut de nou, ' . $user->name . '!');
        }

        return redirect('/posts')->with([
        'visca' => 'Benvingut de nou!',
        'usuari' => $user->id,
        'postsu' => $postsu
         ], ['posts' => $posts, 'categorias' => $categories]);
    }

    public function feedempresas()
    {
        $user      = Auth::user();
        $empresaTipus = Tipus_User::where('name', 'empresa')->first();
        $companies = $empresaTipus
            ? User::where('tipus_user_id', $empresaTipus->id)
                  ->where('id', '!=', $user->id)
                  ->orderByDesc('followers')
                  ->get()
            : collect();

        return view('feedempresas', [
            'companies' => $companies,
            'authUser'  => $user,
        ]);
    }

    public function posts()
    {
        $sort  = request('sort', 'recent');
        $query = Post::where('status', 1);

        $query = match($sort) {
            'oldest'   => $query->oldest(),
            'likes'    => $query->withCount('likes')->orderByDesc('likes_count'),
            'comments' => $query->withCount('coments')->orderByDesc('coments_count'),
            'visits'   => $query->orderByDesc('visits'),
            default    => $query->latest(),
        };

        $posts      = $query->get();
        $categories = Category::all();
        $user       = Auth::user();
        $postsu     = Post::where('user_id', $user->id)->get();

        // Empresa users for the sidebar block (exclude self)
        $empresaTipus = Tipus_User::where('name', 'empresa')->first();
        $companies = $empresaTipus
            ? User::where('tipus_user_id', $empresaTipus->id)
                  ->where('id', '!=', $user->id)
                  ->orderByDesc('followers')
                  ->take(5)
                  ->get()
            : collect();

        return view('posts', [
            'posts'        => $posts,
            'categorias'   => $categories,
            'postsu'       => $postsu,
            'suggested'    => $this->suggestedUsers(),
            'companies'    => $companies,
            'currentSort'  => $sort,
        ]);
    }

    public function destroy(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        return redirect('/inici')->with('visca', 'Vagi be company!');
    }

    public function filtrarcategorias(Request $req){
        $nom = $req->input("category");
        $categoria = Category::where('name' , $nom)->get(); 
        if($categoria->isEmpty()){
            session()->flash("missatge", "Mostrant totes les categories");
            return redirect('/posts')->with( [
                'posts'      => [],
                'categorias' => Category::all(),
            ]);
        }
        $posts = Post::where(['category_id'=> $categoria[0]->id, 'status' => 1])->get();
        $categories = Category::all();
        $user = Auth::user();
        $postsu = Post::where('user_id', $user->id)->get();
        return view('posts', [
            'posts'     => $posts,
            'categorias' => $categories,
            'postsu'    => $postsu,
            'suggested' => $this->suggestedUsers(),
        ]);
    }

    public function filtrarpernom(Request $req)
{
    $nom1 = $req->input("nom");
    $usuari = User::where('name', $nom1)->get();
    if ($usuari->isEmpty()) {  
        $posts = [];           
        $categories = Category::all();

        session()->flash("missatge", "Usuari Inexistent");
        return redirect('/posts')->with( [
            'posts'      => $posts,
            'categorias' => $categories,
        ]);
    }
    $posts = Post::where(['user_id' => $usuari->first()->id,
                          'status' => 1])->get();
    $categories = Category::all();

    $user = Auth::user();
    $postsu = Post::where('user_id', $user->id)->get();

    return view('posts', [
        'posts'      => $posts,
        'categorias' => $categories,
        'postsu'     => $postsu,
        'suggested'  => $this->suggestedUsers(),
    ]);
}

    /**
     * Returns 4 random users with whom the authenticated user has no
     * active or pending connection (fresh shuffle on every request).
     */
    private function suggestedUsers(): \Illuminate\Support\Collection
    {
        $authId = Auth::id();

        // Collect all user IDs already connected or with a pending request
        $excludedIds = Connection::where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                  ->orWhere('receiver_id', $authId);
            })
            ->whereIn('status', ['accepted', 'pending'])
            ->get()
            ->flatMap(fn($c) => [$c->sender_id, $c->receiver_id])
            ->push($authId)
            ->unique()
            ->values()
            ->toArray();

        // Fetch all eligible users and shuffle at PHP level — guarantees
        // a fresh random order on every request regardless of DB caching.
        $empresaTipus = Tipus_User::where('name', 'empresa')->first();

        $query = User::whereNotIn('id', $excludedIds);
        if ($empresaTipus) {
            $query->where('tipus_user_id', '!=', $empresaTipus->id);
        }

        return $query->get()->shuffle()->take(4);
    }

    public function vistaperfil(){
        /** @var \App\Models\User $usuari */
        $usuari = Auth::user();
        $posts = Post::where('user_id', $usuari->id)->paginate(5);
        $tipus_user = Tipus_User::find($usuari->tipus_user_id);
        $categorias = Category::all();
        $tags = Tag::all();
        $likedPosts = Post::whereHas('likes', function ($q) use ($usuari) {
            $q->where('user_id', $usuari->id);
        })->withCount('likes')->get();
        $myComents = \App\Models\Coments::where('user_id', $usuari->id)
            ->with('post')
            ->latest()
            ->get()
            ->filter(fn($c) => $c->post)
            ->values();
        $skills = $usuari->skills()->orderBy('created_at')->get();
        return view('perfil', ['tags' => $tags, 'categorias' => $categorias, 'usuari' => $usuari, 'posts' => $posts, 'tipus_user' => $tipus_user, 'likedPosts' => $likedPosts, 'myComents' => $myComents, 'skills' => $skills]);
    }

    /**
     * Builds the carousel image pool:
     *  1. Real images from storage/public/imatges and storage/public/imatges_posts
     *  2. Picsum fallback seeds (always included so carousel is never empty)
     * Returns a shuffled array of absolute URLs — different order on every request.
     */
    private function buildCarouselImages(): array
    {
        $disk   = Storage::disk('public');
        $stored = [];

        foreach (['imatges', 'imatges_posts'] as $folder) {
            if ($disk->exists($folder)) {
                foreach ($disk->files($folder) as $file) {
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                        $stored[] = asset('storage/' . $file);
                    }
                }
            }
        }

        // Picsum fallback: pool of 50 seeds, shuffle and pick 20
        $seeds = range(1, 50);
        shuffle($seeds);
        $picsum = array_map(
            fn(int $s) => "https://picsum.photos/seed/{$s}/800/500",
            array_slice($seeds, 0, 20)
        );

        $all = array_merge($stored, $picsum);
        shuffle($all);

        return $all;
    }
}