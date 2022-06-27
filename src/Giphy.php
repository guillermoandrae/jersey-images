<?php

namespace App;

use ErrorException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class Giphy
{
    /**
     * @var Client Guzzle's HTTP Client
     */
    private Client $client;

    /**
     * Registers an instance of Guzzle's HTTP client with this object.
     *
     * @param Client $client An instance of Guzzle's HTTP client
     * @return Giphy An instance of this object.
     */
    public function setClient(Client $client): Giphy
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Returns the instance of Guzzle's HTTP client registered with this object.
     *
     * @return Client An instance of Guzzle's HTTP client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Returns the image data.
     *
     * @return array Giphy image data
     * @throws ErrorException|GuzzleException
     */
    public function get(): array
    {
        try {
            $response = $this->getClient()->request('GET', GIPHY_API_URL, [
                'query' => [
                    'api_key' => GIPHY_API_KEY,
                    'tag' => 'trash',
                    'rating' => 'g',
                ]
            ]);
            $responseBody = json_decode($response->getBody(), true);
            $data = $responseBody['data'];
            return [
                'src' => $data['images']['original']['url'],
                'alt' => $data['title']
            ];
        } catch (Exception $ex) {
            throw new ErrorException(sprintf('An error occurred => %s', $ex->getMessage()), $ex->getCode());
        }
    }
}
