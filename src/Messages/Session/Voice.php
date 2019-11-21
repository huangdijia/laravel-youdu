<?php

namespace Huangdijia\Youdu\Messages\Session;

class Voice extends Message
{
    protected $mediaId;

    /**
     * 语音消息
     *
     * @param string $mediaId 语音素材文件id。通过上传素材文件接口获取
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
            "msgType"   => "voice",
            "voice"     => [
                "media_id" => $this->mediaId,
            ],
        ];
    }
}
