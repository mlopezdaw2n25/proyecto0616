<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/inici');
});
//prueba
Route::controller(RegisterController::class)->group(function () {
    Route::get('/inici', 'inici');
    Route::get('/registre/tipus', 'onboarding');
    Route::get('/registre', 'registre')->name('register');
    Route::get('/login', 'login')->middleware('guest')->name('login');
    Route::post('/logout', 'destroy')->middleware('auth')->name('logout');
    Route::post('/registre', 'store');
    Route::post('/login', 'llogat')->middleware('guest');
    Route::get('/posts', 'posts')->middleware('auth');
    Route::post('/posts/p', 'filtrarcategorias')->middleware('auth');
    Route::post('/posts', 'filtrarpernom')->middleware('auth');
    Route::get('/feedempresas', 'feedempresas')->middleware('auth');
    Route::get('/perfil', 'vistaperfil')->middleware('auth');

    // Verificació d'email
    Route::get('/verify-email',        'showVerifyEmailNotice')->name('verification.notice');
    Route::get('/verify-email/{token}', 'verifyEmail')->name('verification.verify');
    Route::post('/resend-verification', 'resendVerification')->name('verification.resend');
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
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth');

// ─── Follow companies ─────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/follow/{company}',   [FollowController::class, 'follow']);
    Route::post('/unfollow/{company}', [FollowController::class, 'unfollow']);
});

// ─── Notifications ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read']);
    Route::post('/notifications/mark-all-read',      [NotificationController::class, 'markAllRead']);
});

// ─── Skills ────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/skills',           [SkillController::class, 'store']);
    Route::delete('/skills/{skill}', [SkillController::class, 'destroy']);
});

// ─── Connections ──────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/connect/{user}',                [ConnectionController::class, 'send']);
    Route::post('/connect/{connection}/accept',   [ConnectionController::class, 'accept']);
    Route::post('/connect/{connection}/reject',   [ConnectionController::class, 'reject']);
    Route::post('/connect/{connection}/cancel',   [ConnectionController::class, 'cancel']);
    Route::post('/connect/{connection}/unfriend', [ConnectionController::class, 'unfriend']);
});

// ─── CV management ───────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/cv',               [CvController::class, 'store']);
    Route::delete('/cv',             [CvController::class, 'destroy']);
    Route::get('/cv/{userId}/view',  [CvController::class, 'show']);
    Route::get('/cv/{userId}/download', [CvController::class, 'download']);
});

// ─── Settings ─────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/configuracion',                     [SettingsController::class, 'index']);
    Route::post('/configuracion/password',           [SettingsController::class, 'updatePassword']);
    Route::post('/configuracion/preferences',        [SettingsController::class, 'updatePreferences']);
    Route::post('/configuracion/privacy',            [SettingsController::class, 'updatePrivacy']);
    Route::post('/configuracion/notifications',      [SettingsController::class, 'updateNotifications']);
    Route::delete('/configuracion/delete-account',  [SettingsController::class, 'deleteAccount']);
});

// ─────────────────────────────────────────────────────────────
// Password Reset – flux complet, només per a convidats
// Rate limiting: màx. 5 peticions d'enviament per minut i IP
// ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    // Formulari "He oblidat la meva contrasenya"
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])
        ->name('password.request');

    // Processa l'enviament del correu (throttled per evitar spam)
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->middleware('throttle:5,1')
        ->name('password.send');

    // Formulari per introduir la nova contrasenya
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');

    // Processa el canvi de contrasenya
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware('throttle:10,1')
        ->name('password.update');
});