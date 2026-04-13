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
        $authId = Auth::id();

        if ($authId === $user->id) {
            return back();
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
