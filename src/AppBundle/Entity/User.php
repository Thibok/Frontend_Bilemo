<?php

/**
 * User Entity
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     * @access private
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @access private
     * @ORM\Column(name="first_name", type="string", length=40)
     */
    private $firstName;

    /**
     * @var string
     * @access private
     * @ORM\Column(name="last_name", type="string", length=40)
     */
    private $lastName;

    /**
     * @var string
     * @access private
     * @ORM\Column(name="facebook_id", type="string", length=255, unique=true)
     */
    private $facebookId;

    /**
     * @var array
     * @access private
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @var string
     * @access private
     */
    private $accessToken;

    /**
     * Constructor
     *
     * @param string $facebookId
     * @param string $firstName
     * @param string $lastName
     * @param string $accessToken
     * 
     * @return void
     */
    public function __construct($facebookId, $firstName, $lastName, $accessToken)
    {
        $this->facebookId = $facebookId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->roles = ['ROLE_USER'];
        $this->accessToken = $accessToken;
    }

    /**
     * Get id
     * @access public
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set facebookId
     * @access public
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     * @access public
     * 
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set firstName
     * @access public
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     * @access public
     * 
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     * @access public
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     * @access public
     * 
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set roles
     * @access public
     * @param array $roles
     * 
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     * @access public
     * 
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {

    }
    
    /**
     * @inheritDoc
     */
    public function getSalt()
    {

    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

    }

    /**
     * Set access token
     * @access public
     * @param string $accessToken
     * 
     * @return User
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get access token
     * @access public
     * 
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}

