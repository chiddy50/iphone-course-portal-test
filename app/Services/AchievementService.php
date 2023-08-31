<?php

namespace App\Services;

use App\Interfaces\AchievementServiceInterface;
use App\Models\User;
use App\Models\Badge;
use App\Models\Achievement;
use App\Events\AchievementUnlocked;

class AchievementService implements AchievementServiceInterface
{
    public function getUnlockedAchievements(User $user)
    {
        $unlockedAchievementIds = $user->achievements->pluck('id')->toArray();

        return Achievement::whereNotIn('id', $unlockedAchievementIds)->pluck('name')->toArray();
    }

    public function getNextAvailableAchievements(User $user)
    {
        $unlockedAchievementIds = $user->achievements->pluck('id')->toArray();

        $nextAvailableAchievements = Achievement::whereNotIn('id', $unlockedAchievementIds)
                    ->get()->groupBy('group')
                    ->map(function ($achievements) {
                        $minAchievement = $achievements->min('required_count');
                        return $achievements->where('required_count', $minAchievement)->pluck('name')->toArray();
                    })
                    ->flatten()
                    ->toArray();
        return $nextAvailableAchievements;
    }

    public function remainingAchievementsToUnlockNextBadge(User $user)
    {
        $badgeMapping = Badge::getBadgeMapping();

        $unlocked_achievements = $user->achievements->count();

        foreach ($badgeMapping as $requiredAchievements) {
            if ($unlocked_achievements < $requiredAchievements) {
                return $requiredAchievements - $unlocked_achievements;
            }
        }

        return 0; // If all achievements are unlocked, no more remaining
    }
}
