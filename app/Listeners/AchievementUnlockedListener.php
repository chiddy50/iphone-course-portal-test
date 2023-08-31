<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\User;
use App\Models\Badge;
use App\Events\BadgeUnlocked;
use Log;

class AchievementUnlockedListener
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

        $nextBadge = $this->getNextBadgeLevel($user);
        Log::info(['nextBadge'=>$nextBadge->name ?? null]);


        if ($nextBadge) {
            $badgeName = $nextBadge->name;

            if (!$user->hasBadge($badgeName)) {
                event(new BadgeUnlocked($badgeName, $user));
            }

        }

    }

    public function getNextBadgeLevel(User $user)
    {
        $achievements_count = $user->achievements()->count() ?? 0;

        $next_badge = Badge::where('required_achievements', '<=', $achievements_count)
                  ->orderBy('required_achievements', 'desc')
                  ->first();
        return $next_badge ?? null;
    }
}
