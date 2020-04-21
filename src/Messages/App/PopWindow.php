<?php

namespace Huangdijia\Youdu\Messages\App;

class PopWindow extends Message
{
    protected $content;

    /**
     * 应用弹窗
     *
     * @param string $url 弹窗打开url
     * @param string $tip 提示内容
     * @param string $title 窗口标题
     * @param integer $width 弹窗宽度
     * @param integer $height 弹窗宽度
     * @param integer $duration 弹窗窗口停留时间。单位：秒，不设置或设置为0会取默认5秒, -1为永久
     * @param integer $position 弹窗位置。 不设置或设置为0默认屏幕中央, 1 左上, 2 右上, 3 右下, 4 左下
     * @param string $noticeId 通知id，用于防止重复弹窗
     * @param integer $pop_mode 打开方式。1 浏览器, 2 窗口, 其他采用应用默认配置
     */
    public function __construct(
        string $url = '',
        string $tip = '',
        string $title = '',
        int $width = 400,
        int $height = 300,
        int $duration = 5,
        int $position = 3,
        string $noticeId = '',
        int $popMode = 1
    ) {
        $this->url      = $url;
        $this->tip      = $tip;
        $this->title    = $title;
        $this->width    = $width;
        $this->height   = $height;
        $this->duration = $duration;
        $this->position = $position;
        $this->noticeId = $noticeId;
        $this->popMode  = $popMode;

    }

    /**
     * 转成 array
     * @return (string|array)[]
     */
    public function toArray()
    {
        return [
            "toUser"    => $this->toUser,
            "toDept"    => $this->toDept,
            "popWindow" => [
                "url"       => $this->url,
                "tip"       => $this->tip,
                "title"     => $this->title,
                "width"     => $this->width,
                "height"    => $this->height,
                "duration"  => $this->duration,
                "position"  => $this->position,
                "notice_id" => $this->noticeId,
                "pop_mode"  => $this->popMode,
            ],
        ];
    }
}
