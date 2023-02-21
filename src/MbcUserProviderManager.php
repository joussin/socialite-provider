<?php

namespace MbcUserProvider;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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









    /**
     * Build an OAuth 2 provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        return new $provider(
            $this->container->make('request'), $config['client_id'],
            $config['client_secret'], $this->formatRedirectUrl($config),
            Arr::get($config, 'guzzle', [])
        );
    }

    /**
     * Format the callback URL, resolving a relative URI if needed.
     *
     * @param  array  $config
     * @return string
     */
    protected function formatRedirectUrl(array $config)
    {
        $redirect = value($config['redirect']);

        return Str::startsWith($redirect ?? '', '/')
            ? $this->container->make('url')->to($redirect)
            : $redirect;
    }
}
