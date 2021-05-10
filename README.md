# laravel-youdu

[![Latest Stable Version](https://poser.pugx.org/huangdijia/laravel-youdu/version.png)](https://packagist.org/packages/huangdijia/laravel-youdu)
[![Total Downloads](https://poser.pugx.org/huangdijia/laravel-youdu/d/total.png)](https://packagist.org/packages/huangdijia/laravel-youdu)
[![GitHub license](https://img.shields.io/github/license/huangdijia/laravel-youdu)](https://github.com/huangdijia/laravel-youdu)


## Installation

### Laravel

composer

~~~bash
composer require "huangdijia/laravel-youdu:2.*"
~~~

publish

~~~bash
php artisan vendor:publish --provider="Huangdijia\\Youdu\\YouduServiceProvider"
~~~

### Lumen

add `YouduServiceProvider` to `bootstrap/app.php`

~~~php
$app->register(Illuminate\Notifications\NotificationServiceProvider::class); // must before YouduServiceProvider
$app->register(Huangdijia\Youdu\YouduServiceProvider::class);
~~~

copy `youdu.php` to `config/`

~~~bash
cp vendor/huangdijia/laravel-youdu-message/config/youdu.php config
~~~

## Usage

### Send text message

~~~php
use Huangdijia\Youdu\Facades\Youdu;

Youdu::send('user1|user2', 'dept1|dept2', 'test'); // send to user and dept
Youdu::sendToUser('user1|user2', 'test'); // send to user
Youdu::sendToDept('dept1|dept2', 'test'); // send to dept
~~~

### Send other type

~~~php
use Huangdijia\Youdu\Facades\Youdu;

Youdu::send('user1|user2', 'dept1|dept2',new Text('test'));
Youdu::sendToUser('user1|user2', new Image($mediaId)); // $mediaId 通过 uploadFile 接口获得
Youdu::sendToDept('dept1|dept2', new File($mediaId)); // $mediaId 通过 uploadFile 接口获得
// ...
~~~

### Message types

|类型|类|
|--|--|
|文本|Huangdijia\Youdu\Messages\App\Text|
|图片|Huangdijia\Youdu\Messages\App\Image|
|文件|Huangdijia\Youdu\Messages\App\File|
|图文|Huangdijia\Youdu\Messages\App\Mpnews|
|链接|Huangdijia\Youdu\Messages\App\Link|
|外部链接|Huangdijia\Youdu\Messages\App\Exlink|
|系统|Huangdijia\Youdu\Messages\App\SysMsg|
|短信|Huangdijia\Youdu\Messages\App\Sms|
|邮件|Huangdijia\Youdu\Messages\App\Mail|

### Upload file

~~~php
use Huangdijia\Youdu\Facades\Youdu;

Youdu::uploadFile($file, $fileType); // $fileType image代表图片、file代表普通文件、voice代表语音、video代表视频
~~~

### Download file

~~~php
use Huangdijia\Youdu\Facades\Youdu;

Youdu::downloadFile($mediaId, $savePath);
~~~
