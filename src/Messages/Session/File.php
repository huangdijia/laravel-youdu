<?php

declare(strict_types=1);
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\Session;

class File extends Message
{
    /**
     * 文件消息.
     *
     * @param string $mediaId 素材文件id。通过上传素材文件接口获取
     * @param string $name 文件名
     * @param int $size 文件大小
     */
    public function __construct(protected string $mediaId = '', protected string $name = '', protected int $size = 0)
    {
    }

    public function toArray()
    {
        return [
            'sessionId' => $this->sessionId,
            'receiver' => $this->receiver,
            'sender' => $this->sender,
            'msgType' => 'file',
            'file' => [
                'media_id' => $this->mediaId,
                'name' => $this->name,
                'size' => $this->size,
            ],
        ];
    }
}
