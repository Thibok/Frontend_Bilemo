<?php

/**
 * SecurityController Test
 */

namespace Tests\AppBundle\Controller;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * SecurityControllerTest
 * @coversDefaultClass \AppBundle\Controller\SecurityController
 */
class SecurityControllerTest extends WebTestCase
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
     * Test path to login
     * @access public
     *
     * @return void
     */
    public function testPathToLogin()
    {
        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Log in')->link();
        $crawler = $this->client->click($link);

        $this->assertSame('Login', $crawler->filter('h1')->text());
    }

    /**
     * Test Logout method of SecurityController
     * @access public
     * @covers ::logoutAction
     *
     * @return void
     */
    public function testLogout()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/space');

        $this->assertEquals(1, $crawler->filter('a:contains("Logout")')->count());

        $this->client->request('GET', '/space/logout');
        $crawler = $this->client->followRedirect();

        $this->assertEquals(0, $crawler->filter('a:contains("Logout")')->count());
    }

    /**
     * Test path to Logout
     * @access public
     *
     * @return void
     */
    public function testPathToLogout()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/space');

        $link = $crawler->filter('#logout')->link();

        $this->client->click($link);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(0, $crawler->filter('a:contains("Logout")')->count());
    }


    /**
     * Log user
     * @access private
     *
     * @return void
     */
    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'secured_area';
        $firewallContext = 'secured_area';

        $token = new UsernamePasswordToken('simpleTest', null, $firewallName, array('ROLE_USER'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->client = null;
    }
}