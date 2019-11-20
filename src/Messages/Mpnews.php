<?php

namespace Huangdijia\Youdu\Messages;

class Mpnews extends Message
{
    protected $mpnews;

    /**
     * 图文消息
     *
     * @param \Huangdijia\Youdu\Messages\Items\Mpnews $mpnews 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
     */
    public function __construct(\Huangdijia\Youdu\Messages\Items\Mpnews $mpnews)
    {
        $this->mpnews = $mpnews;
    }

    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "msgType" => "mpnews", // 消息类型，这里固定为：mpnews
            "mpnews"  => $this->mpnews->toArray(),
        ];
    }
}
