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
    public function __construct(Items\SysMsg $sysMsg)
    {
        $this->sysMsg = $sysMsg;
    }

    public function toAll(bool $onlyOnline = false)
    {
        $this->onlyOnline = $onlyOnline;
    }

    public function toArray()
    {
        $data = [
            "msgType" => "sysMsg",
            "sysMsg"  => $this->sysMsg->toArray(),
        ];

        if (!is_null($this->toUser)) {
            $data['toUser'] = $this->toUser;
        }

        if (!is_null($this->toDept)) {
            $data['toDept'] = $this->toDept;
        }

        if (!is_null($this->onlyOnline)) {
            $data['toAll'] = [
                "onlyOnline" => $this->onlyOnline,
            ];
        }

        return $data;
    }
}
