<?php

namespace Huangdijia\Youdu\Messages;

class SysMsg extends Message
{
    protected $sysMsg;

    /**
     * 隐式链接
     *
     * @param \Huangdijia\Youdu\Messages\Items\SysMsg $sysMsg 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
     */
    public function __construct(\Huangdijia\Youdu\Messages\Items\SysMsg $sysMsg)
    {
        $this->sysMsg = $sysMsg;
    }

    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "msgType" => "sysMsg", // 消息类型，这里固定为：exlink
            "sysMsg"  => $this->sysMsg->toArray(),
        ];
    }
}
