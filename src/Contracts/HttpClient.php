<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Contracts;

interface HttpClient
{
    public function __construct(string $baseUri = '', int $timeout = 2);

    public function get(string $uri, array $data);

    public function post(string $uri, array $data);

    public function upload(string $uri, array $data);

    public function makeUploadFile(string $file);
}
