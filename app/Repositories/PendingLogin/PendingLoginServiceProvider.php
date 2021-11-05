<?php 
namespace App\Repositories\PendingLogin;


use Illuminate\Support\ServiceProvider;

class PendingLoginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\PendingLogin\PendingLoginInterface', 'App\Repositories\PendingLogin\PendingLoginRepository');
    }
}