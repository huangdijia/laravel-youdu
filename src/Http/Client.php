<?php

namespace Huangdijia\Youdu\Http;

use Huangdijia\Youdu\Exceptions\Http\RequestException;

class Client
{
    /**
     * GET
     *
     * @param string $url
     * @param array $data
     * @return array|bool
     * @throws \Huangdijia\Youdu\Exceptions\Http\RequestException
     */
    public function get(string $url = '', array $data = [])
    {
        if (!empty($data)) {
            $url .= (false !== strpos($url, '?') ? '&' : '&') . http_build_query($data);
        }

        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_HEADER         => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => true,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            throw new RequestException("Curl Request Error: " . curl_error($ch), $errno);
        }

        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header     = substr($response, 0, $headerSize);
        $body       = substr($response, $headerSize);

        curl_close($ch);

        return [
            'header'   => $header,
            'body'     => $body,
            'httpCode' => $httpCode,
        ];
    }

    /**
     * POST
     *
     * @param string $url
     * @param array $data
     * @return array
     * @throws \Huangdijia\Youdu\Exceptions\Http\RequestException
     */
    public function post(string $url, array $data = [])
    {
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => 1,
            CURLOPT_HEADER         => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'content-Length: ' . strlen(json_encode($data)),
            ],
            CURLOPT_POSTFIELDS     => json_encode($data),
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            throw new RequestException("Curl Request Error: " . curl_error($ch), $errno);
        }

        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header     = substr($response, 0, $headerSize);
        $body       = substr($response, $headerSize);

        curl_close($ch);

        return [
            'header'   => $header,
            'body'     => $body,
            'httpCode' => $httpCode,
        ];
    }

    /**
     * Upload
     *
     * @param string $url
     * @param array $data
     * @return array|bool
     * @throws \Huangdijia\Youdu\Exceptions\Http\RequestException
     */
    public function upload(string $url, array $data = [])
    {
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            throw new RequestException("Curl Request Error: " . curl_error($ch), $errno);
        }

        curl_close($ch);

        return json_decode($response, true);
    }

}
