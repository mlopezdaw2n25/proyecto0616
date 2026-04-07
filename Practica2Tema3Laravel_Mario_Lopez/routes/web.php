<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/inici');
});
//prueba
Route::controller(RegisterController::class)->group(function () {
    Route::get('/inici', 'inici');
    Route::get('/registre', 'registre');
    Route::get('/login', 'login')->middleware('guest')->name('login');
    Route::post('/logout', 'destroy')->middleware('auth')->name('logout');
    Route::post('/registre', 'store');
    Route::post('/login', 'llogat')->middleware('guest');
    Route::get('/posts', 'posts')->middleware('auth');
    Route::post('/posts/p', 'filtrarcategorias')->middleware('auth');
    Route::post('/posts', 'filtrarpernom')->middleware('auth');
    Route::get('/perfil', 'vistaperfil')->middleware('auth');
});

Route::controller(PostsController::class)->group(function () {
    Route::get('/publicacion', 'crearpost')->middleware('auth');
    Route::post('/publicacion', 'formpost')->middleware('auth');
    Route::get('/vistaprevia/{id}', 'vistaprevia')->middleware('auth');
    Route::Post('/editarpos/{id}', 'mostrarpost')->middleware('auth');
    Route::Post('/editarpost/{id}', 'posteditar')->middleware('auth');
    Route::get('/perfiles/{id}', 'perfiles')->middleware('auth');
    Route::get('/editarperfil/{id}', 'editarperfil')->middleware('auth');
    Route::Post('/editarperfil/{id}', 'posteditarperfil')->middleware('auth');
    Route::post('/borrarpost/{id}', 'eliminarpost')->middleware('auth');
});

Route::post('/posts/{post}/like', [LikeController::class, 'toggleLike'])->middleware('auth');
Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->middleware('auth');