<?php

namespace MbcUserProvider\Traits;


trait TraitUser
{

    protected $code_verifier;


    protected $host;


    /**
     * Set the Mbc instance host.
     *
     * @param  string|null  $host
     * @return $this
     */
    public function setHost($host)
    {
        if (! empty($host)) {
            $this->host = rtrim($host, '/');
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHost(): string
    {
        return $this->host;
    }


    /**
     * Get the Mbc login request CodeVerifier.
     *
     * @return string
     */
    public function getCodeVerifier(): string
    {
        return $this->code_verifier;
    }


    /**
     * Set the Mbc login request CodeVerifier.
     *
     * @param string $code_verifier
     * @return $this
     */
    public function setCodeVerifier(string $code_verifier)
    {
        $this->code_verifier = $code_verifier;
        return $this;
    }





    /**
     * DOIS DISPARAITRE : mettre 'code_verifier' en session
     *
     * @param $code
     * @return array
     */
    public function getTokenFields($code) : array
    {
        $fields = [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $code,
            'redirect_uri'  => $this->redirectUrl,
        ];

        if ($this->usesPKCE()) {
//            $fields['code_verifier'] = $this->request->session()->pull('code_verifier');

            $fields['code_verifier'] = $this->getCodeVerifier();
        }

        return $fields;
    }


    public function mapObjectToModel(\Laravel\Socialite\Contracts\User $user) : \App\Models\User
    {
        $userLaravel = \App\Models\User::updateOrCreate([
            'email' => $user->email,
        ], [
            'name'  => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'password' => uniqid(), // password | ou '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        return $userLaravel;
    }
}
