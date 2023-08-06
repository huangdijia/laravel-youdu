<?php

declare(strict_types=1);
/**
 * This file is part of huangdijia/laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\App;

class SysMsg extends Message
{
    protected $onlyOnline;

    /**
     * 系统消息.
     *
     * @param \Huangdijia\Youdu\Messages\App\Items\SysMsg $sysMsg 消息内容，支持表情，最长不超过600个字符，超出部分将自动截取
     */
    public function __construct(protected Items\SysMsg $sysMsg)
    {
    }

    /**
     * 发送所有人或仅在线用户.
     */
    public function toAll(bool $onlyOnline = false)
    {
        $this->onlyOnline = $onlyOnline;
    }

    /**
     * 转成 array.
     * @return (string|array)[]
     */
    public function toArray()
    {
        $data = [
            'msgType' => 'sysMsg',
            'sysMsg' => $this->sysMsg->toArray(),
        ];

        // 发送至用户
        if (! is_null($this->toUser)) {
            $data['toUser'] = $this->toUser;
        }

        // 发送至部门
        if (! is_null($this->toDept)) {
            $data['toDept'] = $this->toDept;
        }

        // 仅发送至在线用户
        if (! is_null($this->onlyOnline)) {
            $data['toAll'] = [
                'onlyOnline' => $this->onlyOnline,
            ];
        }

        return $data;
    }
}
