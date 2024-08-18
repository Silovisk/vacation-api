<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    VacationPlanRepositoryInterface,
    AuthRepositoryInterface
};
use App\Repositories\{
    VacationPlanRepository,
    AuthRepository
};

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            VacationPlanRepositoryInterface::class,
            VacationPlanRepository::class
        );

        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
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
