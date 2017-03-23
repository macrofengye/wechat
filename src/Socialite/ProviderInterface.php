<?php
namespace WeChat\Socialite;

use Slim\Http\Response;

interface ProviderInterface
{
    /**
     * Redirect the user to the authentication page for the provider.
     *
     * @return Response
     */
    public function redirect();

    /**
     * Get the User instance for the authenticated user.
     *
     * @param \WeChat\Socialite\AccessTokenInterface $token
     *
     * @return \WeChat\Socialite\User
     */
    public function user(AccessTokenInterface $token = null);
}
