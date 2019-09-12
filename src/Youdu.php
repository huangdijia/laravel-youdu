<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Http\Client;
use Huangdijia\Youdu\Crypt\Prpcrypt;
use Illuminate\Support\Facades\Cache;
use Huangdijia\Youdu\Messages\MessageInterface;

class Youdu
{
    private $api;
    private $buin;
    private $appId;
    private $aesKey;

    private $errno;
    private $error;

    public function __construct(string $api = '', int $buin, string $appId = '', string $aesKey = '')
    {
        $this->api    = $api;
        $this->buin   = $buin;
        $this->appId  = $appId;
        $this->aesKey = $aesKey;
    }

    public function getErrno()
    {
        return $this->errno;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getAesKey()
    {
        return $this->aesKey;
    }

    /**
     * 加密
     *
     * @param string $msg
     * @return string|bool
     */
    public function encryptMsg(string $msg = '')
    {
        $pc     = new Prpcrypt($this->aesKey);
        $result = $pc->encrypt($msg, $this->appId);

        if ($result[0] != 0) {
            $this->errno = $result[0];
            $this->error = $result[1];

            return false;
        }

        return $result[1];

    }

    /**
     * 解密
     *
     * @param string|null $encrypted
     * @return bool|string
     */
    public function decryptMsg(?string $encrypted)
    {
        if (strlen($this->aesKey) != 44) {
            $this->errno = ErrorCode::$IllegalAesKey;
            $this->error = 'Illegal AesKey';

            return false;
        }

        $pc     = new Prpcrypt($this->aesKey);
        $result = $pc->decrypt($encrypted, $this->appId);

        if ($result[0] != 0) {
            $this->errno = $result[0];
            $this->error = '';

            return false;
        }

        return $result[1];
    }

    /**
     * 获取token
     *
     * @return bool|string
     */
    public function getAccessToken()
    {
        $encrypted = $this->encryptMsg((string) time());

        if (false === $encrypted) {
            return false;
        }

        $parameters = [
            "buin"    => $this->buin,
            "appId"   => $this->appId,
            "encrypt" => $encrypted,
        ];

        $client = new Client;
        $url    = rtrim($this->api, '/') . '/cgi/gettoken';
        $resp   = $client->post($url, $parameters);
        $body   = json_decode($resp['body']);

        if ($body->errcode == 0) {
            $decrypted = $this->decryptMsg($body->encrypt);

            if (false === $decrypted) {
                $this->errno = ErrorCode::$DecryptAESError;

                return false;
            }

            $decoded = json_decode($decrypted, true);

            return $decoded['accessToken'];
        }

        $this->errno = $body->errcode;
        $this->error = $body->errmsg;

        return false;
    }

    /**
     * 发送消息
     *
     * @param \Huangdijia\Youdu\Messages\MessageInterface $message
     * @return bool
     */
    public function send(MessageInterface $message)
    {
        $token = Cache::remember('youdu:tokens', 2 * 3600, function () {
            return $this->getAccessToken();
        });

        if (!$token) {
            $this->error = 'Get access token faild';

            return false;
        }

        $encrypted  = $this->encryptMsg($message->toJson());
        $parameters = [
            "buin"    => $this->buin,
            "appId"   => $this->appId,
            "encrypt" => $encrypted,
        ];

        $url    = rtrim($this->api, '/') . '/cgi/msg/send?accessToken=' . $token;
        $client = new Client();
        $resp   = $client->post($url, $parameters);

        if ($resp['httpCode'] == 200) {
            $body = json_decode($resp['body'], true);

            if ($body['errcode'] !== 0) {
                $this->errno = $body['errcode'];
                $this->error = $body['errmsg'];

                return false;
            }

            return true;
        }

        $this->errno = ErrorCode::$IllegalHttpReq;
        $this->error = "http request code " . $resp['httpCode'];

        return false;
    }

    public function uploadFile()
    {
        //
    }

    public function downloadFile()
    {
        //
    }
}
