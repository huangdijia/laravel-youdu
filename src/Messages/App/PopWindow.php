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

class PopWindow extends Message
{
    /**
     * @var string
     */
    protected $content;

    /**
     * 应用弹窗.
     *
     * @param string $url 弹窗打开url
     * @param string $tip 提示内容
     * @param string $title 窗口标题
     * @param int $width 弹窗宽度
     * @param int $height 弹窗宽度
     * @param int $duration 弹窗窗口停留时间。单位：秒，不设置或设置为0会取默认5秒, -1为永久
     * @param int $position 弹窗位置。 不设置或设置为0默认屏幕中央, 1 左上, 2 右上, 3 右下, 4 左下
     * @param string $noticeId 通知id，用于防止重复弹窗
     */
    public function __construct(protected string $url = '', protected string $tip = '', protected string $title = '', protected int $width = 400, protected int $height = 300, protected int $duration = 5, protected int $position = 3, protected string $noticeId = '', protected int $popMode = 1)
    {
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
            'popWindow' => [
                'url' => $this->url,
                'tip' => $this->tip,
                'title' => $this->title,
                'width' => $this->width,
                'height' => $this->height,
                'duration' => $this->duration,
                'position' => $this->position,
                'notice_id' => $this->noticeId,
                'pop_mode' => $this->popMode,
            ],
        ];
    }
}
