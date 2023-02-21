<?php

namespace MbcUserProvider;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use MbcUserProvider\Facades\UserProviderFacade;
use MbcUserProvider\Utils\UserProviderExtension;


class MbcUserProviderServiceProvider extends ServiceProvider  implements DeferrableProvider
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

//        $this->app->singleton(UserProviderExtension::class);
//        $this->app->singleton('user_provider_facade', UserProviderFacade::class);
//        $this->app->singleton('user_provider_facade', UserProviderExtension::class);



    }
}
