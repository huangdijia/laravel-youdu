<?php

namespace Huangdijia\Youdu\Messages;

class File extends Message
{
    protected $mediaId;

    /**
     * 文件消息
     *
     * @param string $mediaId 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
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
            "msgType" => "file", // 消息类型，这里固定为：file
            "file"    => [
                "media_id" => $this->mediaId,
            ],
        ];
    }
}
