<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Facades\HttpClient;

class User
{
    protected $youdu;

    public function __construct(Youdu $youdu)
    {
        $this->youdu = $youdu;
    }

    /**
     * 获取用户列表
     *
     * @param int|null $deptId
     * @return array
     */
    public function simpleList(?int $deptId = 0)
    {
        $resp    = HttpClient::get($this->youdu->url('/cgi/user/simplelist'), ['deptId' => $deptId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->youdu->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true)['userList'] ?? [];
    }

    /**
     * 获取用户列表
     *
     * @param int|null $deptId
     * @return array
     */
    public function lists(?int $deptId = 0)
    {
        $resp    = HttpClient::get($this->youdu->url('/cgi/user/list'), ['deptId' => $deptId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->youdu->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true)['userList'] ?? [];
    }

    /**
     * 用户详情
     *
     * @param integer $userId
     * @return array
     */
    public function get(int $userId)
    {
        $resp    = HttpClient::get($this->youdu->url('/cgi/user/get'), ['userId' => $userId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->youdu->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true) ?? [];
    }

    /**
     * 设置认证信息
     *
     * @param integer $userId
     * @param integer $authType 认证方式：0本地认证，2第三方认证
     * @param string $passwd 原始密码md5加密后转16进制的小写字符串
     * @return bool
     */
    public function setAuth(int $userId, int $authType = 0, string $passwd = '')
    {
        $url = $this->youdu->url('/cgi/user/setauth');
        // TODO
    }

    /**
     * 设置头像
     *
     * @param integer $userId
     * @param string $file
     * @return bool
     */
    public function setAvatar(int $userId, string $file)
    {
        $url = $this->youdu->url('/cgi/avatar/set');
        // TODO
    }

    /**
     * 获取头像
     *
     * @param integer $userId
     * @param integer $size
     * @return string
     */
    public function getAvatar(int $userId, int $size = 0)
    {
        $resp      = HttpClient::get($this->youdu->url('/cgi/avatar/get'), ['userId' => $userId, 'size' => $size]);
        $decrypted = $this->youdu->decryptMsg($resp['body'] ?? '');

        return $decrypted;
    }
}
