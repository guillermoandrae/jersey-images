<?php declare(strict_types = 1);

use App\Giphy;
use App\Response;
use GuzzleHttp\Client;

require dirname(__DIR__) . '/vendor/autoload.php';

return function (array $event) {
    $client = new Client();
    $giphy = new Giphy();
    $giphy->setClient($client);
    $response = new Response();
    $response->setGiphy($giphy);
    $response->setEvent($event);
    return $response->send();
};
