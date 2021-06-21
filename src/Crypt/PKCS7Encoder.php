<?php
/**
 * This file is part of Hyperf.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/master/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Crypt;

/**
 * PKCS7Encoder class.
 *
 * 提供基于PKCS7算法的加解密接口.
 */
class PKCS7Encoder
{
    public static $blockSize = 32;

    /**
     * 对需要加密的明文进行填充补位.
     * @param $text 需要进行填充补位操作的明文
     * @return string 补齐明文字符串
     */
    public function encode($text)
    {
        $textLength = strlen($text);

        //计算需要填充的位数
        $amountToPad = self::$blockSize - ($textLength % self::$blockSize);

        if ($amountToPad == 0) {
            $amountToPad = self::$blockSize;
        }

        //获得补位所用的字符
        $padChr = chr($amountToPad);
        $tmp = '';

        for ($index = 0; $index < $amountToPad; ++$index) {
            $tmp .= $padChr;
        }

        return $text . $tmp;
    }

    /**
     * 对解密后的明文进行补位删除.
     * @param decrypted 解密后的明文
     * @param mixed $text
     * @return string 删除填充补位后的明文
     */
    public function decode($text)
    {
        $pad = ord(substr($text, -1));

        if ($pad < 1 || $pad > self::$blockSize) {
            $pad = 0;
        }

        return substr($text, 0, (strlen($text) - $pad));
    }
}
