<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\TaskList;
use App\Models\Task;
use App\Policies\ListPolicy;
use App\Policies\TaskPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        TaskList::class => ListPolicy::class,
        Task::class => TaskPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan pemetaan Policy untuk TaskList dan Task
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
