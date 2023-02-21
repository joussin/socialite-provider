
# Configuration


### Installer laravel/socialite

https://laravel.com/docs/10.x/socialite

```
composer require laravel/socialite
```


### composer.json

```
"repositories": [

        {
            "type": "vcs",
            "url": "https://github.com/joussin/socialite-provider.git"
        }

    ],
```


```
"joussin/socialite-provider": "dev-master"
```

### config/app.php

```
'providers' => [
        \MbcUserProvider\MbcUserProviderServiceProvider::class
    ],
```

### .env

```
# choix du provider && api url

SOCIALITE_DRIVER=mbc|google
MBC_OAUTH_API_HOST=http://127.0.0.1:9999

MBC_SERVER_SIDE_API_OAUTH_CLIENT_ID=5
MBC_SERVER_SIDE_API_OAUTH_CLIENT_SECRET=QubbfUEBHAvrybtHzr010oWtGUpYPQze0i6Sann9




LOGIN : 

# conf LOGIN de notre api oauth2

MBC_LOGIN_OAUTH_CLIENT_ID=5
MBC_LOGIN_OAUTH_CLIENT_SECRET=QubbfUEBHAvrybtHzr010oWtGUpYPQze0i6Sann9
MBC_LOGIN_OAUTH_URL_CALLBACK=http://127.0.0.1:8000/oauth/login/callback


# conf LOGIN de l'api Login de Google
GOOGLE_CLIENT_ID=466719391211-bcf6sv3glah2fk2c51c1o05call54d82.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_URL_CALLBACK=http://127.0.0.1:8000/auth/callback
```


### database


php artisan migrate --path=/database/migrations/2014_10_12_000000_create_users_table.php


### config/services.php

```


    'mbc' => [
    
        'host' => env('MBC_OAUTH_API_HOST'),
        'client_id' => env('MBC_LOGIN_OAUTH_CLIENT_ID'),
        'client_secret' => env('MBC_LOGIN_OAUTH_CLIENT_SECRET'),
        'redirect' => env('MBC_LOGIN_OAUTH_URL_CALLBACK'),
        
        
        'server_client_id' => env('MBC_SERVER_SIDE_API_OAUTH_CLIENT_ID'),
        'server_client_secret' => env('MBC_SERVER_SIDE_API_OAUTH_CLIENT_SECRET'),
    ],

    'google' => [
    
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_URL_CALLBACK')
    ],


 
```

###   routes/web.php



Route::middleware('web')->get('/oauth/redirect', function () {
    return \Laravel\Socialite\Facades\Socialite::driver( env('SOCIALITE_DRIVER') )->redirect();
})->name('login');

Route::middleware('web')->get('/oauth/login/callback', function () {

    $user = Laravel\Socialite\Facades\Socialite::driver( env('SOCIALITE_DRIVER') )->user();

        $userLaravel = \MbcUserProvider\Utils\UserProviderExtension::mapObjectToModel($user);
    $login = \MbcUserProvider\Utils\UserProviderExtension::login($userLaravel);

    return redirect()->route('backoffice.home');

})->name('login.callback');

