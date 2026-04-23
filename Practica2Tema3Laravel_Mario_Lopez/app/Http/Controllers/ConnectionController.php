<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    // POST /connect/{user}
    public function send(User $user)
    {
        $authId  = Auth::id();
        $authUser = Auth::user();

        if ($authId === $user->id) {
            return back();
        }

        // Circle rule: an alumno can only be in ONE empresa's circle.
        // If auth is empresa and target is non-empresa, check target isn't already
        // in an accepted circle connection with any empresa.
        $authIsEmpresa   = $authUser->Tipus_User && $authUser->Tipus_User->name === 'empresa';
        $targetIsEmpresa = $user->Tipus_User && $user->Tipus_User->name === 'empresa';

        if ($authIsEmpresa && !$targetIsEmpresa) {
            $empresaTipusIds = \App\Models\Tipus_User::where('name', 'empresa')->pluck('id');
            $alreadyInCircle = Connection::where('status', 'accepted')
                ->where(function ($q) use ($user, $empresaTipusIds) {
                    $q->where('receiver_id', $user->id)
                      ->whereHas('sender', fn($u) => $u->whereIn('tipus_user_id', $empresaTipusIds));
                })
                ->exists();

            if ($alreadyInCircle) {
                return back()->with('error', 'Aquest alumne ja pertany al cercle d\'una altra empresa.');
            }
        }

        $existing = Connection::where(function ($q) use ($authId, $user) {
            $q->where('sender_id', $authId)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($authId, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $authId);
        })->first();

        if ($existing) {
            // Re-send a previously cancelled request
            if (in_array($existing->status, ['cancelled', 'rejected']) && $existing->sender_id === $authId) {
                $existing->update(['status' => 'pending']);
            }
            return back();
        }

        Connection::create([
            'sender_id'   => $authId,
            'receiver_id' => $user->id,
            'status'      => 'pending',
        ]);

        return back();
    }

    // POST /connect/{connection}/accept
    public function accept(Connection $connection)
    {
        abort_if(Auth::id() !== $connection->receiver_id, 403);
        $connection->update(['status' => 'accepted']);
        return back();
    }

    // POST /connect/{connection}/reject
    public function reject(Connection $connection)
    {
        abort_if(Auth::id() !== $connection->receiver_id, 403);
        $connection->delete();
        return back();
    }

    // POST /connect/{connection}/cancel
    public function cancel(Connection $connection)
    {
        abort_if(Auth::id() !== $connection->sender_id, 403);
        $connection->delete();
        return back();
    }

    // POST /connect/{connection}/unfriend  — either side can remove a friendship
    public function unfriend(Connection $connection)
    {
        abort_if(
            Auth::id() !== $connection->sender_id && Auth::id() !== $connection->receiver_id,
            403
        );
        $connection->delete();
        return back();
    }
}
