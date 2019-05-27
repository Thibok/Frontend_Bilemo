<?php

/**
 * Custom provider for get Facebook User
 */

namespace AppBundle\Provider;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use AppBundle\Requester\FacebookRequester;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * FacebookUserProvider
 */
class FacebookUserProvider implements UserProviderInterface
{
    /**
     * @var FacebookRequester
     * @access private
     */
    private $fbRequester;

    /**
     * @var UserManager
     * @access private
     */
    private $userManager;

    /**
     * Constructor
     * @access public
     * @param FacebookRequester $fbRequester
     * @param UserManager $userManager
     * 
     * @return void
     */
    public function __construct(FacebookRequester $fbRequester, UserManager $userManager)
    {
        $this->fbRequester = $fbRequester;
        $this->userManager = $userManager;
    }

    /**
     * Custom method for loading user by access token
     * @access public
     * @param string $accessToken
     * 
     * @return void
     */
    public function loadUserByAccessToken($accessToken)
    {
        $fields = ['id', 'first_name', 'last_name'];

        $userData = $this->fbRequester->getUserData($fields, $accessToken);

        if (isset($userData['error'])) {
            return $userData['error'];
        }

        $manager = $this->userManager->getManager();
        $actualUser = $manager->getRepository(User::class)->findOneByFacebookId($userData['id']);

        if ($actualUser == null) {
            $user = new User(
                $userData['id'],
                $userData['first_name'],
                $userData['last_name'],
                $accessToken
            );

            $manager->persist($user);
            $manager->flush();

        } else {
            $user = $this->userManager->compareAndUpdate($userData, $actualUser);
            $user->setAccessToken($accessToken);
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username)
    {

    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException();
        }
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}