<?php

namespace ApiOAuthSdk\Services;

use Laravel\Socialite\SocialiteManager;

class MbcUserProviderManager extends SocialiteManager
{

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    protected function createMbcDriver()
    {
        $config = $this->config->get('services.mbc');

        return $this->buildProvider(
            MbcUserProvider::class, $config
        );
    }
}
