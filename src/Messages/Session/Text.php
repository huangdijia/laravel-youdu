<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\Session;

class Text extends Message
{
    protected $content;

    /**
     * 文本消息.
     *
     * @param string $content 消息内容，支持表情
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    public function toArray()
    {
        return [
            'sessionId' => $this->sessionId,
            'receiver' => $this->receiver,
            'sender' => $this->sender,
            'msgType' => 'text',
            'text' => [
                'content' => $this->content,
            ],
        ];
    }
}
