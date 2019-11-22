<?php

namespace Huangdijia\Youdu\Messages\App;

class SysMsg extends Message
{
    protected $sysMsg;
    protected $onlyOnline;

    /**
     * 隐式链接
     *
     * @param \Huangdijia\Youdu\Messages\App\Items\SysMsg $sysMsg 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
     */
    public function __construct(Items\SysMsg $sysMsg, bool $onlyOnline = false)
    {
        $this->sysMsg     = $sysMsg;
        $this->onlyOnline = $onlyOnline;
    }

    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "toAll"   => [
                "onlyOnline" => $this->onlyOnline,
            ],
            "msgType" => "sysMsg",
            "sysMsg"  => $this->sysMsg->toArray(),
        ];
    }
}
