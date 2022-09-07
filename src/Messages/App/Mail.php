<?php

declare(strict_types=1);
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\App;

class Mail extends Message
{
    /**
     * 图片消息.
     *
     * @param string $action 邮件消息类型。new: 新邮件，unread: 未读邮件数
     * @param string $subject 邮件主题。action为new时有效，可为空
     * @param string $fromUser 发送者帐号，action为new时有效
     * @param string $fromEmail 发送者邮件帐号，action为new时有效。fromUser不为空，fromEmail值无效
     * @param string $time 邮件发送时间。为空默认取服务器接收到消息的时间
     * @param string $link 邮件链接。action为new时有效，点此链接即可打开邮件，为空时点击邮件消息默认执行企业邮箱单点登录
     * @param string $unreadCount 未读邮件数。action为unread时有效
     */
    public function __construct(protected string $action = '', protected string $subject = '', protected string $fromUser = '', protected string $fromEmail = '', protected string $link = '', protected int $unreadCount = 0)
    {
    }

    /**
     * 转成 array.
     * @return array
     */
    public function toArray()
    {
        return [
            'toUser' => $this->toUser,
            'toDept' => $this->toDept,
            'msgType' => 'mail',
            'mail' => [
                'action' => $this->action,
                'subject' => $this->subject,
                'fromUser' => $this->fromUser,
                'fromEmail' => $this->fromEmail,
                'time' => time(),
                'link' => $this->link,
                'unreadCount' => $this->unreadCount,
            ],
        ];
    }
}
