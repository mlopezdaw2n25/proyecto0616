<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BioController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        // Only empresa users can have a bio
        if (!($user->Tipus_User && $user->Tipus_User->name === 'empresa')) {
            abort(403);
        }

        $user->bio = $request->input('bio');
        $user->save();

        return back()->with('missatge', 'Biografia guardada correctament.');
    }
}
