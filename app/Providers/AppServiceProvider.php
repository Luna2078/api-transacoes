<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\Interfaces\AuthInterfaceService;
use App\Services\Interfaces\MailInterfaceService;
use App\Services\Interfaces\TransactionInterfaceService;
use App\Services\Interfaces\UsersInterfaceService;
use App\Services\Interfaces\WalletInterfaceService;
use App\Services\MailService;
use App\Services\TransactionService;
use App\Services\UsersService;
use App\Services\WalletService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
			$this->app->bind(
				AuthInterfaceService::class,
				AuthService::class
			);
			$this->app->bind(
				MailInterfaceService::class,
				MailService::class
			);
			$this->app->bind(
				TransactionInterfaceService::class,
				TransactionService::class
			);
			$this->app->bind(
				UsersInterfaceService::class,
				UsersService::class
			);
			$this->app->bind(
				WalletInterfaceService::class,
				WalletService::class
			);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
