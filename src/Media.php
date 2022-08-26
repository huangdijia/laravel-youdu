<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Exceptions\ErrorCode;
use Huangdijia\Youdu\Exceptions\Exception;
use Huangdijia\Youdu\Facades\HttpClient;
use Illuminate\Support\Str;

class Media
{
    public function __construct(protected App $app)
    {
    }

    /**
     * 上传文件.
     *
     * @param string $fileType image代表图片、file代表普通文件、voice代表语音、video代表视频
     */
    public function upload(string $file = '', string $fileType = 'file'): string
    {
        if (! in_array($fileType, ['file', 'voice', 'video', 'image'])) {
            throw new Exception('Unsupport file type ' . $fileType, 1);
        }

        if (preg_match('/^https?:\/\//i', $file)) { // 远程文件
            $contextOptions = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $originalContent = file_get_contents($file, false, $contextOptions);
        } else { // 本地文件
            $originalContent = file_get_contents($file);
        }

        // 加密文件
        $tmpFile = storage_path('app/youdu_' . Str::random());
        $encryptedFile = $this->app->encryptMsg($originalContent);
        $encryptedMsg = $this->app->encryptMsg(json_encode([
            'type' => $fileType ?? 'file',
            'name' => basename($file),
        ], JSON_THROW_ON_ERROR));

        // 保存加密文件
        if (file_put_contents($tmpFile, $encryptedFile) === false) {
            throw new Exception('Create tmpfile faild', 1);
        }

        // 封装上传参数
        $parameters = [
            'file' => HttpClient::makeUploadFile(realpath($tmpFile)),
            'encrypt' => $encryptedMsg,
            'buin' => $this->app->getBuin(),
            'appId' => $this->app->getAppId(),
        ];

        // 开始上传
        $url = $this->app->url('/cgi/media/upload');
        $resp = HttpClient::upload($url, $parameters);

        // 出错后删除加密文件
        if ($resp['errcode'] !== 0) {
            unlink($tmpFile);

            throw new Exception($resp['errmsg'], (int) $resp['errcode']);
        }

        $decrypted = $this->app->decryptMsg($resp['encrypt']);
        $decoded = json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);

        if (empty($decoded['mediaId'])) {
            throw new Exception('mediaId is empty', 1);
        }

        // 删除加密文件
        unlink($tmpFile);

        return $decoded['mediaId'];
    }

    /**
     * 下载文件.
     */
    public function download(string $mediaId = '', ?string $savePath = null): bool
    {
        $savePath ??= config('youdu.file_save_path');
        $encrypted = $this->app->encryptMsg(json_encode(['mediaId' => $mediaId], JSON_THROW_ON_ERROR));
        $parameters = [
            'buin' => $this->app->getBuin(),
            'appId' => $this->app->getAppId(),
            'encrypt' => $encrypted,
        ];

        $url = $this->app->url('/cgi/media/get');
        $resp = HttpClient::Post($url, $parameters);
        $header = $this->decodeHeader($resp['header']);
        $fileInfo = $this->app->decryptMsg($header['Encrypt']);
        $fileInfo = json_decode($fileInfo, true, 512, JSON_THROW_ON_ERROR);
        $fileContent = $this->app->decryptMsg($resp['body']);

        $saveAs = rtrim($savePath, '/') . '/' . $fileInfo['name'];
        $saved = file_put_contents($saveAs, $fileContent);

        if (! $saved) {
            throw new Exception('save failed', 1);
        }

        return true;
    }

    /**
     * 素材文件信息.
     */
    public function info(string $mediaId = ''): bool
    {
        $encrypted = $this->app->encryptMsg(json_encode(['mediaId' => $mediaId], JSON_THROW_ON_ERROR));
        $parameters = [
            'buin' => $this->app->getBuin(),
            'appId' => $this->app->getAppId(),
            'encrypt' => $encrypted,
        ];

        $url = $this->app->url('/cgi/media/search');
        $resp = HttpClient::Post($url, $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true, 512, JSON_THROW_ON_ERROR);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        $decoded = json_decode($resp['body'], true, 512, JSON_THROW_ON_ERROR);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->app->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * 解析 Header.
     */
    protected function decodeHeader(?string $header): array
    {
        if (! $header) {
            return [];
        }

        $result = [];
        $headers = explode("\n", $header);

        foreach ($headers as $h) {
            $row = explode(':', $h);
            [$key, $value] = [$row[0] ?? '', $row[1] ?? null];

            if (! $key || ! $value) {
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
