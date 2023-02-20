
--- ---------------------------------------------------------------------------
--- ---------------------------------------------------------------------------
--- ---------------------------------------------------------------------------
## Configuration

config/app.php

'providers' => [
        /*
         * Package Service Providers...
         */
\ApiOAuthSdk\Laravel\OAuth2ServiceProvider::class
  
    ],

.env

MBC_OAUTH_CLIENT_ID=5#5 (sans secret)
MBC_OAUTH_CLIENT_SECRET=QubbfUEBHAvrybtHzr010oWtGUpYPQze0i6Sann9
MBC_OAUTH_URL_CALLBACK=http://127.0.0.1:8099/oauth/login/callback

config/services.php

    'mbc' => [
        'client_id' => env('MBC_OAUTH_CLIENT_ID'),
        'client_secret' => env('MBC_OAUTH_CLIENT_SECRET'),
        'redirect' => env('MBC_OAUTH_URL_CALLBACK')
    ],


    'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_URL_CALLBACK')
    ],



use Laravel\Socialite\Facades\Socialite;

try {

    Route::group(['middleware' => ['web']], function () {
        // your routes here

        Route::get('/oauth/redirect', function () {


            return Socialite::driver('mbc')->redirect();
        });

        Route::get('/oauth/login/callback', function () {


            $user = Socialite::driver('mbc')->user();

            $user = \App\Models\User::updateOrCreate([
                'github_id' => $user->id,
            ], [
                'name' => $user->name,
                'email' => $user->email
            ]);



            \Illuminate\Support\Facades\Auth::login($user);

            return redirect('/dashboard');

        });
    });

} catch (\Exception $exception) {
die($exception->getMessage());
}
