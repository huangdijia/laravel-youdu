<?php
/**
 * This file is part of Hyperf.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/master/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Exceptions\ErrorCode;
use Huangdijia\Youdu\Exceptions\Exception;
use Huangdijia\Youdu\Facades\HttpClient;

class Group
{
    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 获取群列表.
     *
     * @param int|string $userId
     * @return array
     */
    public function lists($userId = '')
    {
        $parameters = [];

        if ($userId) {
            $parameters['userId'] = $userId;
        }

        $resp = HttpClient::get($this->app->url('/cgi/group/list'), $parameters);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->app->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true)['groupList'] ?? [];
    }

    /**
     * 创建群.
     *
     * @return int|string
     */
    public function create(string $name)
    {
        $parameters = [
            'buin' => $this->app->getBuin(),
            'appId' => $this->app->getAppId(),
            'encrypt' => $this->app->encryptMsg(json_encode([
                'name' => $name,
            ])),
        ];

        $resp = HttpClient::post($this->app->url('/cgi/group/create'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        $decrypted = $this->app->decryptMsg($body['encrypt']);
        $decoded = json_decode($decrypted, true);

        return $decoded['id'];
    }

    /**
     * 删除群.
     *
     * @return bool
     */
    public function delete(string $groupId)
    {
        $resp = HttpClient::get($this->app->url('/cgi/group/delete'), ['groupId' => $groupId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        return true;
    }

    /**
     * 修改群名称.
     *
     * @return bool
     */
    public function update(string $groupId, string $name)
    {
        $parameters = [
            'buin' => $this->app->getBuin(),
            'appId' => $this->app->getAppId(),
            'encrypt' => $this->app->encryptMsg(json_encode([
                'id' => $groupId,
                'name' => $name,
            ])),
        ];

        $resp = HttpClient::post($this->app->url('/cgi/group/update'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 查看群信息.
     *
     * @return array
     */
    public function info(string $groupId)
    {
        $resp = HttpClient::get($this->app->url('/cgi/group/info'), ['id' => $groupId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->app->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true) ?? [];
    }

    /**
     * 添加群成员.
     *
     * @return bool
     */
    public function addMember(string $groupId, array $members = [])
    {
        $parameters = [
            'buin' => $this->app->getBuin(),
            'appId' => $this->app->getAppId(),
            'encrypt' => $this->app->encryptMsg(json_encode([
                'id' => $groupId,
                'userList' => $members,
            ])),
        ];

        $resp = HttpClient::post($this->app->url('/cgi/group/addmember'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 删除群成员.
     *
     * @return bool
     */
    public function delMember(string $groupId, array $members = [])
    {
        $parameters = [
            'buin'    => $this->app->getBuin(),
            'appId'   => $this->app->getAppId(),
            'encrypt' => $this->app->encryptMsg(json_encode([
                'id' => $groupId,
                'userList' => $members,
            ])),
        ];

        $resp = HttpClient::post($this->app->url('/cgi/group/delmember'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 查询用户是否是群成员.
     *
     * @param int|string $userId
     * @return bool
     */
    public function isMember(string $groupId, $userId)
    {
        $resp = HttpClient::get($this->app->url('/cgi/group/ismember'), ['id' => $groupId, 'userId' => $userId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->app->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true)['belong'] ?? false;
    }
}
