<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Tests;

use Huangdijia\Youdu\Facades\Youdu;

/**
 * @internal
 * @coversNothing
 */
class DeptTest extends TestCase
{
    public function testLists()
    {
        $depts = Youdu::dept()->lists();

        $this->assertNotEmpty($depts);
        $this->assertIsArray($depts);
    }
}
