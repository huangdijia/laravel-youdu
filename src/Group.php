<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Facades\HttpClient;
use Huangdijia\Youdu\Facades\Youdu;

class Group
{
    /**
     * 获取群列表
     *
     * @param integer|array $userId
     * @return array
     */
    public function getList($userId = 0)
    {
        $resp    = HttpClient::get(Youdu::url('/cgi/group/list'), ['userId' => (array) $userId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted  = Youdu::decryptMsg($decoded['encrypt'] ?? ''); // decrypt faild

        return $decrypted['groupList'] ?? [];
    }
}
