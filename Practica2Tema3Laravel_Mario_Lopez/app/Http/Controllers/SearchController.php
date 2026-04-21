<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Live user search – returns JSON for the navbar autocomplete.
     * Max 8 results, ordered: exact match first, then alphabetical.
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $users = User::where('name', 'like', '%' . $q . '%')
            ->where('id', '!=', auth()->id())
            ->with('Tipus_User')
            ->orderByRaw("CASE WHEN name LIKE ? THEN 0 ELSE 1 END", [$q . '%'])
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->map(fn($u) => [
                'id'        => $u->id,
                'name'      => $u->name,
                'ruta'      => $u->ruta,
                'tipus'     => $u->Tipus_User->name ?? null,
                'isEmpresa' => $u->Tipus_User && $u->Tipus_User->name === 'empresa',
                'url'       => '/perfiles/' . $u->id,
            ]);

        return response()->json($users);
    }
}
