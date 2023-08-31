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
        $unlocked_achievement_ids = $user->achievements->pluck('id')->toArray();

        return Achievement::whereNotIn('id', $unlocked_achievement_ids)->pluck('name')->toArray();
    }

    public function getNextAvailableAchievements(User $user)
    {
        $unlocked_achievement_ids = $user->achievements->pluck('id')->toArray();

        $next_available_achievements = Achievement::whereNotIn('id', $unlocked_achievement_ids)
                    ->get()->groupBy('group')
                    ->map(function ($achievements) {
                        $minAchievement = $achievements->min('required_count');
                        return $achievements->where('required_count', $minAchievement)->pluck('name')->toArray();
                    })
                    ->flatten()
                    ->toArray();
        return $next_available_achievements;
    }

    public function remainingAchievementsToUnlockNextBadge(User $user)
    {
        $badge_mapping = Badge::getBadgeMapping();
        $unlocked_achievements = $user->achievements->count();

        foreach ($badge_mapping as $required_achievements) {
            if ($unlocked_achievements < $required_achievements) {
                return $required_achievements - $unlocked_achievements;
            }
        }

        return 0; // If all achievements are unlocked, no more remaining
    }
}
