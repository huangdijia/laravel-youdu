<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Crypt\Prpcrypt;
use Huangdijia\Youdu\Http\Client;
use Huangdijia\Youdu\Messages\MessageInterface;
use Huangdijia\Youdu\Messages\PopWindow;
use Huangdijia\Youdu\Messages\Text;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Youdu
{
    private $api;
    private $buin;
    private $appId;
    private $aesKey;
    private $http;

    public function __construct(string $api = '', int $buin, string $appId = '', string $aesKey = '')
    {
        $this->api    = $api;
        $this->buin   = $buin;
        $this->appId  = $appId;
        $this->aesKey = $aesKey;
        $this->http   = new Client;
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
            throw new \Exception($result[1], $result[0]);
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
            throw new \Exception('Illegal AesKey', ErrorCode::$IllegalAesKey);
        }

        $pc                    = new Prpcrypt($this->aesKey);
        [$errcode, $decrypted] = $pc->decrypt($encrypted, $this->appId);

        if ($errcode != 0) {
            throw new \Exception('Decrypt faild', $errcode);
        }

        return $decrypted;
    }

    /**
     * 组装 URL
     *
     * @param string $uri
     * @param boolean $withAccessToken
     *
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
     *
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
            $encrypted  = $this->encryptMsg((string) time());
            $parameters = [
                "buin"    => $this->buin,
                "appId"   => $this->appId,
                "encrypt" => $encrypted,
            ];

            $url  = $this->url('/cgi/gettoken', false);
            $resp = $this->http->post($url, $parameters);
            $body = json_decode($resp['body']);

            if ($body->errcode != 0) {
                throw new \Exception($body->errmsg, $body->errcode);
            }

            $decrypted = $this->decryptMsg($body->encrypt);
            $decoded   = json_decode($decrypted, true);

            return $decoded['accessToken'];
        });
    }

    /**
     * 发送应用消息
     *
     * @param string $toUser 接收成员的帐号列表。多个接收者用竖线分隔，最多支持1000个
     * @param string $toDept 接收部门id列表。多个接收者用竖线分隔，最多支持100个
     * @param \Huangdijia\Youdu\Messages\MessageInterface|string $message
     * @return bool
     */
    public function send(string $toUser = '', string $toDept = '', $message = '')
    {
        if (is_string($message)) {
            $message = new Text($message);
        }

        if (!($message instanceof MessageInterface)) {
            throw new \Exception("\$message must instanced of " . MessageInterface::class, 1);
        }

        if ($toUser) {
            $message->toUser($toUser);
        }

        if ($toDept) {
            $message->toDept($toDept);
        }

        $encrypted  = $this->encryptMsg($message->toJson());
        $parameters = [
            "buin"    => $this->buin,
            "appId"   => $this->appId,
            "encrypt" => $encrypted,
        ];

        $url  = $this->url('/cgi/msg/send');
        $resp = $this->http->post($url, $parameters);

        if ($resp['httpCode'] != 200) {
            throw new \Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new \Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 发送消息给用户
     *
     * @param string $toUser
     * @param \Huangdijia\Youdu\Messages\MessageInterface|string $message
     * @return bool
     */
    public function sendToUser(string $toUser = '', $message = '')
    {
        return $this->send($toUser, '', $message);
    }

    /**
     * 发送消息至部门
     *
     * @param string $toDept
     * @param \Huangdijia\Youdu\Messages\MessageInterface|string $message
     * @return bool
     */
    public function sendToDept(string $toDept = '', $message = '')
    {
        return $this->send('', $toDept, $message);
    }

    /**
     * 设置通知数
     *
     * @param string $account
     * @param string $tip
     * @param integer $msgCount
     * @return bool
     */
    public function setNoticeCount(string $account = '', string $tip = '', int $msgCount = 0)
    {
        $parameters = [
            'app_id'      => $this->appId,
            'msg_encrypt' => $this->encryptMsg(json_encode([
                "account" => $account,
                "tip"     => $tip,
                "count"   => $msgCount,
            ])),
        ];

        $resp = $this->http->post($this->url('/cgi/set.ent.notice'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new \Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new \Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 应用弹窗
     *
     * @param string $toUser
     * @param string $toDept
     * @param \Huangdijia\Youdu\Messages\PopWindow $message
     * @return bool
     */
    public function popWindow(string $toUser = '', string $toDept = '', PopWindow $message)
    {
        if ($toUser) {
            $message->toUser($toUser);
        }

        if ($toDept) {
            $message->toDept($toDept);
        }

        $parameters = [
            'app_id'      => $this->appId,
            'msg_encrypt' => $this->encryptMsg($message->toJson()),
        ];

        $resp = $this->http->post($this->url('/cgi/popwindow'), $parameters);
        
        if ($resp['httpCode'] != 200) {
            throw new \Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new \Exception($body['errmsg'], $body['errcode']);
        }

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
        if (!in_array($fileType, ['file', 'voice', 'video', 'image'])) {
            throw new \Exception('Unsupport file type ' . $fileType, 1);
        }

        $tmpFile       = storage_path('app/youdu_' . Str::random());
        $encryptedFile = $this->encryptMsg(file_get_contents($file));
        $encryptedMsg  = $this->encryptMsg(json_encode([
            'type' => $fileType ?? 'file',
            'name' => basename($file),
            // 'buin'  => $this->buin,
            // 'appId' => $this->appId,
        ]));

        if (false === file_put_contents($tmpFile, $encryptedFile)) {
            throw new \Exception('Create tmpfile faild', 1);
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

            throw new \Exception($resp['errmsg'], $resp['errcode']);
        }

        $decrypted = $this->decryptMsg($resp['encrypt']);
        $decoded   = json_decode($decrypted, true);

        if (empty($decoded['mediaId'])) {
            throw new \Exception('mediaId is empty', 1);
        }

        unlink($tmpFile);

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

        $url         = $this->url('/cgi/media/get');
        $resp        = $this->http->Post($url, $parameters);
        $header      = $this->decodeHeader($resp['header']);
        $fileInfo    = $this->decryptMsg($header['Encrypt']);
        $fileInfo    = json_decode($fileInfo, true);
        $fileContent = $this->decryptMsg($resp['body']);

        $saveAs = rtrim($savePath, '/') . '/' . $fileInfo['name'];
        $saved  = file_put_contents($saveAs, $fileContent);

        if (!$saved) {
            throw new \Exception('save faild', 1);
        }

        return true;
    }
}
