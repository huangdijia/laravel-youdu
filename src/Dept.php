<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Facades\HttpClient;
use Huangdijia\Youdu\Facades\Youdu;

class Dept
{
    /**
     * 获取部门列表
     *
     * @param integer $parentDeptId
     * @return array|bool
     */
    public function getList(int $parentDeptId = 0)
    {
        return HttpClient::get(Youdu::url('/cgi/dept/list'), ['id' => $parentDeptId]);
    }
}