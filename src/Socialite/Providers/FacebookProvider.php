<?php
namespace WeChat\Socialite\Providers;

use WeChat\Socialite\AccessTokenInterface;
use WeChat\Socialite\ProviderInterface;
use WeChat\Socialite\User;

/**
 * Class FacebookService.
 *
 * @link https://developers.facebook.com/docs/graph-api [Facebook - Graph API]
 */
class FacebookProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The base Facebook Graph URL.
     *
     * @var string
     */
    protected $graphUrl = 'https://graph.facebook.com';

    /**
     * The Graph API version for the request.
     *
     * @var string
     */
    protected $version = 'v2.5';

    /**
     * The user fields being requested.
     *
     * @var array
     */
    protected $fields = ['first_name', 'last_name', 'email', 'gender', 'verified'];

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['email'];

    /**
     * Display the dialog in a popup view.
     *
     * @var bool
     */
    protected $popup = false;

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://www.facebook.com/' . $this->version . '/dialog/oauth', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->graphUrl . '/oauth/access_token';
    }

    /**
     * Get the access token for the given code.
     *
     * @param string $code
     *
     * @return \Socialite\AccessToken
     */
    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);

        return $this->parseAccessToken($response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    protected function parseAccessToken($body)
    {
        parse_str($body, $token);

        return parent::parseAccessToken($token);
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken(AccessTokenInterface $token)
    {
        $appSecretProof = hash_hmac('sha256', $token->getToken(), $this->clientSecret);

        $response = $this->getHttpClient()->get($this->graphUrl . '/' . $this->version . '/me?access_token=' . $token . '&appsecret_proof=' . $appSecretProof . '&fields=' . implode(',', $this->fields), [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        $avatarUrl = $this->graphUrl . '/' . $this->version . '/' . $user['id'] . '/picture';

        $firstName = $this->arrayItem($user, 'first_name');
        $lastName = $this->arrayItem($user, 'last_name');

        return new User([
            'id' => $this->arrayItem($user, 'id'),
            'nickname' => null,
            'name' => $firstName . ' ' . $lastName,
            'email' => $this->arrayItem($user, 'email'),
            'avatar' => $avatarUrl . '?type=normal',
            'avatar_original' => $avatarUrl . '?width=1920',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);

        if ($this->popup) {
            $fields['display'] = 'popup';
        }

        return $fields;
    }

    /**
     * Set the user fields to request from Facebook.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Set the dialog to be displayed as a popup.
     *
     * @return $this
     */
    public function asPopup()
    {
        $this->popup = true;

        return $this;
    }
}
