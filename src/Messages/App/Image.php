<?php

declare(strict_types=1);
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\App;

class Image extends Message
{
    /**
     * 图片消息.
     *
     * @param string $mediaId 图片素材文件ID。通过上传素材文件接口获取
     */
    public function __construct(protected string $mediaId = '')
    {
    }

    /**
     * 转成 array.
     * @return array
     */
    public function toArray()
    {
        return [
            'toUser' => $this->toUser,
            'toDept' => $this->toDept,
            'msgType' => 'image',
            'image' => [
                'media_id' => $this->mediaId,
            ],
        ];
    }
}
