<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
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
     * @var array
     */
    protected $options;

    /**
     * construct.
     *
     * @param $options
     */
    public function __construct(string $baseUri = '', int $timeout = 2, array $options = [])
    {
        $this->client = new Client([
            'base_uri' => rtrim($baseUri, '/'),
            'timeout' => $timeout,
        ]);
        $this->options = array_merge_recursive([
            'headers' => [
                'User-Agent' => 'Youdu/2.0',
            ],
        ], $options);
    }

    /**
     * get.
     *
     * @return array
     */
    public function get(string $uri, array $data = [])
    {
        $uri .= (strpos($uri, '?') === false ? '?' : '&') . http_build_query($data);
        $response = $this->client->request('GET', $uri, $this->options);

        return [
            'header' => $response->getHeaders(),
            'body' => $response->getBody()->getContents(),
            'httpCode' => $response->getStatusCode(),
        ];
    }

    /**
     * post.
     *
     * @return array
     */
    public function post(string $uri, array $data = [])
    {
        $response = $this->client->request('POST', $uri, [
            'json' => $data,
        ], $this->options);

        return [
            'header' => $response->getHeaders(),
            'body' => $response->getBody()->getContents(),
            'httpCode' => $response->getStatusCode(),
        ];
    }

    /**
     * upload.
     *
     * @return array
     */
    public function upload(string $uri, array $data = [])
    {
        $parts = [];

        foreach ((array) $data as $key => $value) {
            $parts[] = [
                'name' => $key,
                'contents' => $value,
            ];
        }
        $data = $parts;
        $response = $this->client->request('POST', $uri, [
            'multipart' => $data,
        ], $this->options);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Make a upload file.
     *
     * @return false|resource
     */
    public function makeUploadFile(string $file)
    {
        return fopen($file, 'r');
    }
}
