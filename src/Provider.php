<?php

namespace App;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientExceptionInterface;

abstract class Provider
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function makeRequest($url, $method = 'GET')
    {
        $body = null;
        try {
            $response = $this->client->request($method, $url);
            $body = $response->getBody();
        } catch (ClientExceptionInterface $e) {
            if (401 === $e->getCode()) {
                $this->throwException('Unauthorized.', $e->getCode());
            } elseif (404 === $e->getCode()) {
                $this->throwException('Resource not found.', $e->getCode());
            } else {
                $this->throwException('Failed to get data.');
            }
        }

        return $body->getContents();
    }

    /**
     * @param $message
     * @param int $code
     * @throws \Exception
     */
    public function throwException($message, $code = 400)
    {
        throw new \Exception($message, $code);
    }
}


