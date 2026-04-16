<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->detach($user->id);
            $liked = false;
        } else {
            $post->likes()->attach($user->id);
            $liked = true;

            // Notify the post owner (skip if the user likes their own post)
            if ($post->user_id !== $user->id) {
                Notification::create([
                    'user_id'   => $post->user_id,
                    'sender_id' => $user->id,
                    'type'      => 'like',
                    'post_id'   => $post->id,
                    'read'      => false,
                ]);
            }
        }

        return response()->json([
            'liked' => $liked,
            'count' => $post->likes()->count(),
        ]);
    }
}
