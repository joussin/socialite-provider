<?php

namespace ApiOAuthSdk\Laravel;

use ApiOAuthSdk\Services\MbcUserProviderManager;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use ApiOAuthSdk\Services\OAuth2ApiClient;
use ApiOAuthSdk\Services\OAuth2ApiClientInterface;
use ApiOAuthSdk\Services\JwtTokenService;
use ApiOAuthSdk\Services\OAuth2TokenServiceInterface;
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

        $this->app->singleton(JwtTokenService::class);


    }

    public function provides()
    {
        return [Factory::class];
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
