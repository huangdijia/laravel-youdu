<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Crypt\Prpcrypt;
use Huangdijia\Youdu\Http\Client;
use Huangdijia\Youdu\Messages\MessageInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Youdu
{
    private $api;
    private $buin;
    private $appId;
    private $aesKey;
    private $http;
    private $errno;
    private $error;

    public function __construct(string $api = '', int $buin, string $appId = '', string $aesKey = '')
    {
        $this->api    = $api;
        $this->buin   = $buin;
        $this->appId  = $appId;
        $this->aesKey = $aesKey;
        $this->http   = new Client;
    }

    /**
     * 获取错误编码
     *
     * @return string
     */
    public function getErrno()
    {
        return $this->errno;
    }

    /**
     * 获取错误信息
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 获取 appId
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * 获取 aesKey
     *
     * @return string
     */
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
    private function encryptMsg(string $msg = '')
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
    private function decryptMsg(?string $encrypted)
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
     * 组装 URL
     *
     * @param string $uri
     * @param boolean $withAccessToken
     * @return string
     */
    private function url(string $uri = '', bool $withAccessToken = true)
    {
        $url = rtrim($this->api, '/') . '/' . ltrim($uri, '/');

        if ($withAccessToken) {
            $url .= '?accessToken=' . $this->getAccessToken();
        }

        return $url;
    }

    /**
     * 解析 Header
     *
     * @param string|null $header
     * @return array
     */
    private function decodeHeader(?string $header)
    {
        if (!$header) {
            return [];
        }

        $result  = [];
        $headers = explode("\n", $header);

        foreach ($headers as $h) {
            $row           = explode(":", $h);
            [$key, $value] = [$row[0] ?? '', $row[1] ?? null];

            if (!$key || !$value) {
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * 获取token
     *
     * @return bool|string
     */
    public function getAccessToken()
    {
        return Cache::remember('youdu:tokens:' . $this->appId, 2 * 3600, function () {
            $encrypted = $this->encryptMsg((string) time());

            if (false === $encrypted) {
                return false;
            }

            $parameters = [
                "buin"    => $this->buin,
                "appId"   => $this->appId,
                "encrypt" => $encrypted,
            ];

            $url  = $this->url('/cgi/gettoken', false);
            $resp = $this->http->post($url, $parameters);
            $body = json_decode($resp['body']);

            if ($body->errcode != 0) {
                $this->errno = $body->errcode;
                $this->error = $body->errmsg;

                return false;
            }

            $decrypted = $this->decryptMsg($body->encrypt);

            if (false === $decrypted) {
                $this->errno = ErrorCode::$DecryptAESError;

                return false;
            }

            $decoded = json_decode($decrypted, true);

            $this->errno = null;
            $this->error = null;

            return $decoded['accessToken'];
        });
    }

    /**
     * 发送消息
     *
     * @param \Huangdijia\Youdu\Messages\MessageInterface $message
     * @return bool
     */
    public function send(MessageInterface $message)
    {
        $encrypted  = $this->encryptMsg($message->toJson());
        $parameters = [
            "buin"    => $this->buin,
            "appId"   => $this->appId,
            "encrypt" => $encrypted,
        ];

        $url  = $this->url('/cgi/msg/send');
        $resp = $this->http->post($url, $parameters);

        if ($resp['httpCode'] != 200) {
            $this->errno = ErrorCode::$IllegalHttpReq;
            $this->error = "http request code " . $resp['httpCode'];

            return false;
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            $this->errno = $body['errcode'];
            $this->error = $body['errmsg'];

            return false;
        }

        $this->errno = null;
        $this->error = null;

        return true;
    }

    /**
     * 上传文件
     *
     * @param string $file
     * @param string $fileType image代表图片、file代表普通文件、voice代表语音、video代表视频
     * @return void
     */
    public function uploadFile(string $file = '', string $fileType = 'file')
    {
        if (!in_array($fileType, ['file', 'voice', 'video'])) {
            $this->error = 'Unsupport file type ' . $fileType;

            return false;
        }

        $tmpFile       = storage_path('app/youdu_' . Str::random());
        $encryptedFile = $this->encryptMsg(file_get_contents($file));

        if (false === $encryptedFile) {
            $this->error = 'Encrypt file faild';

            return false;
        }

        if (false === file_put_contents($tmpFile, $encryptedFile)) {
            $this->error = 'Create tmpfile faild';

            return false;
        }

        $encryptedMsg = $this->encryptMsg(json_encode([
            'type' => $fileType ?? 'file',
            'name' => basename($file),
            // 'buin'  => $this->buin,
            // 'appId' => $this->appId,
        ]));

        if (false === $encryptedMsg) {
            unlink($tmpFile);
            $this->error = 'Encrypt msg faild';

            return false;
        }

        $parameters = [
            "file"    => make_curl_file(realpath($tmpFile)),
            "encrypt" => $encryptedMsg,
            "buin"    => $this->buin,
            "appId"   => $this->appId,
        ];

        $url  = $this->url('/cgi/media/upload');
        $resp = $this->http->upload($url, $parameters);

        if ($resp['errcode'] !== 0) {
            unlink($tmpFile);

            $this->errno = $resp['errcode'];
            $this->error = $resp['errmsg'];

            return false;
        }

        $decrypted = $this->decryptMsg($resp['encrypt']);

        if (false === $decrypted) {
            $this->error = 'Decrypt response faild';

            return false;
        }

        $decoded = json_decode($decrypted, true);

        if (empty($decoded['mediaId'])) {
            $this->error = 'mediaId is empty';

            return false;
        }

        unlink($tmpFile);

        $this->errno = null;
        $this->error = null;

        return $decoded['mediaId'];
    }

    /**
     * 下载文件
     *
     * @param string $mediaId
     * @param string|null $savePath
     * @return bool
     */
    public function downloadFile(string $mediaId = '', ?string $savePath = null)
    {
        $savePath   = $savePath ?? config('youdu.file_save_path');
        $encrypted  = $this->encryptMsg(json_encode(['mediaId' => $mediaId]));
        $parameters = [
            "buin"    => $this->buin,
            "appId"   => $this->appId,
            "encrypt" => $encrypted,
        ];

        $url  = $this->url('/cgi/media/get');
        $resp = $this->http->Post($url, $parameters);

        $header   = $this->decodeHeader($resp['header']);
        $fileInfo = $this->decryptMsg($header['Encrypt']);

        if (false === $fileInfo) {
            $this->error = 'Parse fileinfo faild';

            return false;
        }

        $fileInfo    = json_decode($fileInfo, true);
        $fileContent = $this->decryptMsg($resp['body']);

        if (false === $fileContent) {
            $this->error = 'Decrypt file content faild';

            return false;
        }

        $saveAs = rtrim($savePath, '/') . '/' . $fileInfo['name'];
        $saved  = file_put_contents($saveAs, $fileContent);

        if (!$saved) {
            $this->error = 'save faild';

            return false;
        }

        return true;
    }
}
