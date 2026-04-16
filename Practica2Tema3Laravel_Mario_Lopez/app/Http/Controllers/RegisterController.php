<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\Category;
use App\Models\Connection;
use App\Models\Post;
use App\Models\Post_Tag;
use App\Models\Tag;
use App\Models\User;
use App\Models\Likes;
use App\Models\Tipus_User;
use Dom\Document;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function inici(){
        if(!Auth::user()){
        return view('inici');
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

    public function store(RegisterUserRequest $request)
    {
        if ($request->input('tipus_type') === 'empresa') {
            // Empresa: find or create the record to be future-proof
            $tipus = Tipus_User::firstOrCreate(['name' => 'empresa']);
        } else {
            $tipus = Tipus_User::findOrFail($request->tipus_user_id);
        }

        $n = $request->name;
        $imatge = $n . '_' . $request->file('fitx')->getClientOriginalName();
        $path = $request->file('fitx')->storeAs('imatges', $imatge,'public');

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->Tipus_User()->associate($tipus);
        $user->ruta = $path;
        //$user->Tipus_User()->associate($tipus);
        $user->save();


        return redirect('/inici')->with("visca", "Compte creat amb el tipus: " . $tipus->name);
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
        return redirect('/posts')->with([
        'visca' => 'Benvingut de nou!',
        'usuari' => $user->id,
        'postsu' => $postsu
         ], ['posts' => $posts, 'categorias' => $categories]);
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

        return view('posts', [
            'posts'        => $posts,
            'categorias'   => $categories,
            'postsu'       => $postsu,
            'suggested'    => $this->suggestedUsers(),
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
        return User::whereNotIn('id', $excludedIds)
            ->get()
            ->shuffle()
            ->take(4);
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
}