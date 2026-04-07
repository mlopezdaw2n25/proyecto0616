<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Category;
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
use Illuminate\Support\Str;

class PostsController extends Controller
{
    public function crearpost(){
        $categorias = Category::all();
        $tags = Tag::all(); 
        return view('crearpost', ['tags' => $tags, 'categorias' => $categorias]);
    } 

    public function formpost(Request $req){
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
    $post->url = $req->input('imatge');
    $post->user_id = $usuari->id;
    $post->save();
    $tags = $req->input('tags', []);
    foreach ($tags as $value) {
        $post_tag = new Post_Tag();
        $post_tag->post_id = $post->id;
        $post_tag->tag_id = $value;
        $post_tag->save();
    }
    $posts = Post::where('status', 1)->get();
    $categories = Category::all();

    $postsu = Post::where('user_id', $usuari->id)->get();
    return view('posts', ['posts' => $posts, 'categorias' => $categories, 'postsu' => $postsu]);
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

        $tipus_user = Tipus_User::find($usuaripost->tipus_user_id);
        $usuarios = User::all();
        $categorias = Category::all();
        $tags = Tag::all();
        $posts->load('coments.user');
        return view('vistaprevia', ['tags' => $tags, 'categorias' => $categorias, 'usuari' => $usuari, 'post' => $posts, 'usuarios' => $usuarios, 'tipus_user' => $tipus_user, 'usuaripost' => $usuaripost]);
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
    return view('altresperfils', ['tags' => $tags, 'categorias' => $categorias, 'usuari' => $usuari, 'posts' => $posts, 'tipus_user' => $tipus_user]);
}

public function editarperfil($id){
    $usuari = User::find($id);
    return view('editarperfil', ['usuari' => $usuari]);
}

public function posteditarperfil($id ,Request $req){
    $req->validate([
        'name' => 'required|string|max:50',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
    ]);

    $usuari = User::find($id);
    $usuari->name = $req->input('name');
    $usuari->email = $req->input('email');
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
}
