<?php

namespace ApiOAuthSdk;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;


class ApiOAuthSdkServiceProvider extends ServiceProvider  implements DeferrableProvider
{

    /**
     *
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new MbcUserProviderManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(
            __DIR__ . './../routes/oauth.php'
        );

    }
}
