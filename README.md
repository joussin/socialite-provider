
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


            dd(
                $user
            );

            // OAuth 2.0 providers...

            // $user = Socialite::driver('github')->userFromToken($token);

            $token = $user->token;
            $refreshToken = $user->refreshToken;
            $expiresIn = $user->expiresIn;

            // OAuth 1.0 providers...
            $token = $user->token;
            $tokenSecret = $user->tokenSecret;

            // All providers...
            $user->getId();
            $user->getNickname();
            $user->getName();
            $user->getEmail();
            $user->getAvatar();


            /*
            $githubUser = Socialite::driver('github')->user();

            $user = \App\Models\User::updateOrCreate([
                'github_id' => $githubUser->id,
            ], [
                'name' => $githubUser->name,
                'email' => $githubUser->email,
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);

            \Illuminate\Support\Facades\Auth::login($user);

            return redirect('/dashboard');
            */


        });
    });

} catch (\Exception $exception) {
die($exception->getMessage());
}

