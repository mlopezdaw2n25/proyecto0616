<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Post_Tag;
use App\Models\Tag;
use App\Models\User;
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
    $post->status = $req->input('status');
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
    return view('posts', ['posts' => $posts], ['categorias' => $categories]);
}

public function vistaprevia($id){
        $usuari = Auth::user();
        $posts = Post::find($id);
        $categorias = Category::all();
        $tags = Tag::all(); 
        return view('vistaprevia', ['tags' => $tags, 'categorias' => $categorias, 'usuari' => $usuari, 'post' => $posts]);
}

public function editpost($id){
        $usuari = Auth::user();
        $posts = Post::find($id);
        $categorias = Category::all();
        $tags = Tag::all(); 
        return view('editarpost', ['tags' => $tags, 'categorias' => $categorias, 'usuari' => $usuari, 'post' => $posts]);
}

public function posteditar($id, Request $req){
    $categoriaId = $req->input('category'); 
    $categoria = Category::find($categoriaId);
    $usuari = Auth::user();
    $post = Post::find($id);
    $post->name = $req->input('nom');
    $post->slug = Str::slug($req->nom);
    $post->body = $req->input('body');
    $post->extract = substr($req->input('body'), 20);
    $post->status = $req->input('status');
    $post->category_id = $categoria->id;
    $post->url = $req->input('imatge');
    $post->user_id = $usuari->id;
    $post->save();
    $post->tags()->sync($req->input('tags', []));
    
    $posts = Post::where('status', 1)->get();
    $categories = Category::all();
    return view('posts', ['posts' => $posts], ['categorias' => $categories]);
}

public function perfiles($id){
    $usuari = User::find($id);
    $posts = Post::where('user_id', $id)->get();
    $categorias = Category::all();
    $tags = Tag::all(); 
    return view('altresperfils', ['tags' => $tags, 'categorias' => $categorias, 'usuari' => $usuari, 'posts' => $posts]);
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

    return redirect('/posts')->with("visca", 'Perfil Actualitzat!');
}

public function eliminarpost($id){
    $post_tag = Post_Tag::where('post_id', $id)->get();
    foreach ($post_tag as $p) {
        $p->delete();
    }
    $post = Post::find($id);
    $post->delete();
    return redirect('/posts')->with("visca", 'Post eliminat Correctament!');
}
}
