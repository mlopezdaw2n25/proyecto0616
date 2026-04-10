<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Temps de validesa del token en minuts.
     */
    private const TOKEN_EXPIRY_MINUTES = 60;

    // ─────────────────────────────────────────────────────────────
    // STEP 1 – Formulari per sol·licitar l'enllaç
    // ─────────────────────────────────────────────────────────────

    public function showForgotForm(): View
    {
        return view('registres.forgot-password');
    }

    /**
     * Valida l'email, genera un token segur, el guarda hashejat i
     * envia el correu. SEMPRE retorna el mateix missatge genèric per
     * evitar l'enumeració d'emails (OWASP A07).
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = $request->input('email');
        $user  = User::where('email', $email)->first();

        if ($user) {
            // Elimina tokens antics per evitar acumulació
            DB::table('password_resets')->where('email', $email)->delete();

            // Token de 64 caràcters aleatoris criptogràficament segurs
            $plainToken  = Str::random(64);
            $hashedToken = Hash::make($plainToken);

            DB::table('password_resets')->insert([
                'email'      => $email,
                'token'      => $hashedToken,
                'created_at' => Carbon::now(),
            ]);

            Mail::to($email)->send(new ResetPasswordMail($plainToken, $email));
        }

        // Resposta genèrica: no revela si l'email existeix o no
        return redirect()->route('login')
            ->with('status', 'Si el correu existeix al sistema, rebràs ben aviat un enllaç per restablir la contrasenya.');
    }

    // ─────────────────────────────────────────────────────────────
    // STEP 2 – Formulari per introduir la nova contrasenya
    // ─────────────────────────────────────────────────────────────

    public function showResetForm(Request $request, string $token): View|RedirectResponse
    {
        $email = $request->query('email');

        if (! $email || ! $this->resolveValidToken($email, $token)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'L\'enllaç no és vàlid o ha caducat. Sol·licita un de nou.']);
        }

        return view('registres.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // STEP 3 – Processar el canvi de contrasenya
    // ─────────────────────────────────────────────────────────────

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email'                 => ['required', 'email', 'max:255'],
            'token'                 => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);

        $email = $request->input('email');
        $token = $request->input('token');

        // Doble verificació: token vàlid + no caducat
        if (! $this->resolveValidToken($email, $token)) {
            return back()
                ->withInput(['email' => $email])
                ->withErrors(['token' => 'L\'enllaç no és vàlid, ha caducat o ja s\'ha utilitzat. Sol·licita un de nou.']);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'No s\'ha trobat cap compte associat a aquest correu.']);
        }

        // Actualitza la contrasenya amb bcrypt
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Invalida el token immediatament (ús únic)
        DB::table('password_resets')->where('email', $email)->delete();

        return redirect()->route('login')
            ->with('success', 'Contrasenya actualitzada correctament. Ja pots iniciar sessió.');
    }

    // ─────────────────────────────────────────────────────────────
    // MÈTODE PRIVAT D'UTILITAT
    // ─────────────────────────────────────────────────────────────

    /**
     * Cerca un registre de reset per l'email donat, verifica que el token
     * (text pla) coincideix amb el hash emmagatzemat i que no ha caducat.
     *
     * @return object|null  El registre de la BD o null si no és vàlid.
     */
    private function resolveValidToken(string $email, string $plainToken): ?object
    {
        $record = DB::table('password_resets')
            ->where('email', $email)
            ->first();

        if (! $record) {
            return null;
        }

        // Comprova la caducitat
        if (Carbon::parse($record->created_at)->addMinutes(self::TOKEN_EXPIRY_MINUTES)->isPast()) {
            DB::table('password_resets')->where('email', $email)->delete();
            return null;
        }

        // Verifica el token contra el hash (timing-safe via Hash::check)
        if (! Hash::check($plainToken, $record->token)) {
            return null;
        }

        return $record;
    }
}
