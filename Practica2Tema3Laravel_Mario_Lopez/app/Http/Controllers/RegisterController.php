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

class RegisterController extends Controller
{
    public function inici(){
        return view('inici');
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
        $tipus = Tipus_User::findOrFail($request->tipus_user_id);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->Tipus_User()->associate($tipus);
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

        if (!Auth::attempt($atributs)) {
            throw ValidationException::withMessages(
                ['email' => 'Les seves credencials no han pogut ser verificades']
            );
        }

        $user = Auth::user();

        $request->session()->regenerate();

        $posts = Post::where('status', 1)->get();
        $categories = Category::all();
        return redirect('/posts')->with([
        'visca' => 'Benvingut de nou!',
        'usuari' => $user->id 
         ], ['posts' => $posts], ['categorias' => $categories]);
    }

    public function posts()
    {
        $posts = Post::where('status', 1)->get();
        $categories = Category::all();
        return view('posts', ['posts' => $posts], ['categorias' => $categories]);
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
        $posts = Post::where(['category_id'=> $categoria[0]->id, 'status' => 1])->get();
        $categories = Category::all();
        return view('posts', ['posts' => $posts], ['categorias' => $categories]);
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

    return view('posts', [
        'posts'      => $posts,
        'categorias' => $categories,
    ]);
}

    public function vistaperfil(){
        $usuari = Auth::user();
        $posts = Post::where('user_id', $usuari->id)->paginate(5);
        $tipus_user = Tipus_User::find($usuari->tipus_user_id);
        $categorias = Category::all();
        $tags = Tag::all(); 
        return view('perfil', ['tags' => $tags, 'categorias' => $categorias, 'usuari' => $usuari, 'posts' => $posts, 'tipus_user' => $tipus_user]);
    }
}