<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // GET /configuracion
    // ──────────────────────────────────────────────────────────────────────────
    public function index()
    {
        /** @var User $user */
        $user     = Auth::user();
        $settings = $user->getOrCreateSettings();

        return view('configuracion', compact('user', 'settings'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // POST /configuracion/password  – canviar contrasenya
    // ──────────────────────────────────────────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'La contrasenya actual és obligatòria.',
            'password.required'         => 'La nova contrasenya és obligatòria.',
            'password.confirmed'        => 'Les contrasenyes no coincideixen.',
            'password.min'              => 'La contrasenya ha de tenir almenys 8 caràcters.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withInput()
                ->withErrors(['current_password' => 'La contrasenya actual no és correcta.'])
                ->with('settings_tab', 'compte');
        }

        // The User model has a 'hashed' cast on password, so pass plain text
        $user->update(['password' => $request->password]);

        return redirect('/configuracion')
            ->with('success', 'Contrasenya actualitzada correctament.')
            ->with('settings_tab', 'compte');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // POST /configuracion/preferences  – visibilitat + accessibilitat
    // ──────────────────────────────────────────────────────────────────────────
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'language'  => ['nullable', 'string', 'in:ca,es,en'],
            'font_size' => ['nullable', 'string', 'in:small,medium,large'],
        ]);

        /** @var User $user */
        $user     = Auth::user();
        $settings = $user->getOrCreateSettings();

        $settings->update([
            'dark_mode'       => $request->boolean('dark_mode'),
            'language'        => $request->input('language', 'ca'),
            'font_size'       => $request->input('font_size', 'medium'),
            'colorblind_mode' => $request->boolean('colorblind_mode'),
        ]);

        return redirect('/configuracion')
            ->with('success', 'Preferències guardades.')
            ->with('settings_tab', $request->input('_tab', 'visibilitat'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // POST /configuracion/privacy  – privacitat
    // ──────────────────────────────────────────────────────────────────────────
    public function updatePrivacy(Request $request)
    {
        /** @var User $user */
        $user     = Auth::user();
        $settings = $user->getOrCreateSettings();

        // is_private lives on the users table (core account flag)
        $user->update([
            'is_private' => $request->boolean('is_private'),
        ]);

        $settings->update([
            'show_friends'  => $request->boolean('show_friends'),
            'show_likes'    => $request->boolean('show_likes'),
            'show_comments' => $request->boolean('show_comments'),
        ]);

        return redirect('/configuracion')
            ->with('success', 'Privacitat actualitzada.')
            ->with('settings_tab', 'privacitat');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // POST /configuracion/notifications  – notificacions
    // ──────────────────────────────────────────────────────────────────────────
    public function updateNotifications(Request $request)
    {
        /** @var User $user */
        $user     = Auth::user();
        $settings = $user->getOrCreateSettings();

        $settings->update([
            'notifications_enabled' => $request->boolean('notifications_enabled'),
        ]);

        return redirect('/configuracion')
            ->with('success', 'Preferències de notificació guardades.')
            ->with('settings_tab', 'notificacions');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DELETE /configuracion/delete-account  – eliminar compte
    // ──────────────────────────────────────────────────────────────────────────
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_delete' => ['required', 'string', 'in:ELIMINAR'],
        ], [
            'confirm_delete.in' => 'Has d\'escriure ELIMINAR per confirmar.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Eliminar foto de perfil de l'storage si existeix
        if ($user->ruta && Storage::disk('public')->exists($user->ruta)) {
            Storage::disk('public')->delete($user->ruta);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/inici')->with('success', 'El compte ha estat eliminat.');
    }
}
