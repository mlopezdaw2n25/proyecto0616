<?php

namespace App\Http\Controllers;

use App\Models\Coments;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function store(Post $post, Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        $comment = new Coments();
        $comment->body    = $request->body;
        $comment->user_id = $user->id;
        $comment->post_id = $post->id;
        $comment->save();

        // Notify the post owner (skip if the user comments on their own post)
        if ($post->user_id !== $user->id) {
            Notification::create([
                'user_id'   => $post->user_id,
                'sender_id' => $user->id,
                'type'      => 'comment',
                'post_id'   => $post->id,
                'read'      => false,
            ]);
        }

        return response()->json([
            'comment' => [
                'id'         => $comment->id,
                'body'       => $comment->body,
                'created_at' => $comment->created_at->format('d/m/Y'),
                'user'       => [
                    'name'   => $user->name,
                    'ruta'   => $user->ruta,
                    'id'     => $user->id
                ],
            ],
        ]);
    }

    public function destroy(Coments $comment)
    {
        $user = Auth::user();

        // Only the comment author or the post owner can delete
        if ($user->id !== $comment->user_id && $user->id !== $comment->post->user_id) {
            abort(403, 'No tens permís per eliminar aquest comentari.');
        }

        $comment->delete();

        return response()->json(['deleted' => true]);
    }
}
