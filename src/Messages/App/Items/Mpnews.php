<?php

declare(strict_types=1);
/**
 * This file is part of huangdijia/laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\App\Items;

class Mpnews extends Item
{
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param string $content 正文，最长不超过600个字符，超出部分将自动截取
     * @param string $digest 摘要，最长不超过120个字符，超出部分将自动截取
     */
    public function add(string $title = '', string $mediaId = '', string $content = '', string $digest = '', int $showFont = 1)
    {
        $this->items[] = [
            'title' => $title,
            'media_id' => $mediaId,
            'content' => $content,
            'digest' => $digest,
            'showFront' => $showFont,
        ];
    }
}
