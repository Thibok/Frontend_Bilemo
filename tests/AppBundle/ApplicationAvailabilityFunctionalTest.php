<?php

/**
 * ApplicationAvailabity FunctionalTest
 */

namespace Tests\AppBundle;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * ApplicationAvailabilityFunctionalTest
 */
class ApplicationAvailabilityFunctionalTest extends WebTestCase
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
     * Test if all page with no required authentication is up
     * @access public
     * @param string $url
     * @dataProvider urlProvider
     * 
     * @return void
     */
    public function testPageIsSuccessful($url)
    {
        $this->client->request('GET', $url);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * Url values for testPageIsSuccessful
     * @access public
     *
     * @return array
     */
    public function urlProvider()
    {
        return array(
            array('/'),
        );
    }

    /**
     * Test if all page with required authentication is up
     * @access public
     * @param string $url
     * @dataProvider authUrlProvider
     * 
     * @return void
     */
    public function testPageNeedToBeLoggedIsSuccessful($url)
    {
        $this->logIn();

        $this->client->request('GET', $url);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * Url values for testPageNeedToBeLoggedIsSuccessful
     * @access public
     *
     * @return array
     */
    public function authUrlProvider()
    {
        return array(
            array('/space'),
        );
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