<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\AchievementService;
use Log;

class UnlockCommentAchievements
{
    protected $achievementService;

    /**
     * Create the event listener.
     */
    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $user = $event->comment->user;

        // Check if the user has unlocked any new achievements
        $newAchievements = $this->achievementService->unlockCommentAchievements($user);

        Log::info($newAchievements);
    }
}
