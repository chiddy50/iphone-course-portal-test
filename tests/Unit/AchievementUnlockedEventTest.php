<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Events\AchievementUnlocked;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class AchievementUnlockedEventTest extends TestCase
{
    public function testAchievementUnlockedEventIsFired()
    {
        Event::fake();

        // Create a User instance without saving to the database
        $user = new User(['id' => 1, 'name' => 'john', 'email' => 'john@email.com', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);

        event(new AchievementUnlocked('First Comment Written', $user));

        Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user) {
            return $event->achievementName === 'First Comment Written' && $event->user->id === $user->id;
        });
    }
}
