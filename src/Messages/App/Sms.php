<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\App;

class Sms extends Message
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $from;

    /**
     * 图片消息.
     *
     * @param string $from 发送短信的手机号码
     * @param string $content 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
     */
    public function __construct(string $from = '', string $content = '')
    {
        $this->from = $from;
        $this->content = $content;
    }

    /**
     * 转成 array.
     * @return (string|array)[]
     */
    public function toArray()
    {
        return [
            'toUser' => $this->toUser,
            'toDept' => $this->toDept,
            'msgType' => 'sms',
            'sms' => [
                'from' => $this->from,
                'content' => $this->content,
            ],
        ];
    }
}
