<?php

namespace Huangdijia\Youdu\Messages\App;

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

    /**
     * 转成 array
     * @return array 
     */
    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "msgType" => "file",
            "file"    => [
                "media_id" => $this->mediaId,
            ],
        ];
    }
}
