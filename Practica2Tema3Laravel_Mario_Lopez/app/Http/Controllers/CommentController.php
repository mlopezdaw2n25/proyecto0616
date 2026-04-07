<?php

namespace App\Http\Controllers;

use App\Models\Coments;
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

        $comment = new Coments();
        $comment->body = $request->body;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $post->id;
        $comment->save();

        return response()->json([
            'comment' => [
                'id'         => $comment->id,
                'body'       => $comment->body,
                'created_at' => $comment->created_at->format('d/m/Y'),
                'user'       => [
                    'name'   => Auth::user()->name,
                    'avatar' => Auth::user()->avatar,
                    'id'     => Auth::user()->id
                ],
            ],
        ]);
    }
}
