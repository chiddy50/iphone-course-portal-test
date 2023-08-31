<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Services\AchievementService;

class AchievementsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Create a user
        $user = User::factory()->create();

        // Mock the AchievementService
        $mockAchievementService = $this->mock(AchievementService::class);
        $mockAchievementService->shouldReceive('getUnlockedAchievements')->andReturn([]);
        $mockAchievementService->shouldReceive('getNextAvailableAchievements')->andReturn([]);
        $mockAchievementService->shouldReceive('remainingAchievementsToUnlockNextBadge')->andReturn(0);

        // Call the index endpoint
        $response = $this->get(route('achievements.index', ['user' => $user]));

        // Assert response
        $response->assertStatus(200);
        $response->assertJson([
            'unlocked_achievements' => [],
            'next_available_achievements' => [],
            'current_badge' => null,
            'next_badge' => null,
            'remaing_to_unlock_next_badge' => 0,
        ]);
    }
}

