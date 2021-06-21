<?php

namespace Huangdijia\Youdu\Http;

use GuzzleHttp\Client;
use Huangdijia\Youdu\Contracts\HttpClient;

class Guzzle implements HttpClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string[]
     */
    protected $options;

    /**
     * construct
     *
     * @param string $baseUri
     * @param integer $timeout
     */
    public function __construct(string $baseUri = '', int $timeout = 2, array $options = [])
    {
        $this->client = new Client([
            'base_uri' => rtrim($baseUri, '/'),
            'timeout'  => $timeout,
        ]);
        $this->options = array_merge_recursive([
            'headers' => [
                'User-Agent' => 'Youdu/1.0',
            ],
        ], $options);
    }

    /**
     * get
     *
     * @param string $uri
     * @param array $data
     * @return array
     */
    public function get(string $uri, array $data = [])
    {
        $uri .= (false === strpos($uri, '?') ? '?' : '&') . http_build_query($data);
        $response = $this->client->request('GET', $uri, $this->options);

        return [
            'header'   => $response->getHeaders(),
            'body'     => $response->getBody()->getContents(),
            'httpCode' => $response->getStatusCode(),
        ];
    }

    /**
     * post
     *
     * @param string $uri
     * @param array $data
     * @return array
     */
    public function post(string $uri, array $data = [])
    {
        $response = $this->client->request('POST', $uri, [
            'json' => $data,
        ], $this->options);

        return [
            'header'   => $response->getHeaders(),
            'body'     => $response->getBody()->getContents(),
            'httpCode' => $response->getStatusCode(),
        ];
    }

    /**
     * upload
     *
     * @param string $uri
     * @param array $data
     * @return array
     */
    public function upload(string $uri, array $data = [])
    {
        $parts = [];

        foreach ((array) $data as $key => $value) {
            $parts[] = [
                'name'     => $key,
                'contents' => $value,
            ];
        }
        $data     = $parts;
        $response = $this->client->request('POST', $uri, [
            'multipart' => $data,
        ], $this->options);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Make a upload file
     *
     * @param string $file
     * @return resource|false
     */
    public function makeUploadFile(string $file)
    {
        return fopen($file, 'r');
    }
}
