<?php

namespace App\Providers\Custom;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CommentServiceInterface;
use App\Services\CommentService;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CommentServiceInterface::class,
            CommentService::class
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
