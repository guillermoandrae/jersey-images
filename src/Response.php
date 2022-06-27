<?php

namespace App;

use Guillermoandrae\Lambda\Contracts\AbstractApiGatewayResponse;

final class Response extends AbstractApiGatewayResponse
{
    private Giphy $giphy;

    public function setGiphy(Giphy $giphy): AbstractApiGatewayResponse
    {
        $this->giphy = $giphy;
        return $this;
    }

    public function getGiphy(): Giphy
    {
        return $this->giphy;
    }

    public function handle(): void
    {
        switch ($this->getEvent()['httpMethod']) {
            case 'GET':
                $this->getImage();
                break;
            default:
                $this->setStatusCode(405);
                $this->setBodyData(['message' => 'Unsupported HTTP method']);
                break;
        }
    }

    private function getImage(): void
    {
        $attributes = $this->getGiphy()->get();
        $this->setBodyData($attributes);
    }
}
