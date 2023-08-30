<?php

namespace App\Providers\Custom;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\AchievementServiceInterface;
use App\Services\AchievementService;

class AchievementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            AchievementServiceInterface::class,
            AchievementService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
