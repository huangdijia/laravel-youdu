<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
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
     * @param string $itle 标题，最长不超过64个字符，超出部分将自动截取
     * @param string $media_id 封面图片ID。通过上传素材文件接口获取
     * @param string $content 正文，最长不超过600个字符，超出部分将自动截取
     * @param string $digest 摘要，最长不超过120个字符，超出部分将自动截取
     * @param int $showFront 正文是否显示封面图片。1：显示，0：不显示
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
