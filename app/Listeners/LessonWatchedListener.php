<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserLesson;
use App\Models\Achievement;
use App\Events\AchievementUnlocked;

class LessonWatchedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $lesson = $event->lesson;
        $user = $event->user;

        if(!$user->hasWatched($lesson->title)){
            $user_lesson = UserLesson::create([
                'user_id'     => $user->id,
                'lesson_id'   => $lesson->id
            ]);
        }

        $lesson_watched_count = $user->watched()->count();

        $lesson_achievements_criteria = Achievement::where('group', 'lesson')->pluck('required_count', 'name');

        foreach ($lesson_achievements_criteria as $achievement_name => $required_count) {
            if ($lesson_watched_count >= $required_count) {

                // Check if the achievement is already unlocked
                if (!$user->hasUnlockedAchievement($achievement_name)) {
                    event(new AchievementUnlocked($achievement_name, $user));
                }
            }
        }
    }
}
