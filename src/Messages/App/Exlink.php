<?php

namespace Huangdijia\Youdu\Messages\App;

class Exlink extends Message
{
    protected $exlink;

    /**
     * 隐式链接
     *
     * @param \Huangdijia\Youdu\Messages\App\Items\Exlink $exlink 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
     */
    public function __construct(Items\Exlink $exlink)
    {
        $this->exlink = $exlink;
    }

    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "msgType" => "exlink",
            "exlink"  => $this->exlink->toArray(),
        ];
    }
}
