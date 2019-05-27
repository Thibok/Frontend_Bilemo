<?php

/**
 * Custom manager use for compare and update User vs Facebook User and getting EntityManager
 */

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * UserManager
 */
class UserManager
{
    /**
     * @var EntityManagerInterface
     * @access private
     */
    private $manager;
    
    /**
     * Constructor
     * @access public
     * @param EntityManagerInterface $manager
     * 
     * @return void
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Compare and update User vs Facebook User
     * @access public
     * @param array $userData
     * @param User $user
     * 
     * @return User
     */
    public function compareAndUpdate(array $userData, User $user)
    {
        $updated = false;

        if ($user->getFacebookId() !== $userData['id']) {
            $user->setFacebookId($userData['id']);
            $updated = true;
        }

        if ($user->getFirstName() !== $userData['first_name']) {
            $user->setFirstName($userData['first_name']);
            $updated = true;
        }

        if ($user->getLastName() !== $userData['last_name']) {
            $user->setLastName($userData['last_name']);
            $updated = true;
        }

        if ($updated == true) {
            $this->manager->flush();
        }

        return $user;
    }

    /**
     * Get EntityManager
     * @access public
     * 
     * @return EntityManagerInterface
     */
    public function getManager()
    {
        return $this->manager;
    }
}