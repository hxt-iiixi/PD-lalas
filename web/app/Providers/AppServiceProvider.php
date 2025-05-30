<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
class AppServiceProvider extends ServiceProvider
{
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
          Paginator::useBootstrap();
          \Log::info('Database file exists: ' . (file_exists(database_path('database.sqlite')) ? 'YES' : 'NO'));

    }
}
