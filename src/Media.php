<?php

namespace Huangdijia\Youdu;

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

        $tmpFile       = storage_path('app/youdu_' . Str::random());
        $encryptedFile = $this->app->encryptMsg(file_get_contents($file));
        $encryptedMsg  = $this->app->encryptMsg(json_encode([
            'type' => $fileType ?? 'file',
            'name' => basename($file),
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
        $resp = HttpClient::upload($url, $parameters);

        if ($resp['errcode'] !== 0) {
            unlink($tmpFile);

            throw new \Exception($resp['errmsg'], $resp['errcode']);
        }

        $decrypted = $this->app->decryptMsg($resp['encrypt']);
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
    public function download(string $mediaId = '', ?string $savePath = null)
    {
        $savePath   = $savePath ?? config('youdu.file_save_path');
        $encrypted  = $this->app->encryptMsg(json_encode(['mediaId' => $mediaId]));
        $parameters = [
            "buin"    => $this->buin,
            "appId"   => $this->appId,
            "encrypt" => $encrypted,
        ];

        $url         = $this->url('/cgi/media/get');
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
}