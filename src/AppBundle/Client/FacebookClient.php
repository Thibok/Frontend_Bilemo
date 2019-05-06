<?php

/**
 * Custom client (using GuzzleHttp\Client) requesting Facebook for infos
 */

namespace AppBundle\Client;

use GuzzleHttp\Client;

/**
 * FacebookClient
 */
class FacebookClient
{
    /**
     * @var Client
     * @access private
     */
    private $client;

    /**
     * @var string
     * @access private
     */
    private $clientId;

    /**
     * @var string
     * @access private
     */
    private $clientSecret;

    /**
     * Constructor
     * @access public
     * @param Client $client
     * @param string $clientId
     * @param string $clientSecret
     * 
     * @return void
     */
    public function __construct(Client $client, $clientId, $clientSecret)
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Request Facebook for an access token
     * @access public
     * @param string $code
     * @param string $redirectUrl
     * 
     * @return Response
     */
    public function requestFacebookForToken($code, $redirectUrl)
    {
        $url = 'https://graph.facebook.com/v3.2/oauth/access_token?client_id='.$this->clientId.
            '&redirect_uri='.$redirectUrl.
            '&client_secret='.$this->clientSecret.
            '&code='.$code;

        return $response = $this->client->get($url);;
    }

    /**
     * Request Facebook for an user
     *
     * @param array $fields
     * @param string $accessToken
     * @return Response
     */
    public function requestFacebookForUser(array $fields, $accessToken)
    {
        $url = 'https://graph.facebook.com/me?access_token=' .$accessToken. '&fields=';
        $fieldsLength = count($fields);
        
        for ($index = 0; $index < $fieldsLength; $index++) { 
            $lastIndex = $fieldsLength - $index;
            if ($lastIndex == 1) {
                $url .= $fields[$index];
            } else {
                $url .= $fields[$index]. ',';
            }
        }

        return $response = $this->client->get($url);
    }
}