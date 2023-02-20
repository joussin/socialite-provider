
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
        \ApiOAuthSdk\Laravel\ApiOAuthSdkServiceProvider::class
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


### config/services.php

```
'mbc' => [
    'client_id' => env('MBC_OAUTH_CLIENT_ID'),
    'client_secret' => env('MBC_OAUTH_CLIENT_SECRET'),
    'redirect' => env('MBC_OAUTH_URL_CALLBACK')
],
```

### routes/web.php

```
Route::middleware('web')->get('/oauth/redirect', function () {
    return \Laravel\Socialite\Facades\Socialite::driver('mbc')->redirect();
});

Route::middleware('web')->get('/oauth/login/callback', function () {

    $user = Laravel\Socialite\Facades\Socialite::driver('mbc')->user();

    $user = \App\Models\User::updateOrCreate([
        'id' => $user->id,
    ], [
        'name'  => $user->name,
        'email' => $user->email
    ]);
    
    \Illuminate\Support\Facades\Auth::login($user);

    return redirect('/dashboard');
});
```