<?php

namespace App\Services;

use App\Interfaces\AchievementServiceInterface;
use App\Models\User;
use App\Models\Badge;
use App\Models\Achievement;
use App\Events\AchievementUnlocked;
use Log;

class AchievementService implements AchievementServiceInterface
{
    public function unlockAchievements(User $user)
    {
        $unlocked_achievements = collect();

        // Get the user's current comment count
        $comment_count = $user->comments()->count();

        // Get comment achievements criteria from the database
        $comment_achievements_criteria = Achievement::where('group', 'comment')->pluck('required_count', 'name');

        foreach ($comment_achievements_criteria as $achievement_name => $required_count) {
            if ($comment_count >= $required_count) {

                // Check if the achievement is already unlocked
                if (!$user->hasUnlockedAchievement($achievement_name)) {
                    event(new AchievementUnlocked($achievement_name, $user));
                }
                $unlocked_achievements->push($achievement_name);
            }
        }

        return $unlocked_achievements;
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
