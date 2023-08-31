<?php

use Tests\TestCase;
use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class LessonWatchedEventTest extends TestCase
{
    public function testLessonWatchedEventIsFired()
    {
        Event::fake();

        // Create a User instance without saving to the database
        $user = new User(['id' => 1, 'name' => 'john', 'email' => 'john@email.com', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);

        // Create a Lesson instance without saving to the database
        $lesson = new Lesson(['id' => 1, 'title' => 'sample lesson title']);

        event(new LessonWatched($lesson, $user)); // Pass both lesson and user

        Event::assertDispatched(LessonWatched::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }
}
