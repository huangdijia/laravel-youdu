<?php

namespace Huangdijia\Youdu\Messages\Session;

class Video extends Message
{
    protected $mediaId;

    /**
     *  视频消息
     *
     * @param string $mediaId  视频素材文件id。通过上传素材文件接口获取
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
            "msgType"   => "video",
            "video"     => [
                "media_id" => $this->mediaId,
            ],
        ];
    }
}
