<?php

if (!function_exists('make_curl_file')) {
    /**
     * @param string $file
     *
     * @return CURLFile
     */
    function make_curl_file(string $file)
    {
        $mime = mime_content_type($file);
        $info = pathinfo($file);
        $name = $info['basename'];

        return new CURLFile($file, $mime, $name);
    }
}

if (!function_exists('str2hex')) {
    /**
     * 字符串转十六进制
     * @param string $string
     * @return string
     */
    function str2hex(string $string)
    {
        $hex = "";

        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }

        $hex = strtolower($hex);

        return $hex;
    }
}

if (!function_exists('hex2str')) {
    /**
     * 十六进制转字符串
     * @param string $hex
     * @return string
     */
    function hex2str(string $hex)
    {
        $string = "";

        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }

        return $string;
    }
}
