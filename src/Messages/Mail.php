<?php

namespace Huangdijia\Youdu\Messages;

class Mail extends Message
{
    protected $toUser;
    protected $toDept;
    protected $mediaId;

    /**
     * 图片消息
     *
     * @param string $toUser 接收成员的帐号列表。多个接收者用竖线分隔，最多支持1000个
     * @param string $toDept 接收部门id列表。多个接收者用竖线分隔，最多支持100个
     * @param string $action 邮件消息类型。new: 新邮件，unread: 未读邮件数
     * @param string $subject 邮件主题。action为new时有效，可为空
     * @param string $fromUser 发送者帐号，action为new时有效
     * @param string $fromEmail 发送者邮件帐号，action为new时有效。fromUser不为空，fromEmail值无效
     * @param string $time 邮件发送时间。为空默认取服务器接收到消息的时间
     * @param string $link 邮件链接。action为new时有效，点此链接即可打开邮件，为空时点击邮件消息默认执行企业邮箱单点登录
     * @param string $unreadCount 未读邮件数。action为unread时有效
     */
    public function __construct($toUser = '', $toDept = '', string $action = '', string $subject = '', string $fromUser = '', string $fromEmail = '', string $link, int $unreadCount = 0)
    {
        $this->toUser      = $toUser;
        $this->toDept      = $toDept;
        $this->action      = $action;
        $this->subject     = $subject;
        $this->fromUser    = $fromUser;
        $this->fromEmail   = $fromEmail;
        $this->link        = $link;
        $this->unreadCount = $unreadCount;
    }

    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "msgType" => "mail", // 消息类型，这里固定为：mail
            "mail"    => [
                "action"      => $this->action,
                "subject"     => $this->subject,
                "fromUser"    => $this->fromUser,
                "fromEmail"   => $this->fromEmail,
                "time"        => time(),
                "link"        => $this->link,
                "unreadCount" => $this->unreadCount,
            ],
        ];
    }
}
