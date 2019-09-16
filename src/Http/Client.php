<?php

namespace Huangdijia\Youdu\Http;

class Client
{
    /**
     * GET
     *
     * @param string $url
     * @param array $data
     * @return array|bool
     */
    public function get(string $url = '', array $data = [])
    {
        if (!empty($data)) {
            $url .= (false !== strpos($url, '?') ? '&' : '&') . http_build_query($data);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        
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
     */
    public function post(string $url, array $data = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'content-Length: ' . strlen(json_encode($data)),
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);

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
     */
    public function upload(string $url, array $data = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

}
