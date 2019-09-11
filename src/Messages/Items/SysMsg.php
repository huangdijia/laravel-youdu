<?php

namespace Huangdijia\Youdu\Messages\Items;

class Exlink extends Item
{
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * 增加文本内容
     *
     * @param string $content
     * @return void
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
     * 增加链接内容
     *
     * @param string $title 标题。最多允许64个字节
     * @param string $url 链接
     * @param int $action 链接打开方式。0：直接打开url；1：url后面带上有度客户端userName和token，可做单点登录
     * @return void
     */
    public function addLink(string $title = '', string $url = '', int $action = 0)
    {
        $this->items[] = [
            "link" => [
                "title"  => $title,
                "url"    => $url,
                "action" => $action,
            ],
        ];
    }
}
