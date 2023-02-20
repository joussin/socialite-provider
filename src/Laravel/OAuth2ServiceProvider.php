<?php

namespace ApiOAuthSdk\Laravel;

use ApiOAuthSdk\Services\MbcSocialiteManager;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use ApiOAuthSdk\Services\OAuth2ApiClient;
use ApiOAuthSdk\Services\OAuth2ApiClientInterface;
use ApiOAuthSdk\Services\OAuth2TokenService;
use ApiOAuthSdk\Services\OAuth2TokenServiceInterface;
use Laravel\Socialite\Contracts\Factory;


class OAuth2ServiceProvider extends ServiceProvider  implements DeferrableProvider
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
            return new MbcSocialiteManager($app);
        });

        $this->app->singleton(OAuth2TokenServiceInterface::class, OAuth2TokenService::class);
        $this->app->singleton(OAuth2ApiClientInterface::class, function()
        {
            return new OAuth2ApiClient(
                new \GuzzleHttp\Client([
                    'base_uri' => 'http://127.0.0.1:9999'
                ]),
                'http://127.0.0.1:9999',
                env('MBC_OAUTH_URL_CALLBACK')
            );
        });


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
