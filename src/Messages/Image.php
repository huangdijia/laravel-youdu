<?php

namespace Huangdijia\Youdu\Messages;

class Image extends Message
{
    protected $toUser;
    protected $toDept;
    protected $mediaId;

    /**
     * 图片消息
     *
     * @param string $toUser 接收成员的帐号列表。多个接收者用竖线分隔，最多支持1000个
     * @param string $toDept 接收部门id列表。多个接收者用竖线分隔，最多支持100个
     * @param string $mediaId 图片素材文件ID。通过上传素材文件接口获取
     */
    public function __construct($toUser = '', $toDept = '', string $mediaId = '')
    {
        $this->toUser  = $toUser;
        $this->toDept  = $toDept;
        $this->mediaId = $mediaId;
    }

    public function toArray()
    {
        return [
            "toUser"  => $this->toUser,
            "toDept"  => $this->toDept,
            "msgType" => "image", // 消息类型，这里固定为：image
            "image"   => [
                "media_id" => $this->mediaId,
            ],
        ];
    }
}
