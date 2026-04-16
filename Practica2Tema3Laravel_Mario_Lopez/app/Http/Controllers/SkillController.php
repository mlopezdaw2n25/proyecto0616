<?php

namespace App\Http\Controllers;

use App\Models\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->skills()->create([
            'name' => trim($request->name),
        ]);

        return back();
    }

    public function destroy(UserSkill $skill)
    {
        abort_if(Auth::id() !== $skill->user_id, 403);

        $skill->delete();

        return back();
    }
}
