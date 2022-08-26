<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
if (! function_exists('str2hex')) {
    /**
     * 字符串转十六进制.
     */
    function str2hex(string $string): string
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
     */
    function hex2str(string $hex): string
    {
        $string = '';

        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }

        return $string;
    }
}
