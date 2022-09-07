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

class Video extends Message
{
    /**
     *  视频消息.
     *
     * @param string $mediaId 视频素材文件id。通过上传素材文件接口获取
     */
    public function __construct(protected string $mediaId = '')
    {
    }

    public function toArray()
    {
        return [
            'sessionId' => $this->sessionId,
            'receiver' => $this->receiver,
            'sender' => $this->sender,
            'msgType' => 'video',
            'video' => [
                'media_id' => $this->mediaId,
            ],
        ];
    }
}
