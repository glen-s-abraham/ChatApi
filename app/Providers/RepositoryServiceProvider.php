<?php

namespace App\Providers;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use App\Repositories\MessageRepository;
use App\Repositories\Interfaces\BroadcastRepositoryInterface;
use App\Repositories\BroadcastRepository;
use App\Repositories\Interfaces\UserStatusRepositoryInterface;
use App\Repositories\UserStatusRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(MessageRepositoryInterface::class,MessageRepository::class);
        $this->app->bind(BroadcastRepositoryInterface::class,BroadcastRepository::class);
        $this->app->bind(UserStatusRepositoryInterface::class,UserStatusRepository::class);
    }
}
