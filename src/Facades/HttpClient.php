<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Huangdijia\Youdu\Http\Client
 * @method static mixed get(string $url = '', array $data = [])
 * @method static mixed post(string $url, array $data = [])
 * @method static string upload(string $url, array $data = [])
 */
class HttpClient extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'youdu.http.client';
    }
}
