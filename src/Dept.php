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
        $resp    = HttpClient::get(Youdu::url('/cgi/dept/list'), ['id' => $parentDeptId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = Youdu::decryptMsg($decoded['encrypt'] ?? ''); // decrypt faild

        return $decrypted['deptList'] ?? [];
    }
}
