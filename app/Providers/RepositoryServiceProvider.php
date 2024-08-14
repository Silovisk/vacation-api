<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    VacationPlanRepositoryInterface
};
use App\Repositories\{
    VacationPlanRepository
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
