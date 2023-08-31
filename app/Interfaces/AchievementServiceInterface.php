<?php

namespace App\Interfaces;

use App\Models\User;

interface AchievementServiceInterface {
    public function getUnlockedAchievements(User $user);
    public function getNextAvailableAchievements(User $user);
    public function remainingAchievementsToUnlockNextBadge(User $user);
}
