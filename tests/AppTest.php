<?php

namespace Huangdijia\Youdu\Tests;

use Huangdijia\Youdu\Facades\Youdu;

class AppTest extends TestCase
{
    public function testGetAccessToken()
    {
        $token = Youdu::getAccessToken();
        $this->assertNotEmpty($token);
    }

    public function testSendAppText()
    {
        $this->assertTrue(Youdu::sendToUser('10400', 'test'));
    }

    public function testSendAppImage()
    {
        $mediaId = Youdu::media()->upload('/Users/hdj/Downloads/YD20191128-154517.png', 'image');
        $sent    = Youdu::sendToUser('10400', new \Huangdijia\Youdu\Messages\App\Image($mediaId));
        $this->assertTrue($sent);
    }

    public function testSendAppImageFromUrl()
    {
        $mediaId = Youdu::media()->upload('https://www.baidu.com/img/bd_logo1.png', 'image');
        $sent    = Youdu::sendToUser('10400', new \Huangdijia\Youdu\Messages\App\Image($mediaId));
        $this->assertTrue($sent);
    }
}
