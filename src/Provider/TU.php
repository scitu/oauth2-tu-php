<?php
namespace scitu\OAuth2\Client\Provider;
use League\OAuth2\Client\Provider\AbstractProvider;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

class TU extends AbstractProvider
{    // public function getAuthorizationUrl(array $options = [])
    // {
    //     return "https://api.tu.ac.th/o/authorize/?client_id=<client
    //     id>&response_type=code&state=random_state_string"
    // }
    use BearerAuthorizationTrait;
    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://api.tu.ac.th/o/authorize';
    }
    /**
     * Get access token url to retrieve token
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://api.tu.ac.th/o/token/';
    }
    /**
     * Get provider url to fetch user details
     *
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://api.tu.ac.th/api/me/';
    }
    /**
     * Get the default scopes used by this provider.
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return ['read'];
    }
    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     *
     * @return string Scope separator, defaults to ','
     */
    protected function getScopeSeparator()
    {
        return ' ';
    }
    /**
     * Check a provider response for errors.
     *
     * @param  ResponseInterface $response
     * @param  array|string $data
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            throw new IdentityProviderException(
                $data['error'] ?: $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }
    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     *
     * @return League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new TUResourceOwner($response);
    }
    /**
     * Returns a prepared request for requesting an access token.
     *
     * @param array $params
     *
     * @return Psr\Http\Message\RequestInterface
     */
    protected function getAccessTokenRequest(array $params)
    {
        $request = parent::getAccessTokenRequest($params);
        $uri = $request->getUri()
            ->withUserInfo($this->clientId, $this->clientSecret);
        return $request->withUri($uri);
    }
}