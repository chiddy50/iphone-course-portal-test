<?php

namespace App\Services;

use App\Interfaces\AchievementServiceInterface;
use Log;
use App\Models\User;
use App\Models\Achievement;

class AchievementService implements AchievementServiceInterface
{
    public function unlockCommentAchievements(User $user)
    {
        $unlockedAchievements = collect();

        // Get the user's current comment count
        $commentCount = $user->comments()->count();

        // Get comment achievements criteria from the database
        $commentAchievementsCriteria = Achievement::where('group', 'comment')->pluck('required_count', 'name');

        // Check if any new comment achievements are unlocked
        foreach ($commentAchievementsCriteria as $achievementName => $requiredCount) {
            if ($commentCount >= $requiredCount) {

                // Check if the achievement is already unlocked
                if (!$user->hasUnlockedAchievement($achievementName)) {
                    // Unlock the achievement
                    $achievement = Achievement::where('name', $achievementName)->first();
                    UserAchievement::create([
                        'user_id' => $user->id,
                        'achievement_id' => $achievement->id,
                    ]);

                    // Fire the AchievementUnlocked event
                    event(new AchievementUnlocked($achievementName, $user));

                    // Add the unlocked achievement to the collection
                    $unlockedAchievements->push($achievementName);
                }
            }
        }
        return $commentAchievementsCriteria;


        return $unlockedAchievements;
    }
}
