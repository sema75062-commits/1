<?php

namespace App\Providers;

use App\Repositories\Implementations\CardRepository;
use App\Repositories\Implementations\CardSetRepository;
use App\Repositories\Implementations\CenterRepository;
use App\Repositories\Implementations\ChildRepository;
use App\Repositories\Implementations\FamilyAccountRepository;
use App\Repositories\Implementations\RefreshTokenRepository;
use App\Repositories\Implementations\RoleRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Interfaces\CardRepositoryInterface;
use App\Repositories\Interfaces\CardSetRepositoryInterface;
use App\Repositories\Interfaces\CenterRepositoryInterface;
use App\Repositories\Interfaces\ChildRepositoryInterface;
use App\Repositories\Interfaces\FamilyAccountRepositoryInterface;
use App\Repositories\Interfaces\RefreshTokenRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Sanctum::ignoreMigrations();

        $this->app->bind(CardRepositoryInterface::class, CardRepository::class);
        $this->app->bind(CardSetRepositoryInterface::class, CardSetRepository::class);
        $this->app->bind(CenterRepositoryInterface::class, CenterRepository::class);
        $this->app->bind(ChildRepositoryInterface::class, ChildRepository::class);
        $this->app->bind(FamilyAccountRepositoryInterface::class, FamilyAccountRepository::class);
        $this->app->bind(RefreshTokenRepositoryInterface::class, RefreshTokenRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
