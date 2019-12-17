<?php

namespace Huangdijia\Youdu;

use Illuminate\Support\Str;
use Huangdijia\Youdu\Facades\HttpClient;
use Huangdijia\Youdu\Exceptions\ErrorCode;
use Huangdijia\Youdu\Exceptions\Exception;

class User
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 获取用户列表
     *
     * @param int|null $deptId
     * @return array
     */
    public function simpleList(?int $deptId = 0)
    {
        $resp    = HttpClient::get($this->app->url('/cgi/user/simplelist'), ['deptId' => $deptId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->app->decryptMsg($decoded['encrypt'] ?? '');

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
        $resp    = HttpClient::get($this->app->url('/cgi/user/list'), ['deptId' => $deptId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->app->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true)['userList'] ?? [];
    }

    /**
     * 创建用户
     *
     * @param integer|string $userId 用户id(帐号)，企业内必须唯一。长度为1~64个字符（包括汉字和英文字母）
     * @param string $name 用户名称。长度为0~64个字符（包括汉字和英文字母，可为空）
     * @param integer $gender 性别，整型。0表示男性，1表示女性
     * @param string $mobile 手机号码。企业内必须唯一
     * @param string $phone 电话号码
     * @param string $email 邮箱。长度为0~64个字符
     * @param array $dept 所属部门列表,不超过20个
     * @return bool
     */
    public function create($userId, string $name, int $gender = 0, string $mobile = '', string $phone = '', string $email = '', array $dept = [])
    {
        $parameters = $this->app->encryptMsg(json_encode([
            "buin"   => $this->app->getBuin(),
            "appId"  => $this->app->getAppId(),
            "userId" => $userId,
            "name"   => $name,
            "gender" => $gender,
            "mobile" => $mobile,
            "phone"  => $phone,
            "email"  => $email,
            "dept"   => $dept,
        ]));

        $resp = HttpClient::post($this->app->url('/cgi/user/create'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 更新用户
     *
     * @param integer|string $userId 用户id(帐号)，企业内必须唯一。长度为1~64个字符（包括汉字和英文字母）
     * @param string $name 用户名称。长度为0~64个字符（包括汉字和英文字母，可为空）
     * @param integer $gender 性别，整型。0表示男性，1表示女性
     * @param string $mobile 手机号码。企业内必须唯一
     * @param string $phone 电话号码
     * @param string $email 邮箱。长度为0~64个字符
     * @param array $dept 所属部门列表,不超过20个
     * @return bool
     */
    public function update($userId, string $name, int $gender = 0, string $mobile = '', string $phone = '', string $email = '', array $dept = [])
    {
        $parameters = $this->app->encryptMsg(json_encode([
            "buin"   => $this->app->getBuin(),
            "appId"  => $this->app->getAppId(),
            "userId" => $userId,
            "name"   => $name,
            "gender" => $gender,
            "mobile" => $mobile,
            "phone"  => $phone,
            "email"  => $email,
            "dept"   => $dept,
        ]));

        $resp = HttpClient::post($this->app->url('/cgi/user/update'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 更新职位信息
     *
     * @param int|string $userId 用户id(帐号)，企业内必须唯一。长度为1~64个字符（包括汉字和英文字母）
     * @param integer $deptId 部门Id。用户必须在该部门内
     * @param string $position 职务
     * @param integer $weight 职务权重。用户拥有多个职务时，权重值越大的职务排序越靠前
     * @param integer $sortId 用户在部门中的排序，值越大排序越靠前
     * @return bool
     */
    public function updatePosition($userId, int $deptId, string $position = '', int $weight = 0, int $sortId = 0)
    {
        $parameters = $this->app->encryptMsg(json_encode([
            "buin"     => $this->app->getBuin(),
            "appId"    => $this->app->getAppId(),
            "userId"   => $userId,
            "deptId"   => $deptId,
            "position" => $position,
            "weight"   => $weight,
            "sortId"   => $sortId,
        ]));

        $resp = HttpClient::post($this->app->url('/cgi/user/positionupdate'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 删除用户
     *
     * @param int|array $userId
     * @return bool
     */
    public function delete($userId)
    {
        // batch delete
        if (is_array($userId)) {
            $parameters = $this->app->encryptMsg(json_encode([
                "buin"    => $this->app->getBuin(),
                "appId"   => $this->app->getAppId(),
                "delList" => $userId,
            ]));

            $resp = HttpClient::post($this->app->url('/cgi/user/batchdelete'), $parameters);

            if ($resp['httpCode'] != 200) {
                throw new Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
            }

            $body = json_decode($resp['body'], true);

            if ($body['errcode'] !== 0) {
                throw new Exception($body['errmsg'], $body['errcode']);
            }

            return true;
        }

        // single delete
        $resp    = HttpClient::get($this->app->url('/cgi/user/delete'), ['userId' => $userId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        return true;
    }

    /**
     * 用户详情
     *
     * @param integer|string $userId
     * @return array
     */
    public function get($userId)
    {
        $resp    = HttpClient::get($this->app->url('/cgi/user/get'), ['userId' => $userId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->app->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true) ?? [];
    }

    /**
     * 设置认证信息
     *
     * @param integer|string $userId
     * @param integer $authType 认证方式：0本地认证，2第三方认证
     * @param string $passwd 原始密码md5加密后转16进制的小写字符串
     * @return bool
     */
    public function setAuth($userId, int $authType = 0, string $passwd = '')
    {
        // md5 -> hex -> lower
        $passwd = strtolower(bin2hex(md5($passwd)));

        $parameters = $this->app->encryptMsg(json_encode([
            "buin"     => $this->app->getBuin(),
            "appId"    => $this->app->getAppId(),
            "userId"   => $userId,
            "authType" => $authType,
            "passwd"   => $passwd,
        ]));

        $resp = HttpClient::post($this->app->url('/cgi/user/setauth'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 设置头像
     *
     * @param integer|string $userId
     * @param string $file
     * @return bool
     */
    public function setAvatar($userId, string $file)
    {
        if (preg_match('/^https?:\/\//i', $file)) { // 远程文件
            $contextOptions = stream_context_create([
                "ssl" => [
                    "verify_peer"      => false,
                    "verify_peer_name" => false,
                ],
            ]);

            $originalContent = file_get_contents($file, false, $contextOptions);
        } else { // 本地文件
            $originalContent = file_get_contents($file);
        }

        // 加密文件
        $tmpFile       = storage_path('app/youdu_' . Str::random());
        $encryptedFile = $this->app->encryptMsg($originalContent);
        $encryptedMsg  = $this->app->encryptMsg(json_encode([
            'type' => 'image',
            'name' => basename($file),
        ]));

        // 保存加密文件
        if (false === file_put_contents($tmpFile, $encryptedFile)) {
            throw new Exception('Create tmpfile faild', 1);
        }

        // 封装上传参数
        $parameters = [
            "userId"  => $userId,
            "file"    => HttpClient::makeUploadFile(realpath($tmpFile)),
            "encrypt" => $encryptedMsg,
            "buin"    => $this->app->getBuin(),
            "appId"   => $this->app->getAppId(),
        ];

        // 开始上传
        $url  = $this->app->url('/cgi/avatar/set');
        $resp = HttpClient::upload($url, $parameters);

        if ($resp['errcode'] !== 0) {
            unlink($tmpFile);

            return false;
        }

        return true;
    }

    /**
     * 获取头像（头像二进制数据）
     *
     * @param integer|string $userId
     * @param integer $size
     * @return string
     */
    public function getAvatar($userId, int $size = 0)
    {
        $resp      = HttpClient::get($this->app->url('/cgi/avatar/get'), ['userId' => $userId, 'size' => $size]);
        $decrypted = $this->app->decryptMsg($resp['body'] ?? '');

        return $decrypted;
    }

    /**
     * 单点登录
     *
     * @param string $token
     * @return array
     */
    public function identify(string $token)
    {
        $resp = HttpClient::get($this->app->url('/cgi/identify?token=' . $token, false));

        if ($resp['httpCode'] != 200) {
            throw new Exception("http request code " . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $decoded = json_decode($resp['body'], true);

        if (($decoded['status']['code'] ?? 0) != 0) {
            throw new Exception($decoded['status']['message'], 1);
        }

        return $decoded['userInfo'] ?? [];
    }
}
