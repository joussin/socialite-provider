<?php

namespace MbcUserProvider\Facades;

use Illuminate\Support\Facades\Facade;
use MbcUserProvider\Utils\Entity\JwtToken;
use MbcUserProvider\Utils\UserProviderExtension;
use App\Models\User as UserModel;


/**
 * @method static ?JwtToken parseToken(string $access_token)
 * @method static UserModel mapObjectToModel(\Laravel\Socialite\Contracts\User $user)
 * @method static bool login(\Illuminate\Contracts\Auth\Authenticatable $userLaravel)
 *
 * @see UserProviderExtension
 */
class UserProviderFacade  extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'user_provider_facade';
    }
}