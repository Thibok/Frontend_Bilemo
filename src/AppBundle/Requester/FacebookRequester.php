<?php

/**
 * Use FacebookClient and return formatted data (array), manage errors and logs
 */

namespace AppBundle\Requester;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use AppBundle\Client\FacebookClient;
use JMS\Serializer\SerializerInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

/**
 * FacebookRequester
 */
class FacebookRequester
{
    /**
     * @var FacebookClient
     * @access private
     */
    private $fbClient;

    /**
     * @var SerializerInterface
     * @access private
     */
    private $serializer;

    /**
     * @var LoggerInterface
     * @access private
     */
    private $logger;

    /**
     * Constructor
     * @access public
     * @param FacebookClient $fbClient
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * 
     * @return void
     */
    public function __construct(FacebookClient $fbClient, SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->fbClient = $fbClient;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * Get access token with FacebookClient
     * @access public
     * @param string $code
     * @param string $redirectUrl
     * 
     * @return void
     */
    public function getAccessToken($code, $redirectUrl)
    {    
        try {
            $response = $this->fbClient->requestFacebookForToken($code, $redirectUrl);
        } catch(RequestException $e) {
            if ($e instanceof ClientException) {
                $data['error'] = 'Facebook error: Bad request';
            } elseif ($e instanceof ConnectException) {
                $data['error'] = 'Facebook server not respond';
            }

            $this->logger->error($e->getMessage());
            return $data;
        }

        $json = $response->getBody()->getContents();
        $data = $this->serializer->deserialize($json, 'array', 'json');

        return $data;
    }

    /**
     * Get user data with FacebookClient
     * @access public
     * @param array $fields
     * @param string $accessToken
     * 
     * @return void
     */
    public function getUserData(array $fields, $accessToken)
    {
        try {
            $response = $this->fbClient->requestFacebookForUser($fields, $accessToken);
        } catch(RequestException $e) {
            if ($e instanceof ClientException) {
                $userData['error'] = 'Facebook error: Bad request';
            } elseif ($e instanceof ConnectException) {
                $userData['error'] = 'Facebook error: Server not respond';
            }

            $this->logger->error($e->getMessage());
            return $userData;
        }

        $json = $response->getBody()->getContents();
        $userData = $this->serializer->deserialize($json, 'array', 'json');

        return $userData;
    }
}