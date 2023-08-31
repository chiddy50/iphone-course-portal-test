<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\AchievementService;
use Log;

use App\Models\Achievement;
use App\Events\AchievementUnlocked;


class CommentWrittenListener
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $user = $event->comment->user;

        $comment_count = $user->comments()->count();

        $comment_achievements_criteria = Achievement::where('group', 'comment')->pluck('required_count', 'name');

        foreach ($comment_achievements_criteria as $achievement_name => $required_count) {
            if ($comment_count >= $required_count) {

                // Check if the achievement is already unlocked
                if (!$user->hasUnlockedAchievement($achievement_name)) {
                    event(new AchievementUnlocked($achievement_name, $user));
                }
            }
        }
    }
}
