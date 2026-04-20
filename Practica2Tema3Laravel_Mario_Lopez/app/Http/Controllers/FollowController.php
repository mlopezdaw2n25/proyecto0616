<?php

namespace App\Http\Controllers;

use App\Models\CompanyFollow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // POST /follow/{company}
    public function follow(User $company)
    {
        $user = Auth::user();

        // Only non-empresa users can follow empreses
        if ($user->id === $company->id) {
            return back();
        }

        $alreadyFollowing = CompanyFollow::where('follower_id', $user->id)
            ->where('company_id', $company->id)
            ->exists();

        if (! $alreadyFollowing) {
            CompanyFollow::create([
                'follower_id' => $user->id,
                'company_id'  => $company->id,
            ]);

            // Increment denormalised counter
            $company->increment('followers');
        }

        return back();
    }

    // POST /unfollow/{company}
    public function unfollow(User $company)
    {
        $user = Auth::user();

        $deleted = CompanyFollow::where('follower_id', $user->id)
            ->where('company_id', $company->id)
            ->delete();

        if ($deleted) {
            // Decrement, but never below 0
            $company->decrement('followers', 1);
            if ($company->followers < 0) {
                $company->update(['followers' => 0]);
            }
        }

        return back();
    }
}
