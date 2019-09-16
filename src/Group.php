<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Facades\HttpClient;

class Group
{
    protected $youdu;

    public function __construct(Youdu $youdu)
    {
        $this->youdu = $youdu;
    }

    /**
     * 获取群列表
     *
     * @param integer|array $userId
     * @return array
     */
    public function lists($userId = 0)
    {
        $resp    = HttpClient::get($this->youdu->url('/cgi/group/list'), ['userId' => (array) $userId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->youdu->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true)['groupList'] ?? [];
    }

    /**
     * 创建群
     *
     * @param string $name
     * @return string|int
     */
    public function create(string $name)
    {
        $parameters = [
            'buin'    => $this->youdu->getBuin(),
            'appId'   => $this->youdu->getAppId(),
            'encrypt' => $this->youdu->encryptMsg(json_encode([
                'name' => $name,
            ])),
        ];

        $resp = HttpClient::post($this->youdu->url('/cgi/group/create'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new \Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new \Exception($body['errmsg'], $body['errcode']);
        }

        $decrypted = $this->youdu->decryptMsg($body['encrypt']);
        $decoded   = json_decode($decrypted, true);

        return $decoded['id'];
    }

    /**
     * 删除群
     *
     * @param string $groupId
     * @return bool
     */
    public function delete(string $groupId)
    {
        $resp    = HttpClient::get($this->youdu->url('/cgi/group/delete'), ['groupId' => $groupId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        return true;
    }

    /**
     * 修改群名称
     *
     * @param string $groupId
     * @param string $name
     * @return bool
     */
    public function update(string $groupId, string $name)
    {
        $parameters = [
            'buin'    => $this->youdu->getBuin(),
            'appId'   => $this->youdu->getAppId(),
            'encrypt' => $this->youdu->encryptMsg(json_encode([
                'id'   => $groupId,
                'name' => $name,
            ])),
        ];

        $resp = HttpClient::post($this->youdu->url('/cgi/group/update'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new \Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new \Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 查看群信息
     *
     * @param string $groupId
     * @return array
     */
    public function info(string $groupId)
    {
        $resp    = HttpClient::get($this->youdu->url('/cgi/group/info'), ['id' => $groupId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->youdu->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true) ?? [];
    }

    /**
     * 添加群成员
     *
     * @param string $groupId
     * @param array $members
     * @return bool
     */
    public function addMember(string $groupId, array $members = [])
    {
        $parameters = [
            'buin'    => $this->youdu->getBuin(),
            'appId'   => $this->youdu->getAppId(),
            'encrypt' => $this->youdu->encryptMsg(json_encode([
                'id'       => $groupId,
                'userList' => $members,
            ])),
        ];

        $resp = HttpClient::post($this->youdu->url('/cgi/group/addmember'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new \Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new \Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 删除群成员
     *
     * @param string $groupId
     * @param array $members
     * @return bool
     */
    public function delMember(string $groupId, array $members = [])
    {
        $parameters = [
            'encrypt' => $this->youdu->encryptMsg(json_encode([
                'id'       => $groupId,
                'userList' => $members,
            ])),
        ];

        $resp = HttpClient::post($this->youdu->url('/cgi/group/delmember'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new \Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new \Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 查询用户是否是群成员
     *
     * @param string $groupId
     * @param string|integer $userId
     * @return boolean
     */
    public function isMember(string $groupId, $userId)
    {
        $resp    = HttpClient::get($this->youdu->url('/cgi/group/ismember'), ['id' => $groupId, 'userId' => $userId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->youdu->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true)['belong'] ?? false;
    }
}
