<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Photo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Photo $photo): JsonResponse
    {
        $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $photo->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        // Load user for the response
        $comment->load('user');

        // Create notification for the photo owner
        if ($photo->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $photo->user_id,
                'actor_id' => Auth::id(),
                'type' => 'comment',
                'notifiable_type' => Photo::class,
                'notifiable_id' => $photo->id,
                'data' => [
                    'message' => 'mengomentari postingan Anda.',
                    'comment_body' => $comment->body,
                ]
            ]);
        }

        return response()->json([
            'message' => 'Komentar berhasil ditambahkan!',
            'comment' => $comment,
            'user' => $comment->user,
        ]);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        // Ensure user can only delete their own comments
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Komentar dihapus!']);
    }
}
