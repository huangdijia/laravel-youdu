<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Facades\HttpClient;
use Huangdijia\Youdu\Facades\Youdu;
use Illuminate\Support\Facades\Cache;

class AccessToken
{
    public function get(int $buin = 0, string $appId = '')
    {
        return Cache::remember('youdu:tokens:' . $appId, 2 * 3600, function () use ($buin, $appId) {
            $encrypted  = Youdu::encryptMsg((string) time());
            $parameters = [
                "buin"    => $buin,
                "appId"   => $appId,
                "encrypt" => $encrypted,
            ];

            $url  = Youdu::url('/cgi/gettoken', false);
            $resp = HttpClient::post($url, $parameters);
            $body = json_decode($resp['body']);

            if ($body->errcode != 0) {
                throw new \Exception($body->errmsg, $body->errcode);
            }

            $decrypted = Youdu::decryptMsg($body->encrypt);
            $decoded   = json_decode($decrypted, true);

            return $decoded['accessToken'];
        });
    }
}
