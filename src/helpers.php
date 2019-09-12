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
