<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Events\CommentWritten;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            // 'lesson_id' => 'required|exists:lessons,id',
            'content' => 'required|string',
        ]);

        // Create the comment
        $comment = Comment::create([
            'user_id' => auth()->id(),
            'body' => $request->input('content'),
        ]);

        // Trigger the CommentWritten event
        event(new CommentWritten($comment));


        // event(new BadgeUnlocked($comment));


        return response()->json(['message' => 'Comment created successfully']);
    }
}
