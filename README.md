# laravel-youdu

## Installation

### Laravel

composer

~~~bash
composer require huangdijia/laravel-youdu
~~~

publish

~~~bash
php artisan vendor:publish --provider="Huangdijia\\Youdu\\YouduServiceProvider"
~~~

### Lumen

> TODO

## Usage

### Send message

~~~php
Huangdijia\Youdu\Facades\Youdu::send('user1,user2', 'dept1,dept2', 'test'); // send to user and dept
Huangdijia\Youdu\Facades\Youdu::sendToUser('user1,user2', 'test'); // send to user
Huangdijia\Youdu\Facades\Youdu::sendToDept('dept1,dept2', 'test'); // send to user
~~~

### Message types

|类型|类|
|--|--|
|文本|Huangdijia\Youdu\Messages\Text|
|图片|Huangdijia\Youdu\Messages\Image|
|文件|Huangdijia\Youdu\Messages\File|
|图文|Huangdijia\Youdu\Messages\Mpnews|
|链接|Huangdijia\Youdu\Messages\Link|
|外部链接|Huangdijia\Youdu\Messages\Exlink|
|系统|Huangdijia\Youdu\Messages\SysMsg|
|短信|Huangdijia\Youdu\Messages\Sms|
|邮件|Huangdijia\Youdu\Messages\Mail|
