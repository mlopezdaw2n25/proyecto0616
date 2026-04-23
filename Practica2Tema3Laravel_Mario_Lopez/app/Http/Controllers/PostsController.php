<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Category;
use App\Models\Connection;
use App\Models\Post;
use App\Models\Post_Tag;
use App\Models\Tag;
use App\Models\User;
use App\Models\Tipus_User;
use Dom\Document;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostsController extends Controller
{
    public function crearpost(){
        $categorias = Category::all();
        $tags = Tag::all(); 
        return view('crearpost', ['tags' => $tags, 'categorias' => $categorias]);
    } 

    public function formpost(Request $req){
    $req->validate([
        'nom'      => 'required|string|max:255',
        'body'     => 'required|string',
        'category' => 'required|exists:categories,name',
        'tags'     => 'required|array|min:1',
        'tags.*'   => 'exists:tags,id',
        'image'    => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:4096',
    ], [
        'category.required' => 'La categoria és obligatòria.',
        'category.exists'   => 'La categoria seleccionada no és vàlida.',
        'tags.required'     => 'Has de seleccionar almenys un tag.',
        'tags.min'          => 'Has de seleccionar almenys un tag.',
        'tags.*.exists'     => 'Un dels tags seleccionats no és vàlid.',
        'image.mimes'       => 'La imatge ha de ser JPG, PNG, GIF o WEBP.',
        'image.max'         => 'La imatge no pot superar els 4 MB.',
    ]);

    // Gestió de la imatge pujada
    $imagePath = null;
    if ($req->hasFile('image')) {
        $file   = $req->file('image');
        $usuari = Auth::user();
        $nom    = Str::slug($req->input('nom'));
        $fitxer = $nom . '_' . $usuari->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $imagePath = $file->storeAs('imatges_posts', $fitxer, 'public');
    }

    $n = $req->input('category');
    $categoria = Category::where('name', $n)->get(); 
    $usuari = Auth::user();
    $post = New Post();
    $post->name = $req->input('nom');
    $post->slug = Str::slug($req->nom);
    $post->body = $req->input('body');
    $post->extract = substr($req->input('body'), 100);
    $post->status = 1;
    $post->category_id = $categoria[0]->id;
    $post->url = $imagePath;
    $post->user_id = $usuari->id;
    $post->save();
    $tags = $req->input('tags', []);
    foreach ($tags as $value) {
        $post_tag = new Post_Tag();
        $post_tag->post_id = $post->id;
        $post_tag->tag_id = $value;
        $post_tag->save();
    }
    $tipusUser = Auth::user()->Tipus_User;
    $isEmpresa = $tipusUser && $tipusUser->name === 'empresa';

    return redirect($isEmpresa ? '/feedempresas' : '/posts');
}

public function vistaprevia($id){
        $usuari = Auth::user();
        $posts = Post::find($id);

        if (!$posts) {
            abort(404, 'Publicación no encontrada.');
        }

        $usuaripost = $posts->user;
        if (!$usuaripost) {
            abort(404, 'Usuario de la publicación no encontrado.');
        }

        if ($posts->status == 2 && $usuari->id !== $posts->user_id) {
            abort(403, 'No tienes permiso para acceder a esta publicación oculta.');
        }

        $posts->increment('visits');

        $tipus_user   = Tipus_User::find($usuaripost->tipus_user_id);
        $empresaTipus = Tipus_User::where('name', 'empresa')->first();

        $isEmpresaPost = $empresaTipus && $usuaripost->tipus_user_id === $empresaTipus->id;

        $companies = $empresaTipus
            ? User::where('tipus_user_id', $empresaTipus->id)
                  ->where('id', '!=', $usuari->id)
                  ->orderByDesc('followers')
                  ->get()
            : collect();

        $usuarios = $isEmpresaPost
            ? collect()
            : ($empresaTipus
                ? User::where('tipus_user_id', '!=', $empresaTipus->id)
                      ->where('id', '!=', $usuari->id)
                      ->inRandomOrder()
                      ->limit(8)
                      ->get()
                : User::where('id', '!=', $usuari->id)->inRandomOrder()->limit(8)->get());

        $categorias = Category::all();
        $tags = Tag::all();
        $posts->load('coments.user');
        return view('vistaprevia', [
            'tags'          => $tags,
            'categorias'    => $categorias,
            'usuari'        => $usuari,
            'post'          => $posts,
            'usuarios'      => $usuarios,
            'companies'     => $companies,
            'isEmpresaPost' => $isEmpresaPost,
            'tipus_user'    => $tipus_user,
            'usuaripost'    => $usuaripost,
        ]);
}


public function posteditar($id, Request $req){
    $post = Post::find($id);
    $post->status = 2;
    $post->save(); 
    $posts = Post::where('status', 1)->get();
    $categories = Category::all();
    return redirect('perfil')->with(['posts' => $posts], ['categorias' => $categories]);
}

public function mostrarpost($id, Request $req){
    $post = Post::find($id);
    $post->status = 1;
    $post->save(); 
    $posts = Post::where('status', 1)->get();
    $categories = Category::all();
    return redirect('perfil')->with(['posts' => $posts], ['categorias' => $categories]);
}

public function perfiles($id){
    $usuari = User::find($id);
    $posts = Post::where('user_id', $id)->get();
    $categorias = Category::all();
    $tags = Tag::all();
    $tipus_user = Tipus_User::find($usuari->tipus_user_id);
    $skills = $usuari->skills()->orderBy('created_at')->get();
    $isEmpresaPerfil = $tipus_user && $tipus_user->name === 'empresa';
    $jobOffers = $isEmpresaPerfil
        ? \App\Models\JobOffer::where('user_id', $usuari->id)->latest()->get()
        : collect();

    // Treballo per: the empresa that has this alumno in their circle (non-empresa profile only)
    $employerCompany = null;
    if (!$isEmpresaPerfil) {
        $circleConn = \App\Models\Connection::where('receiver_id', $usuari->id)
            ->where('status', 'accepted')
            ->whereHas('sender', fn($q) => $q->whereHas('Tipus_User', fn($t) => $t->where('name', 'empresa')))
            ->with('sender')
            ->first();
        $employerCompany = $circleConn?->sender;
    }

    // Cercle de treball: accepted non-empresa connections (empresa profiles only)
    $circleMembers = collect();
    if ($isEmpresaPerfil) {
        $sentIds     = $usuari->sentRequests()->where('status', 'accepted')->pluck('receiver_id');
        $receivedIds = $usuari->receivedRequests()->where('status', 'accepted')->pluck('sender_id');
        $circleMembers = \App\Models\User::whereIn('id', $sentIds->merge($receivedIds))
            ->whereHas('Tipus_User', fn($q) => $q->where('name', '!=', 'empresa'))
            ->get();
    }

    return view('altresperfils', ['tags' => $tags, 'categorias' => $categorias, 'usuari' => $usuari, 'posts' => $posts, 'tipus_user' => $tipus_user, 'skills' => $skills, 'isEmpresaPerfil' => $isEmpresaPerfil, 'jobOffers' => $jobOffers, 'employerCompany' => $employerCompany, 'circleMembers' => $circleMembers]);
}

public function editarperfil($id){
    $usuari = User::find($id);
    return view('editarperfil', ['usuari' => $usuari]);
}

public function posteditarperfil($id ,Request $req){
    $req->validate([
        'name' => 'required|string|max:50',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        'fitx'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $usuari = User::find($id);
    $usuari->name = $req->input('name');
    $usuari->email = $req->input('email');

    if ($req->hasFile('fitx') && $req->file('fitx')->isValid()) {
        // Elimina la imatge anterior del disc
        if ($usuari->ruta && Storage::disk('public')->exists($usuari->ruta)) {
            Storage::disk('public')->delete($usuari->ruta);
        }

        // Desa la nova imatge
        $fitx  = $req->file('fitx');
        $nom   = $usuari->name . '_' . $fitx->getClientOriginalName();
        $path  = $fitx->storeAs('imatges', $nom, 'public');
        $usuari->ruta = $path;
    }

    $usuari->save();

    $postsu = Post::where('user_id', $usuari->id)->get();

    return redirect('/posts')->with(['visca' => 'Perfil Actualitzat!'], ['postsu' => $postsu]);
}

public function eliminarpost($id){
    $post_tag = Post_Tag::where('post_id', $id)->get();
    foreach ($post_tag as $p) {
        $p->delete();
    }
    $post = Post::find($id);
    $post->delete();


    return redirect('/perfil')->with("visca", 'Post eliminat Correctament!');
}

    /**
     * Returns 4 random users with whom the authenticated user has no
     * active or pending connection (fresh shuffle on every request).
     */
    private function suggestedUsers(): \Illuminate\Support\Collection
    {
        $authId = Auth::id();

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

        return User::whereNotIn('id', $excludedIds)
            ->get()
            ->shuffle()
            ->take(4);
    }
}
