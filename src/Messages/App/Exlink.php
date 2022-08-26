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

class Exlink extends Message
{
    /**
     * 外链消息.
     *
     * @param \Huangdijia\Youdu\Messages\App\Items\Exlink $exlink 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
     */
    public function __construct(protected Items\Exlink $exlink)
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
            'msgType' => 'exlink',
            'exlink' => $this->exlink->toArray(),
        ];
    }
}
