<?php
namespace VirtualCloud;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use VirtualCloud\Console\Commands\InstallProvider;

class RedisViewServeProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('redis-view', function ($app) {
            return new RedisViewServeProvider();
        });
    }

    public function boot()
    {
        $this->registerRoutes();
        $this->registerCommands();
        $this->loadViewsFrom(__DIR__.'\resources\views', 'redis-view');
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'\routes\route.php');
        });
    }

    /**
     * Get the Telescope route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'namespace' => 'VirtualCloud\Controllers',
            'prefix' => 'redis-view',
        ];
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallProvider::class,
            ]);
        }
    }
}
