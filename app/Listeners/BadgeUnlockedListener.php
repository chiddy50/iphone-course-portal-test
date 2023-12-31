<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Badge;
use App\Models\UserBadge;

class BadgeUnlockedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $badge_name = $event->badge_name;
        $user = $event->user;

        $badge = Badge::where('name', $badge_name)->first();

        if ($badge) {
            UserBadge::create([
                'user_id' => $user->id,
                'badge_id' => $badge->id
            ]);
        }
    }
}
