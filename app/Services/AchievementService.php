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
        $unlockedAchievements = collect();

        // Get the user's current comment count
        $commentCount = $user->comments()->count();

        // Get comment achievements criteria from the database
        $commentAchievementsCriteria = Achievement::where('group', 'comment')->pluck('required_count', 'name');

        foreach ($commentAchievementsCriteria as $achievementName => $requiredCount) {
            if ($commentCount >= $requiredCount) {

                // Check if the achievement is already unlocked
                if (!$user->hasUnlockedAchievement($achievementName)) {
                    event(new AchievementUnlocked($achievementName, $user));
                }
                $unlockedAchievements->push($achievementName);
            }
        }

        return $unlockedAchievements;
    }

    public function getNextBadgeLevel(User $user)
    {
        $achievementsCount = $user->achievements()->count() ?? 0;

        $nextBadge = Badge::where('required_achievements', '<=', $achievementsCount)
                  ->orderBy('required_achievements', 'desc')
                  ->first();

        return $nextBadge ?? null;
    }
}
