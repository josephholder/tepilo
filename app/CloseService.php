<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;

class CloseService
{
    protected $method;

    protected $uri;

    protected $options;

    public function __construct($method, $options = [], $uri = '')
    {
        $this->setUri($uri);
        $this->setMethod($method);
        $this->setOptions($options);
    }

    /**
     * @return string
     */
    private function getUri()
    {
        return $this->uri;
    }

    /**
     * @param $uri
     */
    private function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    private function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    private function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    private function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    private function setOptions($options)
    {
        $this->options = $options;
    }

    public  function call() {
        $client = new Client([
            'verify' => false, // required as the url is not running on an SSL Certificate
            'allow_redirect' => false,
            'base_uri' => env('CLOSE_IO_URI'),
            'auth' => [
                env('CLOSE_IO_USERNAME', ''),
                env('CLOSE_IO_PASSWORD', '')
            ],
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'on_stats' => function (TransferStats $stats) use (&$url) {
                $url = $stats->getEffectiveUri();
            }
        ]);

        $request = $client->request(
            $this->getMethod(),
            $this->getUri(),
            $this->getOptions()
        );

        return [
            'code' => $request->getStatusCode(),
            'data' => $request->getBody()->getContents()
        ];
    }
}
