<?php

namespace AppTest;

use App\Giphy;
use App\Response;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    private Response $response;

    private MockHandler $mock;

    public function testUnsupportedMethod(): void
    {
        $this->getResponse()->setEvent(['httpMethod' => 'DELETE']);
        $this->getResponse()->send();
        $this->assertEquals(405, $this->getResponse()->getStatusCode());
    }

    public function testGet(): void
    {
        $imageData = [
            'images' => [
                'original' => [
                    'url' => 'https://my.image.gif'
                ]
            ],
            'title' => 'This is a test image'
        ];
        $this->getMock()->append(new GuzzleResponse(200, [], json_encode(['data' => $imageData])));
        $this->getResponse()->setEvent([
            'httpMethod' => 'GET',
        ]);
        $this->getResponse()->handle();
        $body = $this->getResponse()->getBody();
        $this->assertEquals($imageData['images']['original']['url'], $body['data']['src']);
        $this->assertEquals($imageData['title'], $body['data']['alt']);
    }

    public function testGetWithException(): void
    {
        $statusCode = 500;
        $this->getMock()->reset();
        $this->getMock()->append(new GuzzleResponse($statusCode));
        try {
            $this->getResponse()->setEvent([
                'httpMethod' => 'GET',
            ]);
            $this->getResponse()->handle();
        } catch (Exception $ex) {
            $this->assertStringContainsString('An error occurred =>', $ex->getMessage());
            $this->assertEquals($statusCode, $ex->getCode());
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->response = new Response();
        $this->mock = new MockHandler();
        $handlerStack = HandlerStack::create($this->mock);
        $client = new Client(['handler' => $handlerStack]);
        $giphy = (new Giphy())->setClient($client);
        $this->response->setGiphy($giphy);
    }

    private function getResponse(): Response
    {
        return $this->response;
    }

    private function getMock(): MockHandler
    {
        return $this->mock;
    }
}
