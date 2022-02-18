<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Crypt;

use Huangdijia\Youdu\Exceptions\ErrorCode;
use Throwable;

class SHA1
{
    /**
     * 用SHA1算法生成安全签名.
     * @param string $token 票据
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param string $encrypt 密文消息
     * @param mixed $encrypt_msg
     */
    public function getSHA1($token, $timestamp, $nonce, $encrypt_msg)
    {
        //排序
        try {
            $array = [$encrypt_msg, $token, $timestamp, $nonce];
            sort($array, SORT_STRING);
            $str = implode($array);

            return [ErrorCode::$OK, sha1($str)];
        } catch (Throwable $e) {
            return [ErrorCode::$ComputeSignatureError, $e->getMessage()];
        }
    }
}
