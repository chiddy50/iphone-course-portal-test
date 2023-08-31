<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Events\BadgeUnlocked;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class BadgeUnlockedEventTest extends TestCase
{
    public function testBadgeUnlockedEventIsFired()
    {
        Event::fake();

        // Create a User instance without saving to the database
        $user = new User(['id' => 1, 'name' => 'john', 'email' => 'john@email.com', 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);

        event(new BadgeUnlocked('intermediate', $user));

        Event::assertDispatched(BadgeUnlocked::class, function ($event) use ($user) {
            return $event->badge_name === 'intermediate' && $event->user->id === $user->id;
        });
    }
}
