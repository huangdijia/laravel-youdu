<?php

declare(strict_types=1);
/**
 * This file is part of huangdijia/laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Http;

use CURLFile;
use Huangdijia\Youdu\Contracts\HttpClient;
use Huangdijia\Youdu\Exceptions\Http\RequestException;
use Illuminate\Support\Arr;

class Curl implements HttpClient
{
    protected string $baseUri;

    protected string $userAgent;

    /**
     * construct.
     */
    public function __construct(string $baseUri = '', protected int $timeout = 2, array $options = [])
    {
        $this->baseUri = trim($baseUri, '/');
        $this->userAgent = Arr::get($options, 'headers.User-Agent', 'Youdu/2.0');
    }

    /**
     * GET.
     *
     * @throws \Huangdijia\Youdu\Exceptions\Http\RequestException
     */
    public function get(string $uri = '', array $data = []): array
    {
        if (! empty($data)) {
            $uri .= (str_contains($uri, '?') ? '&' : '&') . http_build_query($data);
        }

        $uri = $this->baseUri . $uri;

        $options = [
            CURLOPT_URL => $uri,
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => $this->timeout,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            throw new RequestException('Curl Request Error: ' . curl_error($ch), $errno);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        curl_close($ch);

        return [
            'header' => $header,
            'body' => $body,
            'httpCode' => $httpCode,
        ];
    }

    /**
     * POST.
     *
     * @throws \Huangdijia\Youdu\Exceptions\Http\RequestException
     */
    public function post(string $uri, array $data = []): array
    {
        $uri = $this->baseUri . $uri;

        $options = [
            CURLOPT_URL => $uri,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'content-Length: ' . strlen(json_encode($data, JSON_THROW_ON_ERROR)),
            ],
            CURLOPT_POSTFIELDS => json_encode($data, JSON_THROW_ON_ERROR),
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => $this->timeout,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            throw new RequestException('Curl Request Error: ' . curl_error($ch), $errno);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        curl_close($ch);

        return [
            'header' => $header,
            'body' => $body,
            'httpCode' => $httpCode,
        ];
    }

    /**
     * Upload.
     *
     * @throws \Huangdijia\Youdu\Exceptions\Http\RequestException
     */
    public function upload(string $uri, array $data = []): array
    {
        $uri = $this->baseUri . $uri;

        $options = [
            CURLOPT_URL => $uri,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => $this->timeout,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            throw new RequestException('Curl Request Error: ' . curl_error($ch), $errno);
        }

        curl_close($ch);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Make a upload file.
     */
    public function makeUploadFile(string $file): CURLFile
    {
        $mime = mime_content_type($file);
        $info = pathinfo($file);
        $name = $info['basename'];

        return new CURLFile($file, $mime, $name);
    }
}
