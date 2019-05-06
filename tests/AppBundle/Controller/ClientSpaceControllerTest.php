<?php

/**
 * ClientSpaceControllerTest
 */

namespace Tests\AppBundle\Controller;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * ClientSpaceControllerTest
 * @coversDefaultClass \AppBundle\Controller\SecurityController
 */
class ClientSpaceControllerTest extends WebTestCase
{
    /**
     * @var Client
     * @access private
     */
    private $client;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->client = self::createClient();
    }

    /**
     * Test user can't access of url when he's not logged
     * @access public
     * @param string $url
     * @dataProvider urlNoAuthUserCantAccess
     *
     * @return void
     */
    public function testNoAuthUserCantAccess($url)
    {
        $this->client->request('GET', $url);
        $crawler = $this->client->followRedirect();

        $this->assertSame('Login', $crawler->filter('h1')->text());
    }

    /**
     * User can't access url when he's not logged
     * @access public
     *
     * @return array
     */
    public function urlNoAuthUserCantAccess()
    {
        return [
            [
                '/space'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->client = null;
    }
}