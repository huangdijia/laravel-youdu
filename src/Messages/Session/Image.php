<?php

namespace Huangdijia\Youdu\Messages\Session;

class Image extends Message
{
    protected $mediaId;

    /**
     * 图片消息
     *
     * @param string $mediaId 图片素材文件id。通过上传素材文件接口获取
     */
    public function __construct(string $mediaId = '')
    {
        $this->mediaId = $mediaId;
    }

    public function toArray()
    {
        return [
            "sessionId" => $this->sessionId,
            "receiver"  => $this->receiver,
            "sender"    => $this->sender,
            "msgType"   => "image",
            "image"     => [
                "media_id" => $this->mediaId,
            ],
        ];
    }
}
