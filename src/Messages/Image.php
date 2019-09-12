<?php

namespace Huangdijia\Youdu\Messages;

class Image extends Message
{
    protected $mediaId;

    /**
     * 图片消息
     *
     * @param string $mediaId 图片素材文件ID。通过上传素材文件接口获取
     */
    public function __construct(string $mediaId = '')
    {
        $this->mediaId = $mediaId;
    }

    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "msgType" => "image", // 消息类型，这里固定为：image
            "image"   => [
                "media_id" => $this->mediaId,
            ],
        ];
    }
}
