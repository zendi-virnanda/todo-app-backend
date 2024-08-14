<?php

namespace App\Providers;

use App\Interfaces\{
    UserServiceInterface,
    TodoServiceInterface
};
use App\Models\Task;
use App\Policies\TodoPolicy;
use App\Services\{
    UserService,
    TodoService
};
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //bind user service
        $this->app->bind(UserServiceInterface::class, UserService::class);

        //bind todo service
        $this->app->bind(TodoServiceInterface::class, TodoService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Task::class, TodoPolicy::class);
    }
}
