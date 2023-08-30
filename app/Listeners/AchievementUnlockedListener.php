<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Events\BadgeUnlocked;
use App\Services\AchievementService;
use Log;

class AchievementUnlockedListener
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
    public function handle(object $event): void
    {
        $achievementName = $event->achievementName;
        $user = $event->user;

        // Unlock the achievement
        $achievement = Achievement::where('name', $achievementName)->first();

        UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id
        ]);

        $nextBadge = $this->achievementService->getNextBadgeLevel($user);
        Log::info(['nextBadge'=>$nextBadge->name ?? null]);


        if ($nextBadge) {
            $badgeName = $nextBadge->name;

            if (!$user->hasBadge($badgeName)) {
                event(new BadgeUnlocked($badgeName, $user));
            }

        }

    }
}
