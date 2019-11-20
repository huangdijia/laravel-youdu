<?php

namespace Huangdijia\Youdu\Messages;

class Exlink extends Message
{
    protected $exlink;

    /**
     * 隐式链接
     *
     * @param \Huangdijia\Youdu\Messages\Items\Exlink $exlink 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
     */
    public function __construct(\Huangdijia\Youdu\Messages\Items\Exlink $exlink)
    {
        $this->exlink = $exlink;
    }

    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "msgType" => "exlink", // 消息类型，这里固定为：exlink
            "exlink"  => $this->exlink->toArray(),
        ];
    }
}
