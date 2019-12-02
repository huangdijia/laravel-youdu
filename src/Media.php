<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Facades\HttpClient;
use Illuminate\Support\Str;

class Media
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 解析 Header
     *
     * @param string|null $header
     *
     * @return array
     */
    protected function decodeHeader(?string $header)
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
     * 上传文件
     *
     * @param string $file
     * @param string $fileType image代表图片、file代表普通文件、voice代表语音、video代表视频
     * @return void
     */
    public function upload(string $file = '', string $fileType = 'file')
    {
        if (!in_array($fileType, ['file', 'voice', 'video', 'image'])) {
            throw new \Exception('Unsupport file type ' . $fileType, 1);
        }

        if (preg_match('/^https?:\/\//i', $file)) { // 远程文件
            $contextOptions = stream_context_create([
                "ssl" => [
                    "verify_peer"      => false,
                    "verify_peer_name" => false,
                ],
            ]);

            $originalContent = file_get_contents($file, false, $contextOptions);
        } else { // 本地文件
            $originalContent = file_get_contents($file);
        }

        // 加密文件
        $tmpFile       = storage_path('app/youdu_' . Str::random());
        $encryptedFile = $this->app->encryptMsg($originalContent);
        $encryptedMsg  = $this->app->encryptMsg(json_encode([
            'type' => $fileType ?? 'file',
            'name' => basename($file),
        ]));

        // 保存加密文件
        if (false === file_put_contents($tmpFile, $encryptedFile)) {
            throw new \Exception('Create tmpfile faild', 1);
        }

        // 封装上传参数
        $parameters = [
            "file"    => HttpClient::makeUploadFile(realpath($tmpFile)),
            "encrypt" => $encryptedMsg,
            "buin"    => $this->app->getBuin(),
            "appId"   => $this->app->getAppId(),
        ];

        // 开始上传
        $url  = $this->app->url('/cgi/media/upload');
        $resp = HttpClient::upload($url, $parameters);

        // 出错后删除加密文件
        if ($resp['errcode'] !== 0) {
            unlink($tmpFile);

            throw new \Exception($resp['errmsg'], $resp['errcode']);
        }

        $decrypted = $this->app->decryptMsg($resp['encrypt']);
        $decoded   = json_decode($decrypted, true);

        if (empty($decoded['mediaId'])) {
            throw new \Exception('mediaId is empty', 1);
        }

        // 删除加密文件
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
    public function download(string $mediaId = '', ?string $savePath = null)
    {
        $savePath   = $savePath ?? config('youdu.file_save_path');
        $encrypted  = $this->app->encryptMsg(json_encode(['mediaId' => $mediaId]));
        $parameters = [
            "buin"    => $this->app->getBuin(),
            "appId"   => $this->app->getAppId(),
            "encrypt" => $encrypted,
        ];

        $url         = $this->app->url('/cgi/media/get');
        $resp        = HttpClient::Post($url, $parameters);
        $header      = $this->decodeHeader($resp['header']);
        $fileInfo    = $this->app->decryptMsg($header['Encrypt']);
        $fileInfo    = json_decode($fileInfo, true);
        $fileContent = $this->app->decryptMsg($resp['body']);

        $saveAs = rtrim($savePath, '/') . '/' . $fileInfo['name'];
        $saved  = file_put_contents($saveAs, $fileContent);

        if (!$saved) {
            throw new \Exception('save faild', 1);
        }

        return true;
    }

    /**
     * 素材文件信息
     *
     * @param string $mediaId
     * @return bool
     */
    public function info(string $mediaId = '')
    {
        $encrypted  = $this->app->encryptMsg(json_encode(['mediaId' => $mediaId]));
        $parameters = [
            "buin"    => $this->app->getBuin(),
            "appId"   => $this->app->getAppId(),
            "encrypt" => $encrypted,
        ];

        $url  = $this->app->url('/cgi/media/search');
        $resp = HttpClient::Post($url, $parameters);

        if ($resp['httpCode'] != 200) {
            throw new \Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new \Exception($body['errmsg'], $body['errcode']);
        }

        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->app->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true);
    }
}
