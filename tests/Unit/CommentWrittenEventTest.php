<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Events\CommentWritten;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Event;

class CommentWrittenEventTest extends TestCase
{
    public function testCommentWrittenEventIsFired()
    {
        Event::fake();

        // Create a User instance without saving to the database
        $user = new User(['id' => 1, 'name' => 'john', 'email' => 'john@email.com', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);

        // Create a Comment instance without saving to the database
        $comment = new Comment(['id' => 1, 'name' => 'sample comment']);

        // Fire the CommentWritten event with the mock instances
        event(new CommentWritten($comment));

        // Assert that the CommentWritten event is dispatched
        Event::assertDispatched(CommentWritten::class, function ($event) use ($comment) {
            return $event->comment->id === $comment->id;
        });
    }
}
