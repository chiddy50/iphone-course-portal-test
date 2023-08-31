<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Lesson;
use App\Events\CommentWritten;
use App\Events\LessonWatched;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'lesson_id' => 'required',
            // 'content' => 'required|string',
        ]);

        $user = auth()->user();

        // // Create the comment
        // $comment = Comment::create([
        //     'user_id' => auth()->id(),
        //     'body' => $request->input('content'),
        // ]);

        $lesson = Lesson::find($request->lesson_id);

        event(new LessonWatched($lesson, $user));

        return response()->json(['message' => 'Comment created successfully']);
    }
}
