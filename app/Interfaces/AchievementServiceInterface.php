<?php

namespace App\Interfaces;

use App\Models\User;

interface AchievementServiceInterface {
    public function unlockCommentAchievements(User $user);


}
