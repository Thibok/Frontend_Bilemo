<?php

/**
 * Authenticate an user with Facebook
 */

namespace AppBundle\Authenticator;

use Doctrine\ORM\ORMException;
use AppBundle\Requester\FacebookRequester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Doctrine\DBAL\Exception\ConnectionException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * FacebookAuthenticator
 */
class FacebookAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var FacebookRequester
     * @access private
     */
    private $fbRequester;

    /**
     * @var RouterInterface
     * @access private
     */
    private $router;

    /**
     * @var CsrfTokenManagerInterface
     * @access private
     */
    private $csrfTokenManager;

    /**
     * Constructor
     * @access public
     * @param FacebookRequester $fbRequester
     * @param RouterInterface $router
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * 
     * @return void
     */
    public function __construct(
        FacebookRequester $fbRequester,
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->fbRequester = $fbRequester;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @inheritDoc
     */
    public function createToken(Request $request, $providerKey)
    {
        $csrfToken = $request->query->get('state');

        if (false === $this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }

        if ($code = $request->query->get('code')) {
            $redirectUri = $this->router->generate('bi_space', [], ROUTER::ABSOLUTE_URL);

            $data = $this->fbRequester->getAccessToken($code, $redirectUri);

            if (isset($data['error'])) {
                throw new CustomUserMessageAuthenticationException($data['error']);
            }

            $accessToken = $data['access_token'];
        } else {
            throw new CustomUserMessageAuthenticationException('You must be logged to access this page !');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $accessToken,
            $providerKey
        );
    }

    /**
     * @inheritDoc
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @inheritDoc
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $accessToken = $token->getCredentials();

        try {
            $userResult = $userProvider->loadUserByAccessToken($accessToken);
        } catch(\Exception $e) {
            if ($e instanceof ConnectionException || $e instanceof ORMException) {
                $userResult = 'An error is occured with the server';
            }
        }

        if (is_string($userResult)) {
            throw new CustomUserMessageAuthenticationException($userResult);
        }

        return new PreAuthenticatedToken(
            $userResult,
            $accessToken,
            $providerKey,
            $userResult->getRoles()
        );
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
       $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

       $url = $this->router->generate('bi_login');

       return new RedirectResponse($url);
    }
}