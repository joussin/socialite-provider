<?php

namespace MbcUserProvider;

use MbcUserProvider\Two\MbcProvider;
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
            MbcProvider::class, $config
        )
            ->setCodeVerifier('P6oelECwFb5dACCIeafOu6DO2gfBMsOupeap1CiWRg3U3n9PE2tzrsY93xsXonyGkKFYqLexiKVdQ8wPsaRsdrKloR7VvxJ9sIKDEsKJWioeex7kB8NQjucObr2mPjs2')
            ->setHost($config['host'] ?? 'http://0.0.0.0:9999');
    }


    public function getDefaultDriver(): string
    {
        return env('SOCIALITE_DRIVER');
    }
}
