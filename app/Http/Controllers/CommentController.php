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

        $user = auth()->user();

        // // Create the comment
        // $comment = Comment::create([
        //     'user_id' => auth()->id(),
        //     'body' => $request->input('content'),
        // ]);

        for ($i = 11; $i <= 101; $i++) {
            $lesson = Lesson::find($i);
            event(new LessonWatched($lesson, $user));
        }

        // event(new CommentWritten($comment, $user));

        return response()->json(['message' => 'Comment created successfully']);
    }
}
