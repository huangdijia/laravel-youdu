<?php

namespace Huangdijia\Youdu\Http;

use GuzzleHttp\Client;
use Huangdijia\Youdu\Contracts\HttpClient;

class Guzzle implements HttpClient
{
    protected $client;

    /**
     * construct
     *
     * @param string $baseUri
     * @param integer $timeout
     */
    public function __construct(string $baseUri = '', int $timeout = 2)
    {
        $this->client = new Client([
            'base_uri' => rtrim($baseUri, '/'),
            'timeout'  => $timeout,
        ]);
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
        // fix url to uri
        $uri      = $this->url2uri($uri) . (false === strpos($uri, '?') ? '?' : '&') . http_build_query($data);
        $response = $this->client->request('GET', $uri);

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
        // fix url to uri
        $uri      = $this->url2uri($uri);
        $response = $this->client->request('POST', $uri, [
            'json' => $data,
        ]);

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
        // fix url to uri
        $uri   = $this->url2uri($uri);
        $parts = [];

        foreach ((array) $data as $key => $value) {
            $parts[] = [
                'name' => $key,
                'contents' => $value,
            ];
        }
        $data     = $parts;
        $response = $this->client->request('POST', $uri, [
            'multipart' => $data,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * url transform to uri
     *
     * @param string $url
     * @return string
     */
    public function url2uri(string $url)
    {
        return preg_replace('/^https?:\/\/([^\/]+)/', '', $url);
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
