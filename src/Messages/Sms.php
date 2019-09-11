<?php

namespace Huangdijia\Youdu\Messages;

class Sms extends Message
{
    protected $toUser;
    protected $toDept;
    protected $mediaId;

    /**
     * 图片消息
     *
     * @param string $toUser 接收成员的帐号列表。多个接收者用竖线分隔，最多支持1000个
     * @param string $toDept 接收部门id列表。多个接收者用竖线分隔，最多支持100个
     * @param string $from 发送短信的手机号码
     * @param string $content 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
     */
    public function __construct($toUser = '', $toDept = '', string $from = '', string $content = '')
    {
        $this->toUser  = $toUser;
        $this->toDept  = $toDept;
        $this->from    = $from;
        $this->content = $content;
    }

    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "msgType" => "sms", // 消息类型，这里固定为：sms
            "sms"     => [
                "from"    => $this->from,
                "content" => $this->content,
            ],
        ];
    }
}
