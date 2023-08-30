<?php

namespace App\Interfaces;

use App\Models\User;

interface AchievementServiceInterface {
    public function unlockAchievements(User $user);
    public function getNextBadgeLevel(User $user);
}
