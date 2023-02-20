
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
#avec notre api oauth2
MBC_OAUTH_CLIENT_ID=5#5 (sans secret)
MBC_OAUTH_CLIENT_SECRET=QubbfUEBHAvrybtHzr010oWtGUpYPQze0i6Sann9
MBC_OAUTH_URL_CALLBACK=http://127.0.0.1:8099/oauth/login/callback

#avec google
GOOGLE_CLIENT_ID=466719391211-bcf6sv3glah2fk2c51c1o05call54d82.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_URL_CALLBACK=http://127.0.0.1:8099/auth/callback
```


### database


php artisan migrate --path=/database/migrations/2014_10_12_000000_create_users_table.php


### config/services.php

```
'mbc' => [
    'client_id' => env('MBC_OAUTH_CLIENT_ID'),
    'client_secret' => env('MBC_OAUTH_CLIENT_SECRET'),
    'redirect' => env('MBC_OAUTH_URL_CALLBACK')
],
```

###   routes/web.php



Route::middleware('web')->get('/oauth/redirect', function () {
return \Laravel\Socialite\Facades\Socialite::driver('mbc')->redirect();
})->name('oauth.login');

Route::middleware('web')->get('/oauth/login/callback', function () {

    $user = Laravel\Socialite\Facades\Socialite::driver('mbc')->user();

    $userLaravel = Laravel\Socialite\Facades\Socialite::driver('mbc')->mapObjectToModel($user);

    \Illuminate\Support\Facades\Auth::login($userLaravel);

    return redirect()->route('backoffice.home');

})->name('oauth.login.callback');

