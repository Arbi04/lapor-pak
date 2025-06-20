<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\ReportRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ResidentRepository;
use App\Interfaces\AuthRepositoryInterface;
use App\Repositories\ReportStatusRepository;
use App\Interfaces\ReportRepositoryInterface;
use App\Repositories\ReportCategoryRepository;
use App\Interfaces\ResidentRepositoryInterface;
use App\Interfaces\ReportStatusRepositoryInterface;
use App\Interfaces\ReportCategoryRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);

        $this->app->bind(ResidentRepositoryInterface::class, ResidentRepository::class);

        $this->app->bind(ReportCategoryRepositoryInterface::class, ReportCategoryRepository::class);

        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);

        $this->app->bind(ReportStatusRepositoryInterface::class, ReportStatusRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
