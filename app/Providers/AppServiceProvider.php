<?php

namespace App\Providers;

use App\Services\CsvReaderService;
use App\Services\EditorService;
use App\Services\LoginService;
use App\Services\PostContentService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(EditorService::class,function($app){
            return new EditorService();
        });

        $this->app->singleton(CsvReaderService::class,function($app){
            return new CsvReaderService();
        });

        $this->app->singleton(PostContentService::class,function($app){
            return new PostContentService();
        });

        $this->app->singleton(LoginService:: class,function($app){
            return new LoginService();
        });

        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
