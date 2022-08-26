<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\App\Items;

class SysMsg extends Item
{
    /**
     * @param string $title 系统消息标题。最多允许64个字节
     * @param int $popDuration 弹窗显示时间，0及负数弹窗不消失，正数为对应显示秒数
     * @param array $items 消息详细内容
     */
    public function __construct(protected string $title = '', protected int $popDuration = 6, array $items = [])
    {
        $this->items = $items;
    }

    /**
     * 转成 array.
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->title,
            'popDuration' => $this->popDuration,
            'msg' => $this->items,
        ];
    }

    /**
     * 增加文本内容.
     */
    public function addText(string $content)
    {
        $this->items[] = [
            'text' => [
                'content' => $content,
            ],
        ];
    }

    /**
     * 增加链接内容.
     *
     * @param string $title 标题。最多允许64个字节
     * @param string $url 链接
     * @param int $action 链接打开方式。0：直接打开url；1：url后面带上有度客户端userName和token，可做单点登录
     */
    public function addLink(string $title = '', string $url = '', int $action = 0)
    {
        $this->items[] = [
            'link' => [
                'title' => $title,
                'url' => $url,
                'action' => $action,
            ],
        ];
    }
}
