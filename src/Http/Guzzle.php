<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Http;

use GuzzleHttp\Client;
use Huangdijia\Youdu\Contracts\HttpClient;

class Guzzle implements HttpClient
{
    protected Client $client;

    protected array $options = [];

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
     */
    public function get(string $uri, array $data = []): array
    {
        $uri .= (! str_contains($uri, '?') ? '?' : '&') . http_build_query($data);
        $response = $this->client->request('GET', $uri, $this->options);

        return [
            'header' => $response->getHeaders(),
            'body' => $response->getBody()->getContents(),
            'httpCode' => $response->getStatusCode(),
        ];
    }

    /**
     * post.
     */
    public function post(string $uri, array $data = []): array
    {
        $response = $this->client->request('POST', $uri, [
            'json' => $data,
        ]);

        return [
            'header' => $response->getHeaders(),
            'body' => $response->getBody()->getContents(),
            'httpCode' => $response->getStatusCode(),
        ];
    }

    /**
     * upload.
     */
    public function upload(string $uri, array $data = []): array
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
        ]);

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
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
