<?php declare(strict_types = 1);

use App\Giphy;
use App\Response;
use GuzzleHttp\Client;

require dirname(__DIR__) . '/vendor/autoload.php';

define('GIPHY_API_URL', getenv('GIPHY_API_URL', true) ?: getenv('GIPHY_API_URL'));
define('GIPHY_API_KEY', getenv('GIPHY_API_KEY', true) ?: getenv('GIPHY_API_KEY'));

return function (array $event) {
    $client = new Client();
    $giphy = new Giphy();
    $giphy->setClient($client);
    $response = new Response();
    $response->setGiphy($giphy);
    $response->setEvent($event);
    return $response->send();
};
