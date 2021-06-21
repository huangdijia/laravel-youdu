<?php
/**
 * This file is part of Hyperf.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/master/README.md
 * @contact  huangdijia@gmail.com
 */
if (! function_exists('str2hex')) {
    /**
     * 字符串转十六进制.
     * @return string
     */
    function str2hex(string $string)
    {
        $hex = '';

        for ($i = 0; $i < strlen($string); ++$i) {
            $hex .= dechex(ord($string[$i]));
        }

        return strtolower($hex);
    }
}

if (! function_exists('hex2str')) {
    /**
     * 十六进制转字符串.
     * @return string
     */
    function hex2str(string $hex)
    {
        $string = '';

        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }

        return $string;
    }
}
