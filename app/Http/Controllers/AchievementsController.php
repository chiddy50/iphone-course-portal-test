<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AchievementService;

class AchievementsController extends Controller
{
    public $achievementService;

    public function __construct(AchievementService $achievementService){
        $this->achievementService = $achievementService;
    }

    public function index(User $user)
    {
        $unlocked_achievements = $this->achievementService->getUnlockedAchievements($user);
        $next_available_achievements = $this->achievementService->getNextAvailableAchievements($user);
        $current_badge = $user->currentBadge();
        $next_badge = $user->getNextBadgeName();
        $remainingAchievementsToUnlockNextBadge = $this->achievementService->remainingAchievementsToUnlockNextBadge($user);

        return response()->json([
            'unlocked_achievements' => $unlocked_achievements,
            'next_available_achievements' => $next_available_achievements,
            'current_badge' => $current_badge,
            'next_badge' => $next_badge,
            'remaing_to_unlock_next_badge' => $remainingAchievementsToUnlockNextBadge
        ]);
    }
}
